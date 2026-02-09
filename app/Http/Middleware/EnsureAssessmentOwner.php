<?php

namespace App\Http\Middleware;

use App\Helpers\Helpers;
use Closure;
use Illuminate\Http\Request;
use App\Models\Assessment;

class EnsureAssessmentOwner
{
    /**
     * Handle an incoming request.
     */

    public function handle(Request $request, Closure $next)
    {
        $assessmentId = $request->input('assessment_id');

        if (!$assessmentId) {
            return $next($request);
        }

        $user = Helpers::getUser();

        if (!$user) {
            return Helpers::forbiddenResponse('Unauthorized user');
        }

        $assessment = Assessment::singleAssessmentFromId($assessmentId);

        if (empty($assessment)) {
            return Helpers::validationResponse('Assessment Record Not Found');
        }

        if ($assessment->user_id !== $user->id) {
            return Helpers::forbiddenResponse('Unauthorized assessment access');
        }

        return $next($request);
    }

}
