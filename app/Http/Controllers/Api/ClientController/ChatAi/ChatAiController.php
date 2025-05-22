<?php

namespace App\Http\Controllers\Api\ClientController\ChatAi;

use App\Helpers\Assessments\AssessmentHelper;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Helpers\OpenRouterHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ChatAi\AskQuestionRequest;
use App\Http\Requests\Api\Client\ChatAi\LikeDisLikeAiReplyRequest;
use App\Http\Requests\Api\Client\ChatAi\StoreClientQueryRequest;
use App\Jobs\SummarizeChatHistory;
use App\Models\Admin\DailyTip\UserDailyTip;
use App\Models\Assessment;
use App\Models\Client\Dashboard\ActionPlan;
use App\Models\Client\Point\Point;
use App\Models\Client\Point\PointLog;
use App\Models\HAIChai\BrainCluster;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatbotKeyword;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\ClientQuery;
use App\Models\HAIChai\HaiChat;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatConversation;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\HAIChai\LlmModel;
use App\Models\HAIChai\PublishedChatBot;
use App\Models\HAIChai\QueryAnswer;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatAiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function aiChat(Request $request){

        try {

            $ai_chat = HaiChat::getChat($request->input('days_old'), $request->input('is_latest'));

            return Helpers::successResponse("Your today's chat with AI", $ai_chat);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function askQuestion(AskQuestionRequest $request){

        try {

            $per_credit_token = config('chat.hai_one_credit_token_count');

            $user_credits = (float)(Point::where('user_id', Helpers::getUser()->id)->first()->point ?? 0) * $per_credit_token;

            if ($user_credits <= 20){

                return Helpers::upgradePackageResponse("Upgrade your account.");
            }

            $chat_bot = PublishedChatBot::first();

            if (!$chat_bot){

                return Helpers::validationResponse('There is not any connected brain.');
            }

            $is_restricted_word = ChatbotKeyword::checkPublishedChatBotKeywords($chat_bot['restricted_keywords'] ?? [], $request->input('question'));

            if (!$is_restricted_word){

                if ($chat_bot && $chat_bot['model_type'] === 5){

                    $user_grid = Assessment::getAssessmentFromUserId(Helpers::getUser()['id'] ?? null);

                    $user = User::userDataForHAi(Helpers::getUser()->id);

                    $user_name = $user['first_name'];

                    $user_intentions = $user?->userIntentions?->pluck('description')->toArray();

//                $interval_life = User::userIntervalOfLife($user['date_of_birth']);

                    $body = ["query" => $request->input('question'), 'temperature' => $chat_bot['temperature'], 'max_tokens' => $chat_bot['max_token'], 'file_name' => $chat_bot['embedding_ids'], 'prompt_folder' => $chat_bot['name'], 'total_chunks' => $chat_bot['chunk'], 'gpt_model' => 'sonnet','user_grid' => $user_grid ?? [], 'dislike' => $request->input('is_repeat_answer'), 'loc' => 'dev', 'user_name' => $user_name, 'user_id' => (int)Helpers::getUser()->id, 'user_intentions' => $user_intentions];

                    $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'temp-llm-model', $body);

                    Log::info(['ai Reply' => $aiReply]);

                    $llm_prompt = OpenRouterHelper::addUserDetailsIntoPrompt(Helpers::getUser()->id, $aiReply['combined_output']);

                    $final_persona = OpenRouterHelper::createFinalPersona($chat_bot['prompt']);

                    $authorization = \request()->header('Authorization');

                    $queryArray = [
                        'headers' => ['Authorization' => $authorization]
                    ];

                    $client = new Client(['http_errors' => false, 'timeout' => 180]);

                    $route = "ec2-34-233-15-190.compute-1.amazonaws.com/bedrock/bedrock.php?persona=" . $final_persona . "&prompt=". $llm_prompt ."&query=" . $request->input('question');

                    $response = $client->request("get", $route, $queryArray);

                    if ($response->getStatusCode() === 200){

                        $reply = $response->getBody()->getContents();

                        HaiChat::createChat($request->input("question"), $reply, null, $request->input("is_repeat_answer"));

                        $reply = [
                            $reply ?? "",
                            0
                        ];

                    }else{

                        return Helpers::validationResponse('Something went wrong');
                    }

                }else{

                    $user_id = Helpers::getUser()->id;

                    $user_grid = Assessment::getLatestAssessment($user_id);

                    $user = User::userDataForHAi($user_id);

                    $user_name = $user['first_name'];

//                    $user_intentions = $user?->userIntentions?->pluck('description')->toArray();

//                    $interval_life = User::userIntervalOfLife($user['date_of_birth']);

                    $optimizationPlan = $user_grid ? ActionPlan::getUserActionPlan($user_id) : null;

                    $coreState = $user_grid ? Assessment::getCoreState($user_grid, $user['date_of_birth']) : null;

                    $userTrait = Assessment::UserTraits($user_id);

                    $userDailyTip = UserDailyTip::where('user_id', $user_id)->with('dailyTip')->latest()->first();

                    $result = [
                        'name' => $user_name,
                        'email' => $user['email'] ?? '',
                        'phone' => $user['phone'] ?? '',
                        'date_of_birth' => $user['date_of_birth'] ?? '',
                        'gender' => $user['gender'] ?? '',
                        'timezone' => $user['timezone'] ?? '',
                        'optimization_plan' => $optimizationPlan,
                        'core_state' => $coreState,
                        'daily_tip' => $userDailyTip,
                    ];

                    $body = ['user_query' => $request->input('question'),'user_detail' => $result ?? [] ,'base_data' => ($chat_bot['prompt'] ?? null), 'restriction_data' => ($chat_bot['restriction'] ?? null), 'formatted_docs' => $chat_bot['embedding_ids'],
                        'temperature' => $chat_bot['temperature'], 'max_tokens' => $chat_bot['max_tokens'], 'chunks' => $chat_bot['chunks'], 'user_id' => Helpers::getUser()->id,'user_trait' => $userTrait ?? []];

                    $response = GuzzleHelpers::sendRequestFromGuzzleForNewHai('post', 'persona/api/chat', $body);

                    if (isset($response['response'])){

                        HaiChat::createChat($request->input("question"), $response['response'], null, $request->input("is_repeat_answer"));

                        PointLog::updateHaiCreditLogs($per_credit_token,(int)$user_credits, (int)100);

                        $reply = [
                            $response['response'],
                            0
                        ];

                    }else{

                        return Helpers::validationResponse('Something went wrong.');
                    }

                }

            }else{

                $reply = [
                    $is_restricted_word ?? 'Your query contains restricted keywords. So, I am unable to response you about these.',
                    3,
                ];
            }

            return Helpers::successResponse('Answer of asked question', $reply);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function likeDislikeAiReply(LikeDisLikeAiReplyRequest $request){

        try {

            HaiChat::likeDisLikeAiReply($request, $request->input('type'));

            return Helpers::successResponse('Chat successfully ' . $request->type . 'd');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function clientQuery(StoreClientQueryRequest $request){

        try {

            ClientQuery::createQuery(Helpers::getUser()->id, $request->input('query'));

            return Helpers::successResponse('Query submitted');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function clientQueryAnswer(){

        try {

            $client_answer = QueryAnswer::userQueryAnswer();

            return Helpers::successResponse('Query answer', $client_answer);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
