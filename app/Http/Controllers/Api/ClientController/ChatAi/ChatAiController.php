<?php

namespace App\Http\Controllers\Api\ClientController\ChatAi;

use App\Helpers\Assessments\AssessmentHelper;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ChatAi\AskQuestionRequest;
use App\Http\Requests\Api\Client\ChatAi\LikeDisLikeAiReplyRequest;
use App\Http\Requests\Api\Client\ChatAi\StoreClientQueryRequest;
use App\Models\Assessment;
use App\Models\HAIChai\ClientQuery;
use App\Models\HAIChai\HaiChat;
use App\Models\HAIChai\QueryAnswer;
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

            $assessments = AssessmentHelper::getAssessments();

            $assessmentDetails = Assessment::getAssessment();

            $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/llm-data', ['question' => $request->input('question'), 'user_id' => Helpers::getUser()->id, 'assessment_ids' => $assessments, 'assessment_details' => $assessmentDetails, 'is_repeat' => $request->input('is_repeat_answer')]);

            HaiChat::createChat($request->input('question'), $aiReply);

            return Helpers::successResponse('Answer of asked question', $aiReply);

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
