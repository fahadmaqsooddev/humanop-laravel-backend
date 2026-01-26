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
            return Helpers::validationResponse('assessment_id is required');
        }

        // Check assessment exists
        $assessment = Assessment::singleAssessmentFromId($assessmentId);

        if (empty($assessment)) {
            return Helpers::validationResponse('Assessment Not Found');
        }

        $user = Helpers::getUser();

        if (!$user) {
            return Helpers::forbiddenResponse('Unauthorized user');
        }

        // Ownership check
        $isOwner = Assessment::where('id', $assessmentId)
            ->where('user_id', $user->id)
            ->exists();

        if (!$isOwner) {
            return Helpers::forbiddenResponse('Unauthorized assessment access');
        }

        return $next($request);
    }
}
