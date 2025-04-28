<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Helpers\OpenRouterHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ChatAi\AskQuestionRequest;
use App\Models\Assessment;
use App\Models\HAIChai\BrainCluster;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatbotKeyword;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\HaiChat;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\HAIChai\LlmModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class B2BHaiController extends Controller
{

    public function __construct()
    {
        $this->auth = Auth::guard('api');
    }

    public function askQuestion(AskQuestionRequest $request){

        try {

            $setting = HaiChatSetting::where('maestro_app', 1)->first();

            $chat_bot = Chatbot::whereId($setting['chat_bot_id'])->first();

//            $setting = HaiChatSetting::getHaiChatSetting($chat_bot['id']);

            $prompts = ChatPrompt::where('name',$chat_bot->name)->first();

            $selectedModel = LlmModel::getSelectedModel($setting['model_type']);

            $activeChatAndEmbedding = BrainCluster::connectedClusterEmbeddingIds($chat_bot['id']);

            $is_restricted_word = ChatbotKeyword::checkChatBotKeywordsForApi($chat_bot->id ?? null, $request->input('question'));

            if (!$is_restricted_word){

                $email = Helpers::findEmailFromString($request->input('question'));

                if ($email){

                    $user_id = User::checkUserEmailInB2B($email);

                    if (!$user_id){

                        $reply = [
                            "<p>You don't have access to this user's data.</p>",
                            0
                        ];

                        return Helpers::successResponse('Answer of asked question', $reply);
                    }

                    $user_grid = Assessment::getAssessmentFromUserId($user_id);

                }else{

                    $user_grid = Assessment::getAssessmentFromUserId(Helpers::getUser()['id'] ?? null);
                }

                $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

                $user_name = Helpers::getUser()->first_name . ' ' . Helpers::getUser()->last_name;

                $body = ["query" => $request->input('question'), 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $chat_bot['name'], 'total_chunks' => $setting['chunk'], 'gpt_model' => 'sonnet','user_grid' => $user_grid ?? [], 'dislike' => $request->input('is_repeat_answer'), 'loc' => $subFolder, 'user_name' => $user_name, 'user_id' => (int)Helpers::getUser()->id];

                $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'b2b-llm-model', $body);

                $openRouterResponse = OpenRouterHelper::callOpenRouterApi($request->input('question'), $setting, $aiReply, $selectedModel['model_value'] ?? null, $prompts['prompt'] ?? null);

                $reply = null;

                foreach ($openRouterResponse['choices'] as $choice)
                {

                    HaiChat::createChat($request->input("question"), $choice['message']['content'], null, $request->input("is_repeat_answer"));

                    $reply = [
                        $choice['message']['content'] ?? "",
                        0
                    ];
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
}
