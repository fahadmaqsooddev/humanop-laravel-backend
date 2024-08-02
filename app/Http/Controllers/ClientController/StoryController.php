<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\Controller;
use App\Models\Client\Story\Story;
use App\Models\User;
use Illuminate\Http\Request;

class StoryController extends Controller
{

    public function stories(Request $request){

        try {

            if ($request->has('id') && !empty($request->input('id'))){

                $user = User::user($request->input('id'));

                if ($user){

                    $stories = Story::userStories($request->input('id'));

                    return view('client-dashboard.story.story', compact('stories', 'user'));
                }

            }

            toastr()->error('Something went wrong');

            return redirect('client/human-network');

        }catch (\Exception $exception){

            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
