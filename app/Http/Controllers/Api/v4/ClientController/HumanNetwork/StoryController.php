<?php

namespace App\Http\Controllers\Api\ClientController\v4\HumanNetwork;

use App\Helpers\v4\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v4\Client\Story\CreateStoryRequest;
use App\Http\Requests\Api\v4\Client\Story\DeleteStoryRequest;
use App\Http\Requests\Api\v4\Client\Story\StoryViewedRequest;
use App\Http\Requests\Api\v4\Client\Story\UserStoryRequest;
use App\Models\v4\Client\Story\Story;
use App\Models\v4\Client\StoryView\StoryView;
use App\Models\v4\Upload\Upload;
use App\Models\v4\User;
use Illuminate\Support\Facades\DB;

class StoryController extends Controller
{

    protected $story;

    public function __construct(Story $story)
    {
        $this->middleware('auth:api');

        $this->story = $story;
    }

    public function storyUsers(){

        try {

            $story_users = User::storyUsers();

            return Helpers::successResponse('Story users', $story_users);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function createStory(CreateStoryRequest $request){

        DB::beginTransaction();
        try {

            $dataArray = $request->only($this->story->getFillable());

            if ($request->hasFile('image') && !empty($request->file('image'))){

                $upload_id = Upload::uploadFile($request->file('image'), 200, 200, 'base64Image', 'png',true);

                $dataArray['upload_id'] = $upload_id;

                $dataArray['file_type'] = 'image';

                Story::addStory($dataArray);

                DB::commit();

                return Helpers::successResponse('Story uploaded');

            }

        }catch (\Exception $exception){

            DB::rollBack();
            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function deleteStory(DeleteStoryRequest $request){

        try {

            Story::deleteStory($request->input('id'));

            return Helpers::successResponse('Story deleted');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function userStories(UserStoryRequest $request){

        try {

            $stories = User::userStories($request->input('id'));

            return Helpers::successResponse('User stories', $stories);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function storyViewed(StoryViewedRequest $request){ // this form request is used in storyViews function

        try {

            StoryView::addStoryView($request->input('id'));

            return Helpers::successResponse('Story viewed');

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function storyViews(StoryViewedRequest $request){

        try {

            $story_views = StoryView::storyViews($request->input('id'));

            return Helpers::successResponse('Story viewed', $story_views);

        }catch (\Exception $exception){

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }
}
