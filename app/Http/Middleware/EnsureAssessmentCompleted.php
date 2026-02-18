<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\v4\Assessment;
use App\Helpers\v4\Helpers;

class EnsureAssessmentCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = Helpers::getUser();

        if (!$user) {
            return Helpers::unauthResponse('Unauthorized user.');
        }

        $firstAssessment = Assessment::where('user_id', $user->id)
            ->orderBy('id', 'asc')
            ->first();


        if (!$firstAssessment) {
            return Helpers::notFoundResponse('Assessment not found');
        }

        //dd($firstAssessment);

        $isCompleted =
            $firstAssessment->web_page == 0 &&
            $firstAssessment->assessment_page == 0 &&
            $firstAssessment->app_page == 0;

        if (!$isCompleted) {
            return Helpers::forbiddenResponse(
                'Please complete your initial assessment before continuing.'
            );
        }

        return $next($request);
    }
}
