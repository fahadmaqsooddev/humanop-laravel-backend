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
use App\Models\Assessment;
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

            $chat_bot = Chatbot::where('is_published', 1)->first();

            $setting = HaiChatSetting::getHaiChatSetting($chat_bot['id']);

            $prompts = ChatPrompt::where('name',$chat_bot->name)->first();

            $selectedModel = LlmModel::getSelectedModel($setting['model_type']);

            $activeChatAndEmbedding = BrainCluster::connectedClusterEmbeddingIds($chat_bot['id']);

            $is_restricted_word = ChatbotKeyword::checkChatBotKeywordsForApi($chat_bot->id ?? null, $request->input('question'));

            if (!$is_restricted_word){

//                $assessments = AssessmentHelper::getAssessments();
//
//                $assessmentDetails = Assessment::getAssessment();

//                $body = ['question' => $request->input('question'),
//                    'user_id' => Helpers::getUser()->id,
//                    'assessment_ids' => $assessments,
//                    'assessment_details' => $assessmentDetails,
//                    'is_repeat' => $request->input('is_repeat_answer'),
//                    'publish_model' => ($chat_bot->publish_path ?? null)];
//
//                $app_env = env('APP_ENV');
//                $url = $app_env === 'staging' ? 'http://54.227.7.149:8000/publish_llm-data' : 'http://54.227.7.149:8000/publish_llm-data';

//                $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', $url, $body);

                $user_grid = Assessment::getAssessmentFromUserId(Helpers::getUser()['id'] ?? null);

                $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

                $user = User::userDataForHAi(Helpers::getUser()->id);

                $user_name = $user['first_name'] . ' ' . $user['last_name'];

//                $user_intentions = $user?->userIntentions?->pluck('description')->toArray();

                $body = ["query" => $request->input('question'), 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $chat_bot['name'], 'total_chunks' => $setting['chunk'], 'gpt_model' => 'sonnet','user_grid' => $user_grid ?? [], 'dislike' => $request->input('is_repeat_answer'), 'loc' => $subFolder, 'user_name' => $user_name, 'user_id' => (int)Helpers::getUser()->id];

                if ($setting && $setting['model_type'] === 5){

                    $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'temp-llm-model', $body);

                    Log::info(['ai Reply' => $aiReply]);

                    $llm_prompt = OpenRouterHelper::addUserDetailsIntoPrompt(Helpers::getUser()->id, $aiReply['combined_output']);

                    $final_persona = OpenRouterHelper::createFinalPersona($prompts['prompt']);

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

                        session()->flash("error", "Try again.");
                    }

                }else{

                    $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'llm-model', $body);

                    $llm_prompt = OpenRouterHelper::addUserDetailsIntoPrompt(Helpers::getUser()->id, $aiReply['prompt']);

                    $final_persona = OpenRouterHelper::createFinalPersona($prompts['prompt'] ?? "");

                    [$userMessage, $assistantMessage] = HaiChat::userLastMessage();

                    $openRouterResponse = OpenRouterHelper::callOpenRouterApi($request->input('question'), $setting, $llm_prompt, $selectedModel['model_value'], $final_persona,$userMessage, $assistantMessage);

                    $reply = null;

                    foreach ($openRouterResponse['choices'] as $choice)
                    {

                        HaiChat::createChat($request->input("question"), $choice['message']['content'], null, $request->input("is_repeat_answer"));

                        $reply = [
                            $choice['message']['content'] ?? "",
                            0
                        ];
                    }

                }

                SummarizeChatHistory::dispatch(Helpers::getUser()->id);

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
