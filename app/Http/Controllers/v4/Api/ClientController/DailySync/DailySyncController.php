<?php

namespace App\Http\Controllers\v4\Api\ClientController\DailySync;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\v4\Api\Client\DailySync\SubmitQuestionRequest;
use App\Models\v4\Admin\DailySync\DailySyncQuestion;
use App\Models\v4\Client\DailySync\DailySyncResponse;
use App\Models\v4\Client\DailySync\DailySyncSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailySyncController extends Controller
{
    const QUESTIONS_LIMIT = 3;

    const HOURS_UNTIL_NEXT_SESSION = 24;

    const SESSION_COMPLETED_AT = 1;

    const SESSION_NOT_COMPLETED_AT = 0;

    public function archive()
    {
        $user = Helpers::getUser();


        $sessions = DailySyncSession::where('user_id', $user->id)
            ->whereHas('responses', fn($q) => $q->whereNotNull('response_text')->where('response_text', '!=', ''))
            ->with(['responses' => fn($q) => $q->whereNotNull('response_text')
                ->where('response_text', '!=', '')
                ->orderBy('created_at', 'desc')])
            ->orderBy('created_at', 'desc')
            ->get();

        $responses = $sessions->flatMap(function ($session) {

            return $session->responses->map(function ($response) {

                return [
                    'question_text' => $response->question_text,
                    'response_text' => $response->response_text,
                    'created_at' => $response->created_at,
                ];

            });

        });

        $archive = $responses
            ->groupBy(function ($item) {

                return Carbon::parse($item['created_at'])->format('Y-m-d');

            })
            ->map(function ($items, $date) {

                return [
                    'date' => $date,
                    'responses' => $items->map(function ($item) {
                        return [
                            'question' => $item['question_text'],
                            'response' => $item['response_text'],
                        ];
                    })->values()

                ];

            })
            ->values()
            ->all();

        return Helpers::successResponse('Daily sync archive', $archive);

    }

    public function status()
    {
        $user = Helpers::getUser();

        $premiumRequired = $user->plan_name === Admin::PREMIUM_PLAN_NAME;

        $latestSession = DailySyncSession::where('user_id', $user->id)->latest('created_at')->first();

        $completedToday = false;

        $submitQuestion = 0;

        if ($latestSession) {

            $completedToday = $latestSession->is_completed && $latestSession->updated_at->isToday();

            $submitQuestion = DailySyncResponse::submitQuestionCount($latestSession->id);
        }

        return Helpers::successResponse('Daily sync status', [
            'premium_required' => $premiumRequired,
            'completed_today' => $completedToday,
            'submit_question' => $submitQuestion,
        ]);

    }

    public function submitResponse(SubmitQuestionRequest $request)
    {

        $user = Helpers::getUser();

        $session = DailySyncSession::getSingleSession($user->id, $request['session_id']);

        if (!$session) {
            return Helpers::validationResponse('Invalid session.');
        }

        $dailySyncResponse = DailySyncResponse::getSingleSession($session->id, $request['question_id']);

        if (!$dailySyncResponse) {

            return Helpers::validationResponse('Invalid question for this session.');

        }

        $dailySyncResponse->update(['response_text' => $request['response']]);

        if (DailySyncResponse::submitQuestionCount($session->id) == self::QUESTIONS_LIMIT) {

            $session->update(['is_completed' => self::SESSION_COMPLETED_AT]);

        }

        $payload = [
            'session_id' => $session->id,
            'question_id' => (int)$request['question_id'],
            'response_text' => $dailySyncResponse->response_text,
            'session_completed' => $session->fresh()->is_completed == true,
        ];

        return Helpers::successResponse('Response saved', $payload);

    }

    public function dailySyncStart()
    {
        $user = Helpers::getUser();

        return DB::transaction(function () use ($user) {

            $latestSession = DailySyncSession::where('user_id', $user->id)
                ->lockForUpdate()
                ->latest()
                ->first();

            if ($latestSession && $latestSession->is_completed == self::SESSION_NOT_COMPLETED_AT) {

                $responses = $latestSession->responses()->orderBy('id')->get();

                $questionsPayload = [];

                $step = 1;

                foreach ($responses as $response) {

                    $questionsPayload[] = [
                        'step' => $step,
                        'question_id' => $response->question_id,
                        'question' => $response->question_text,
                    ];

                    $step++;

                }

                $response = [
                    'session_id' => $latestSession->id,
                    'questions' => $questionsPayload,
                ];

                return Helpers::successResponse('Existing incomplete session', $response);

            }

            if ($latestSession && $latestSession->is_completed == self::SESSION_COMPLETED_AT) {

                $nextAllowedAt = Carbon::parse($latestSession->updated_at)->addHours(self::HOURS_UNTIL_NEXT_SESSION);

                if (Carbon::now()->lt($nextAllowedAt)) {

                    return Helpers::validationResponse('You can start a new session after 24 hours from your last completion.');

                }

            }

            $activeQuestions = DailySyncQuestion::getActiveQuestions();

            if ($activeQuestions->count() < self::QUESTIONS_LIMIT) {

                return Helpers::validationResponse('Not enough questions available. At least ' . self::QUESTIONS_LIMIT . ' active questions are required.');

            }

            $selectedQuestions = $activeQuestions->random(self::QUESTIONS_LIMIT);

            $session = DailySyncSession::createSessions($user);

            $questionsPayload = [];

            $step = 1;

            foreach ($selectedQuestions as $question) {

                DailySyncResponse::createResponse($session, $question);

                $questionsPayload[] = [
                    'step' => $step,
                    'question_id' => $question->id,
                    'question' => $question->question_text,
                ];
                $step++;

            }

            $response = [
                'session_id' => $session->id,
                'questions' => $questionsPayload,
            ];

            return Helpers::successResponse('Daily sync session started', $response);

        });
    }

}
