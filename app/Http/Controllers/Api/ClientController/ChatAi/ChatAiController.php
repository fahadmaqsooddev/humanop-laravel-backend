<?php

namespace App\Http\Controllers\Api\ClientController\ChatAi;

use App\Helpers\Assessments\AssessmentHelper;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ChatAi\AskQuestionRequest;
use App\Http\Requests\Api\Client\ChatAi\LikeDisLikeAiReplyRequest;
use App\Http\Requests\Api\Client\ChatAi\StoreClientQueryRequest;
use App\Models\Assessment;
use App\Models\HAIChai\Chatbot;
use App\Models\HAIChai\ChatbotKeyword;
use App\Models\HAIChai\ClientQuery;
use App\Models\HAIChai\HaiChat;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatConversation;
use App\Models\HAIChai\QueryAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

//            $assessments = AssessmentHelper::getAssessments();
//
//            $assessmentDetails = Assessment::getAssessment();
//
//            $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/llm-data', ['question' => $request->input('question'), 'user_id' => Helpers::getUser()->id, 'assessment_ids' => $assessments, 'assessment_details' => $assessmentDetails, 'is_repeat' => $request->input('is_repeat_answer')]);

            $client = \OpenAI::client("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");

            $chatBot = Chatbot::chatBotFromUserPlan();

            $is_restricted_word = ChatbotKeyword::checkChatBotKeywords($chatBot->id ?? null, $request->input('question'));

            if (!$is_restricted_word){

                $knowledge = HaiChatActiveEmbedding::activeEmbeddings($chatBot->id ?? null);

                $chunks = HaiChatHelpers::findRelevantChunks($request->input('question'), $knowledge, $chatBot->chunks ?? 1);

                $chunks = array_column($chunks,'content');

                $detail_question = $request->input('is_repeat_answer') === 1 ? 'Tell me in detail about ' : "";

                $messages = [
                    [
                        'role' => 'system',
                        'content' => "Ensure responses follow these prompts: ". ($chatBot->prompt ?? null) ."Use context to provide accurate answers. Ensure responses follow these restrictions: ". ($chatBot->restriction ?? null),
                    ],
                    [
                        'role' => 'assistant',
                        'content' => "Here is the related context: ". implode('\n',$chunks) .".",
                    ],
                    [
                        'role' => 'user',
                        'content' => $detail_question . $request->input('question'),
                    ]
                ];

                $reply = $client->chat()->create([
                    'model' => 'ft:gpt-4o-mini-2024-07-18:personal::AdxDqOYu',
                    'messages' => $messages,
                    'max_tokens' => $chatBot->max_tokens ?? 250,
                    'temperature' => $chatBot->temperature ?? 0.2,
                ]);

                if (isset($reply->toArray()['choices'][0]['message']['content'])){

                    HaiChat::createChat($request->input('question'), $reply->toArray()['choices'][0]['message']['content'], null);

                    $aiReply = $reply->toArray()['choices'][0]['message']['content'];

                }

            }else{

                $aiReply = $is_restricted_word ?? 'Your query contains restricted keywords. So, I am unable to response you about these.';

//                $conversationsArray = $this->conversations->toArray();

//                $restrictedResponse = [
//                    'reply' => ,
//                    'message' => $request->input('question'),
//                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
//                ];

//                if (count($conversationsArray) > 0){
//
//                    $final = array_merge($conversationsArray,[$restrictedResponse]);
//
//                    $this->conversations = collect($final);
//
//                }else{
//
//                    $this->conversations = collect([$restrictedResponse]);
//                }
            }

            return Helpers::successResponse('Answer of asked question', $aiReply);

//            HaiChat::createChat($request->input('question'), $aiReply);

//            return Helpers::successResponse('Answer of asked question', $aiReply);

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
