<?php

namespace App\Http\Controllers\Api\ClientController\ChatAi;

use App\Helpers\Assessments\AssessmentHelper;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\ChatAi\AskQuestionRequest;
use App\Http\Requests\Api\Client\ChatAi\LikeDisLikeAiReplyRequest;
use App\Models\Assessment;
use App\Models\HAIChai\HaiChat;

class ChatAiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function aiChat(){

        try {

            $ai_chat = HaiChat::getChat();

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

            HaiChat::likeDisLikeAiReply($request);

            return Helpers::successResponse('Chat successfully ' . $request->type . 'd');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }
}
