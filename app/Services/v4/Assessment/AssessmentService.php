<?php

namespace App\Services\v4\Assessment;

use App\Enums\Admin\Admin;
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
use App\Models\v4\Client\Dashboard\ActionPlan;
use App\Models\v4\Client\Gamification\GamificationBadgesAchievement;
use App\Models\v4\Client\HumanOpPoints\HumanOpPoints;
use App\Models\Question;
use Carbon\Carbon;
use App\Services\GeoService;
use Illuminate\Support\Facades\Cache;
use App\Models\HotSpotUser;
use App\Services\GoHighLevelService;
use Illuminate\Support\Facades\Log;


class AssessmentService
{

    public static function submitAnswers(array $answerIds): string
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

        $message = self::updateAssessmentWithScores($assessment, $finalScores, $user);
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

    private static function updateAssessmentWithScores($assessment, array $scores, $user): string
    {
        $result = self::calculateUpdatedScores($assessment, $scores);

        $userGender = (int) $user->getRawOriginal('gender');

        $questionCount = self::getApplicableQuestionCount($userGender);

        [$page,$currentPage,$webPage,$appPage] = self::calculatePages($assessment,$questionCount);

        if ($currentPage >= $questionCount) {
            return self::handleFinalPage($assessment, $user, $result);
        } else {

            return self::handleIntermediatePage($assessment, $currentPage, $webPage, $appPage, $result);
        }
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
     * Get the total number of applicable questions for the user.
     */
    private static function getApplicableQuestionCount(int $gender): int
    {
        return Question::genderApplicable($gender)
            ->active()
            ->rootQuestions()
            ->count();
    }

    /**
     * Calculate the web and app pages based on current assessment state.
     */
    private static function calculatePages($assessment): array
    {


        $appPage = $assessment->app_page + 1;

        $webPage = (int) floor($appPage / 3);
        $currentPage = $appPage;
        $page = $webPage;

        return [$page, $currentPage, $webPage, $appPage];
    }

    /**
     * Handle the logic when the user is on the final page of the assessment.
     */
    private static function handleFinalPage($assessment, $user, array $result): string
    {
        $result['page'] = 0;
        $result['web_page'] = 0;
        $result['app_page'] = 0;

        $cachedIp = Cache::get("user_ip_{$user->id}", null);
        $geoService = new GeoService();
        $location = $geoService->getLocationByIp($cachedIp);

        $result['ip_address'] = $cachedIp ?? '0.0.0.0';
        $result['city'] = $location['city'];
        $result['country'] = $location['country'];

        $assessment->update($result);

        event(new SubmitAssessment($user->id, 0));

        self::triggerGamification($user);

        $message = self::handleDailyTipIfFinalPage($assessment, $user);

        $ghl = app(GoHighLevelService::class);

        $ghl->syncContactWithTags($user, Admin::ASSESSMENT_GIVEN);

        if (Assessment::where('user_id', $user->id)->count() == 1) {
            ActivityLogger::addLog(
                'Assessment Completed',
                "Congratulations on finishing your first assessment! Remember to come back next season (90 days) to take it again for free."
            );
        } else {
            ActivityLogger::addLog('Assessment Completed', "Congratulations on finishing your assessment!");
        }

        self::updateAssessmentColorCodes($assessment);
        self::initializeActionPlan($assessment);

        return $message;
    }

    /**
     * Handle the logic for intermediate (non-final) pages.
     */
    private static function handleIntermediatePage($assessment, int $currentPage, int $webPage, int $appPage, array $result): string
    {

        $result['page'] = $webPage;
        $result['web_page'] = $currentPage;
        $result['app_page'] = $appPage;

        $assessment->update($result);

        event(new SubmitAssessment($assessment->user_id, $appPage + 1));

        self::updateAssessmentColorCodes($assessment);

        if ($assessment->page == 0) {
            self::initializeActionPlan($assessment);
        }

        return '';
    }

    /**
     * Update color codes for the assessment.
     */
    private static function updateAssessmentColorCodes($assessment): void
    {
        AssessmentColorCode::deleteAssessemntColorCodeData($assessment);
        AssessmentColorCode::createStylesCodeAndColor($assessment);
        AssessmentColorCode::createFeaturesCodeAndColor($assessment);
    }

    /**
     * Initialize action plan for the assessment.
     */
    private static function initializeActionPlan($assessment): void
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

        $flatIds = collect($answerIds)
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        if (empty($flatIds)) {
            return;
        }

        $answers = Answer::with('question')
            ->where(function ($query) use ($flatIds) {
                $query->whereIn('id', $flatIds)
                    ->orWhereIn('answer_id', $flatIds);
            })
            ->get()
            ->sortBy(function ($item) use ($flatIds) {
                $index = array_search($item->id, $flatIds);
                if ($index !== false) return $index;

                $index = array_search($item->answer_id, $flatIds);
                if ($index !== false) return $index;

                return count($flatIds); // unlisted items last
            })
            ->values();

        if ($answers->isEmpty()) {
            return;
        }

        $now = now(); // agar timestamps chahiye

        // Prepare insert data
        $insertData = $answers->map(function ($ans) use ($userId, $assessment, $now) {
            return [
                'user_id'       => $userId,
                'assessment_id' => $assessment->id,
                'answer_id'     => $ans->id,
                'question_id'   => $ans->question_id,
                'answer'        => $ans->answer,
                'question'      => optional($ans->question)->question,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        })->toArray();

        AssessmentDetail::insertOrIgnore($insertData);
    }


    private static function triggerGamification($user): void
    {
        HaiChatHelpers::syncUserRecordWithHAi();
        HumanOpPoints::addPointsAfterCompleteAssessment($user);
        GamificationBadgesAchievement::addBadgeAfterCompleteAssessment($user->id);
    }
}
