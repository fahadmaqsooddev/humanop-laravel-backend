<?php

namespace App\Services\Assessment;

use App\Events\Assessment\SubmitAssessment;
use App\Events\DailyTip\NewDailyTip;
use App\Helpers\ActivityLogs\ActivityLogger;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Answer;
use App\Models\AnswerCode;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\AssessmentDetail;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Client\Gamification\GamificationBadgesAchievement;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Question;
use Carbon\Carbon;
use App\Services\GeoService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\HotSpotUser;

class AssessmentService
{
    public static function submitAnswers(array $answerIds,bool $assessmentFromApp = false): string
    {
        $user = Helpers::getUser();
        $userId = $user->id;

        [$singleScores, $multiScores] = self::calculateAnswerScores($answerIds);
        $finalScores = array_merge_recursive($singleScores, $multiScores);

        $assessment = Assessment::where('user_id', $userId)->latest()->first();
        if (!$assessment) {
            // handle case where no assessment exists
            return 'Assessment not found';
        }

        $message = self::updateAssessmentWithScores($assessment, $finalScores, $user,$assessmentFromApp);
        self::storeAssessmentDetails($answerIds, $assessment, $userId);

        return $message;
    }

    private static function calculateAnswerScores(array $answerIds): array
    {
        $flatIds = collect($answerIds)->flatten()->unique()->toArray();

        $answerCodes = AnswerCode::whereIn('answer_id', $flatIds)->get()->groupBy('answer_id');

        $singleScores = [];

        $multiScores = [];

        foreach ($answerIds as $answer) {

            if (is_array($answer)) {

                $i = 3;

                foreach ($answer as $ansId) {

                    $code = $answerCodes[$ansId]->first() ?? null;

                    if ($code) {

                        $number = (int)$code->number + $i;

                        $key = strtolower($code->code);

                        $multiScores[$key] = ($multiScores[$key] ?? 0) + $number;

                        $i--;
                    }
                }
            } else {

                if (!isset($answerCodes[$answer])) continue;

                foreach ($answerCodes[$answer] as $code) {

                    $singleScores[$code->code] = ($singleScores[$code->code] ?? 0) + $code->number;

                }
            }
        }

        return [$singleScores, $multiScores];
    }

    private static function updateAssessmentWithScores($assessment, array $scores, $user, bool $assessmentFromApp = false): string
    {
        $result = self::calculateUpdatedScores($assessment, $scores);

        $userGender = (int) $user->gender;
        $questionCount = Question::genderApplicable($userGender)
            ->active()
            ->rootQuestions()
            ->count();

        $pageData = self::calculateCurrentPages($assessment, $questionCount, $assessmentFromApp);

        $result = array_merge($result, $pageData['update_data']);
        $assessment->update($result);

        self::handleAssessmentEvents($assessment, $user, $pageData['current_page']);

        AssessmentColorCode::deleteAssessemntColorCodeData($assessment);
        AssessmentColorCode::createStylesCodeAndColor($assessment);
        AssessmentColorCode::createFeaturesCodeAndColor($assessment);

        if ($assessment->page == 0) {
            self::handleNewActionPlan($assessment);
        }

        return $pageData['message'];
    }

    /**
     * 1️⃣ Update scores
     */
    private static function calculateUpdatedScores($assessment, array $scores): array
    {
        $old = $assessment->toArray();
        $result = [];

        foreach ($scores as $code => $value) {
            $result[strtolower($code)] = ($old[strtolower($code)] ?? 0) + $value;
        }

        return $result;
    }

    /**
     * 2️⃣ Calculate current page numbers
     */
    private static function calculateCurrentPages($assessment, int $questionCount, bool $assessmentFromApp): array
    {
        if ($assessmentFromApp) {
            $appPage = $assessment->app_page + 1;
            $webPage = (int) ceil($appPage / 3);
            $currentPage = $appPage;

            if ($appPage > 1) {
                $webPage = (int) ceil(($appPage + 1) / 3);
            }
        } else {
            $currentPage = $assessment->web_page + 1;
            $webPage = $currentPage;
            $appPage = (($webPage - 1) * 3) + 3;
        }

        $totalPages = $assessmentFromApp ? $questionCount : ceil($questionCount / 3);

        $message = '';
        $updateData = [];

        if ($currentPage >= $totalPages) {
            $updateData['page'] = 0;
            $updateData['web_page'] = 0;
            $updateData['app_page'] = 0;
            $message = 'final_page';
        } else {
            $updateData['page'] = $webPage;
            $updateData['web_page'] = $webPage;
            $updateData['app_page'] = $appPage;
        }

        return [
            'current_page' => $currentPage,
            'update_data' => $updateData,
            'message' => $message,
        ];
    }

    /**
     * 3️⃣ Handle gamification, events and logging
     */
    private static function handleAssessmentEvents($assessment, $user, int $currentPage)
    {
        if ($currentPage == 0) {
            $cachedIp = Cache::get("user_ip_{$user->id}", null);
            $geoService = new GeoService();
            $location = $geoService->getLocationByIp($cachedIp);
            $assessment->update([
                'ip_address' => $cachedIp ?? '0.0.0.0',
                'city' => $location['city'],
                'country' => $location['country']
            ]);

            event(new SubmitAssessment($user->id, 0));
            self::triggerGamification($user);

            $logMessage = Assessment::where('user_id', $user->id)->count() == 1
                ? "Congratulations on finishing your first assessment! Remember to come back next season (90 days) to take it again for free."
                : "Congratulations on finishing your assessment!";

            ActivityLogger::addLog('Assessment Completed', $logMessage);

            self::handleDailyTipIfFinalPage($assessment, $user);
        } else {
            event(new SubmitAssessment($user->id, $currentPage + 1));
        }
    }

    /**
     * 4️⃣ Handle Action Plan & HotSpot insertion
     */
    private static function handleNewActionPlan($assessment)
    {
        ActionPlan::storeUserActionPlan($assessment);
        $data = Assessment::getAllRowGrid($assessment->id);
        if ($data) {
            $trendTracker = new HotSpotUser;
            $trendTracker->insertData($assessment->id, $data);
        }

        ActivityLogger::addLog('New Action Plan', "Your New 14 Days Action Plan");
    }


    private static function handleDailyTipIfFinalPage($assessment, $user): string
    {
        $tip = UserDailyTip::getLatestTip();

        if (!$tip) {
            $alchemy = Assessment::getAlchemy($assessment);
            $communication = Assessment::getEnergy($assessment);
            $color = AssessmentColorCode::getGreenCodes($assessment->id);

            $selected = array_filter([
                $color['code'] ?? null,
                $alchemy['code'] ?? null,
                $communication[0] ?? null
            ]);

            $randomCode = $selected[array_rand($selected)];
            $newTip = DailyTip::getSameCodeTips($randomCode);

            if ($newTip) {
                $latestTip = UserDailyTip::where('user_id', $user->id)
                    ->where('daily_tip_id', $newTip->id)
                    ->latest()
                    ->first();

                $alreadyExists = $latestTip && $latestTip->created_at >= Carbon::now()->subDays(365);

                if (!$alreadyExists) {

                    UserDailyTip::createUserDailyTip($user->id, $newTip->id, $assessment->id);
                    event(new NewDailyTip($user->id, 'new daily tip', 'Your New Daily Tip'));

                    ActivityLogger::addLog('new daily tip', "Your New Daily Tip");

                }
            }
        }

        return Assessment::where('user_id', $user->id)->count() === 1
            ? "Congratulations on finishing your first assessment! Remember to come back next season (90 days) to take it again for free."
            : "Congratulations on finishing your assessment!";
    }

    private static function storeAssessmentDetails(array $answerIds, $assessment, $userId): void
    {
        $flatIds = collect($answerIds)->flatten()->unique()->toArray();

        $answers = Answer::with('question')
            ->whereIn('id', $flatIds)
            ->orWhereIn('answer_id', $flatIds)
            ->get();

        $insertData = [];

        foreach ($answers as $ans) {
            if ($ans) {
                $insertData[] = [
                    'user_id'       => $userId,
                    'assessment_id' => $assessment->id,
                    'answer_id'     => $ans->id,
                    'question_id'   => $ans->question_id,
                    'answer'        => $ans->answer,
                    'question'      => optional($ans->question)->question,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
        }

        // Batch insert, ignores duplicates if DB has unique constraint
        AssessmentDetail::insertOrIgnore($insertData);
    }


    private static function triggerGamification($user): void
    {
        HaiChatHelpers::syncUserRecordWithHAi();
        HumanOpPoints::addPointsAfterCompleteAssessment($user);
        GamificationBadgesAchievement::addBadgeAfterCompleteAssessment($user->id);
    }
}
