<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $assessment = Assessment::where('user_id', $user->id)->first();

        if (! $assessment) {
            return response()->json([
                'status'  => false,
                'message' => 'Assessment not completed. Please complete your assessment first.',
            ], 403);
        }


        $incompleteStatuses = [
            'not_started',
            'in_progress',
            'incomplete',
            'not_submitted',
        ];

        if (in_array($assessment->status, $incompleteStatuses)) {
            return response()->json([
                'status'  => false,
                'message' => 'Assessment not completed. Please complete your assessment first.',
                'errors'  => ['assessment' => 'Assessment is incomplete'],
            ], 403);
        }

        return $next($request);
    }
}
