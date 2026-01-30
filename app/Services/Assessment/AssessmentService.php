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
        $old = $assessment->toArray();
        $result = [];

        foreach ($scores as $code => $value) {
            $result[strtolower($code)] = ($old[strtolower($code)] ?? 0) + $value;
        }

        $userGender = (int) $user->gender;

        $questionCount = Question::genderApplicable($userGender)
            ->active()
            ->rootQuestions()
            ->count();

        $totalPages = $assessmentFromApp
            ? $questionCount
            : ceil($questionCount / 3);


        /**
         * 1️⃣ Calculate CURRENT QUESTION NUMBER
         */
        if ($assessmentFromApp) {

            $currentPage = ($assessment->web_page * 3) + 1;

            $appPage = $assessment->app_page + 1;

            if ($appPage == 1) {
                $webPage = 1;
            } else {

                $webPage = (int) ceil(($appPage + 1) / 3);
            }

        } else {
            // web: 1 page = 3 questions
            $currentPage = $assessment->web_page + 1;
            $webPage = $assessment->web_page + 1;
            $appPage =  1 + (($webPage - 1) * 3) + 3;

        }

        $message = '';

        Log::info([
           'Current Page' => $currentPage,
           'Total Page' => $totalPages,
        ]);
        if ($currentPage >= $totalPages) {
            $result['page'] = 0;
            $result['app_page'] = 0;
            $result['web_page'] = 0;
            $cachedIp = Cache::get("user_ip_{$user->id}", null); // null if not set
            $geoService = new GeoService();
            $location = $geoService->getLocationByIp($cachedIp);
            $result['ip_address'] = $cachedIp ?? '0.0.0.0'; // fallback if cache empty
            $result['city'] = $location['city'];
            $result['country'] = $location['country'];
            $assessment->update($result);
            event(new SubmitAssessment($user->id, 0));
            $message = self::handleDailyTipIfFinalPage($assessment, $user);
            self::triggerGamification($user);

            if (Assessment::where('user_id', $user->id)->count() == 1) {

                ActivityLogger::addLog('Assessment Completed', "Congratulations on finishing your first assessment! Remember to come back next season (90 days) to take it again for free.");

            } else {

                ActivityLogger::addLog('Assessment Completed', "Congratulations on finishing your assessment!");

            }

        } else {

            Log::info([
                'App Page' => $appPage,
                'Web Page' => $webPage,
            ]);
            $result['app_page'] = $appPage;
            $result['web_page'] = $webPage;

            $result['page'] = $webPage;

            $assessment->update($result);
            event(new SubmitAssessment($user->id, $currentPage + 1));
        }

        AssessmentColorCode::deleteAssessemntColorCodeData($assessment);
        AssessmentColorCode::createStylesCodeAndColor($assessment);
        AssessmentColorCode::createFeaturesCodeAndColor($assessment);



        if ($assessment->page == 0) {

            ActionPlan::storeUserActionPlan($assessment);
            $data=Assessment::getAllRowGrid($assessment->id);
            if($data){
                $trendtracker=new HotSpotUser;
                $trendtracker->insertData($assessment->id,$data);
            }
            ActivityLogger::addLog('New Action Plan', "Your New 14 Days Action Plan");
        }

        return $message;
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

        $answers = Answer::with('question')->whereIn('id', $flatIds)->orWhereIn('answer_id', $flatIds)->get()->keyBy('id');

        foreach ($answerIds as $answer) {

            $data = ['user_id' => $userId, 'assessment_id' => $assessment->id];

            $ids = is_array($answer) ? $answer : [$answer];

            foreach ($ids as $id) {

                $ans = $answers[$id] ?? null;

                if ($ans) {
                    $data = [
                        'user_id'     => $userId,
                        'assessment_id' => $assessment->id,
                        'answer_id'   => $ans->id,
                        'question_id' => $ans->question_id,
                        'answer'      => $ans->answer,
                        'question'    => optional($ans->question)->question,
                    ];

                    // 🔹 Check if record already exists
                    $exists = AssessmentDetail::where('user_id', $userId)
                        ->where('assessment_id', $assessment->id)
                        ->where('question_id', $ans->question_id)
                        ->where('answer_id',$ans->id)
                        ->exists();

                    if (!$exists) {
                        AssessmentDetail::createAssessmentDetail($data);
                    }
                }
            }
        }
    }

    private static function triggerGamification($user): void
    {
        HaiChatHelpers::syncUserRecordWithHAi();
        HumanOpPoints::addPointsAfterCompleteAssessment($user);
        GamificationBadgesAchievement::addBadgeAfterCompleteAssessment($user->id);
    }
}
