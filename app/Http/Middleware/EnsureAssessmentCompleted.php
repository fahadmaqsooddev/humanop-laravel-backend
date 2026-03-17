<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Helpers\Helpers;
class EnsureAssessmentCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Helpers::getUser();

        if (!$user) {
            return Helpers::unauthResponse('Unauthorized');
        }

        $assessment = Assessment::getFirstAssessment($user->id);

        if (!$assessment) {
            return Helpers::notFoundResponse('Assessment Not Found');
        }

        if (!Assessment::isAssessmentComplete($assessment)) {
            return Helpers::forbiddenResponse('Please complete your assessment first');
        }

        return $next($request);
    }
}
