<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\v4\Api\Client\Optimizationtrend\GetOptimizationTrendAnalysisRequest;
use App\Http\Requests\v4\Api\Client\Optimizationtrend\StoreOptimizationTrendAnalysisRequest;
use Illuminate\Http\Request;
use App\Models\HotSpotUser;
use App\Models\HotSpot;
use App\Helpers\Helpers;
use App\Models\Assessment;
use App\Models\v4\Client\OptimizationTrend\OptimizationTrendAnalysis;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HotSpotController extends Controller
{
    public function ensureArray($data)
    {
        if (!$data) return [];
        return is_array($data) && array_key_exists(0, $data) ? $data : [$data];
    }

    private function getTrendData($userId, $dob)
    {
        $latestAssessmentId = HotSpotUser::getLatestAssessmentId($userId);
        if (!$latestAssessmentId) {
            return ['error' => "No assessment data found"];
        }

        $previousAssessmentId = HotSpotUser::getPreviousAssessmentId($userId, $latestAssessmentId);
        if (!$previousAssessmentId) {
            return ['error' => "First Assessment. No Previous Data"];
        }

        // ------------------ Assessments ------------------
        $latestAssessment = Assessment::singleAssessmentFromId($latestAssessmentId, null);
        $previousAssessment = Assessment::singleAssessmentFromId($previousAssessmentId, null);

        $latestCore = Assessment::getCoreState($latestAssessment, $dob);
        $prevCore = Assessment::getCoreState($previousAssessment, $dob);

        // Helper to normalize array/object
        $normalize = function ($item) {
            if (!$item) return [];
            return is_array($item) && isset($item[0]) ? $item : [$item];
        };

        // Helper to calculate shifts
        $calculateShifts = function ($category, $prevData, $currData, $desc) use ($normalize) {

            $prevArr = collect($normalize($prevData))->pluck('public_name')->filter()->values()->toArray();
            $currArr = collect($normalize($currData))->pluck('public_name')->filter()->values()->toArray();

            $removed = array_values(array_diff($prevArr, $currArr));
            $added = array_values(array_diff($currArr, $prevArr));

            $max = max(count($removed), count($added));
            $shifts = [];

            for ($i = 0; $i < $max; $i++) {
                $shifts[] = [
                    'category' => $category,
                    'prev_value' => $removed[$i] ?? ($prevArr[0] ?? null),
                    'curr_value' => $added[$i] ?? ($currArr[0] ?? null),
                    'description' => $desc
                ];
            }

            return $shifts;
        };

        $messages = config('hotspot_messages');

        // ------------------ Core Shifts ------------------
        $traitsShifts = $calculateShifts(
            $messages['traits']['label'],
            $prevCore['topThreeStyles'] ?? [],
            $latestCore['topThreeStyles'] ?? [],
            $messages['traits']['message']
        );

        $driversShifts = $calculateShifts(
            $messages['drivers']['label'],
            $prevCore['topTwoFeatures'] ?? [],
            $latestCore['topTwoFeatures'] ?? [],
            $messages['drivers']['message']
        );

        $alchemyShifts = $calculateShifts(
            $messages['alchemy']['label'],
            $prevCore['boundary'] ?? [],
            $latestCore['boundary'] ?? [],
            $messages['alchemy']['message']
        );

        $commShifts = $calculateShifts(
            $messages['comm_style']['label'],
            $prevCore['topCommunication'] ?? [],
            $latestCore['topCommunication'] ?? [],
            $messages['comm_style']['message']
        );

        $perceptionShifts = $calculateShifts(
            $messages['perception']['label'],
            $prevCore['perception'] ?? [],
            $latestCore['perception'] ?? [],
            $messages['perception']['message']
        );

        $energyShifts = $calculateShifts(
            $messages['energy_pool']['label'],
            $prevCore['energyPool'] ?? [],
            $latestCore['energyPool'] ?? [],
            $messages['energy_pool']['message']
        );

        // ------------------ Hotspot Rows ------------------
        $prevRows = HotSpotUser::getRows($userId, $previousAssessmentId);
        $currRows = HotSpotUser::getRows($userId, $latestAssessmentId);

        $prevHotspots = $prevRows->pluck('hotspot_score')->filter()->toArray();
        $currHotspots = $currRows->pluck('hotspot_score')->filter()->toArray();

        // ------------------ Trend ------------------
        $minPrev = !empty($prevHotspots) ? min($prevHotspots) : null;
        $minCurr = !empty($currHotspots) ? min($currHotspots) : null;

        if ($minPrev === null || $minCurr === null) {
            $trend = 'NEUTRAL';
        } elseif ($minCurr > $minPrev) {
            $trend = 'POSITIVE';
        } elseif ($minCurr < $minPrev) {
            $trend = 'NEGATIVE';
        } else {
            $trend = 'NEUTRAL';
        }

        // ------------------ Hotspot Delta ------------------
        $hotspotNameMap = HotSpot::pluck('name', 'id')->toArray();

        $mapHotspots = function ($list, $type) use ($hotspotNameMap) {
            return collect($list)->values()->map(function ($id, $index) use ($hotspotNameMap, $type) {

                $name = $hotspotNameMap[$id] ?? 'Unknown';

                return [
                    'id' => 'HOTSPOT_' . str_pad($id, 2, '0', STR_PAD_LEFT),
                    'name' => $name,
                    'priority' => $id,
                    'description' => ucfirst($type) . " Priority #{$id} ({$name})",
                    'count' => $index + 1
                ];
            })->toArray();
        };

        $resolved = $mapHotspots(array_diff($prevHotspots, $currHotspots), 'eliminated');
        $new = $mapHotspots(array_diff($currHotspots, $prevHotspots), 'new');
        $persistent = $mapHotspots(array_intersect($prevHotspots, $currHotspots), 'persistent');

        // ------------------ Interval Shift ------------------
        $intervalShift = optional($prevRows->first())->shift_interval !== optional($currRows->first())->shift_interval;

        $contextNote = $messages['trend_context'][$trend] ?? $messages['trend_context']['NEUTRAL'];

        return [
            'data' => [
                'trend' => $trend,
                'interval_shift' => $intervalShift,
                'current_assessment_id' => $latestAssessment->id,
                'current_assessment_date' => $latestAssessment->updated_at,
                'previous_assessment_id' => $previousAssessment->id,
                'previous_assessment_date' => $previousAssessment->updated_at,

                'hotspot_delta' => [
                    'resolved' => $resolved,
                    'new' => $new,
                    'persistent' => $persistent
                ],

                'authentic_shifts' => [
                    'interval_of_life' => $intervalShift,
                    'traits' => $traitsShifts,
                    'drivers' => $driversShifts,
                    'alchemy' => $alchemyShifts,
                    'comm_style' => $commShifts,
                    'perception' => $perceptionShifts,
                    'energy_pool' => $energyShifts
                ],

                'hai_analysis_prompt_context' => [
                    'context_note' => $contextNote
                ]
            ]
        ];
    }

    public function getTrendDirection(Request $request)
    {
        try {

            $user = Helpers::getUser();

            if ($user->plan_name != Admin::PREMIUM_PLAN_NAME) {
                return Helpers::validationResponse("Only Premium Users can access this feature");
            }

            $result = $this->getTrendData($user->id, $user->date_of_birth);

            if (isset($result['error'])) {
                return Helpers::validationResponse($result['error']);
            }

            return Helpers::successResponse('Trend data', $result['data']);

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function generateAnalysis(Request $request)
    {
        try {

            $user = Helpers::getUser();

            if ($user->plan_name != Admin::PREMIUM_PLAN_NAME) {
                return Helpers::validationResponse("Only Premium Users can access this feature");
            }

            $result = $this->getTrendData($user->id, $user->date_of_birth);

            if (isset($result['error'])) {
                return Helpers::validationResponse($result['error']);
            }

            $body = [
                'context' => $request->input('context') ?? "",
                'trends' => $result['data'],
            ];

            $haiResponse = GuzzleHelpers::sendRequestFromGuzzleForNewHai(
                'post',
                "appChats/optimization-trend",
                $body
            );

            if ($haiResponse && !empty($haiResponse['response'])) {
                return Helpers::successResponse('Optimization Trend Analysis', $haiResponse['response']);
            }

            return Helpers::validationResponse('Something went wrong. Please try again later.');

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function storeOptimizationTrendAnalysis(StoreOptimizationTrendAnalysisRequest $request)
    {
        try {

            $user = Helpers::getUser();

            if ($user->plan_name != Admin::PREMIUM_PLAN_NAME) {

                return Helpers::validationResponse("Only Premium Users can access this feature");

            }

            $validated = $request->validated();

            $currentAssessmentId = (int) $validated['current_assessment_id'];

            $previousAssessmentId = (int) $validated['previous_assessment_id'];

            if ($currentAssessmentId === $previousAssessmentId) {

                return Helpers::validationResponse('Current and previous assessment must be different.');

            }

            $assessmentOwnershipCount = Assessment::query()
                ->where('user_id', $user->id)
                ->whereIn('id', [$currentAssessmentId, $previousAssessmentId])
                ->distinct()
                ->count('id');

            if ($assessmentOwnershipCount !== 2) {

                return Helpers::validationResponse('Assessments do not belong to this user.');

            }

            $record = OptimizationTrendAnalysis::storeOptimizationAnalysis([
                'user_id' => $user->id,
                'current_assessment_id' => $currentAssessmentId,
                'previous_assessment_id' => $previousAssessmentId,
                'context' => $validated['context'] ?? null,
                'ai_response' => $validated['ai_response'],
            ]);

            return Helpers::successResponse('Optimization Trend Analysis stored successfully.', $record);

        } catch (ValidationException $exception) {

            return Helpers::validationResponse($exception->validator->errors()->first());

        } catch (\Exception $exception) {

            Log::error('OptimizationTrendAnalysis error', [
                'user_id' => $user->id ?? null,
                'error' => $exception->getMessage(),
            ]);

            return Helpers::serverErrorResponse('Something went wrong');

        }

    }

    public function getOptimizationTrendAnalysis(GetOptimizationTrendAnalysisRequest $request)
    {
        try {

            $user = Helpers::getUser();

            if ($user->plan_name != Admin::PREMIUM_PLAN_NAME) {

                return Helpers::validationResponse("Only Premium Users can access this feature");

            }

            $validated = $request->validated();

            $currentAssessmentId = (int) $validated['current_assessment_id'];

            $previousAssessmentId = (int) $validated['previous_assessment_id'];

            $records = OptimizationTrendAnalysis::getOptimizationAnalysis($user->id, $currentAssessmentId, $previousAssessmentId);

            if ($records->isEmpty()) {

                return Helpers::validationResponse('Optimization Trend Analysis not found.');

            }

            return Helpers::successResponse('Optimization Trend Analysis', $records);

        } catch (ValidationException $exception) {

            return Helpers::validationResponse($exception->validator->errors()->first());

        } catch (\Exception $exception) {

            Log::error('OptimizationTrendAnalysis error', [
                'user_id' => $user->id ?? null,
                'error' => $exception->getMessage(),
            ]);

            return Helpers::serverErrorResponse('Something went wrong');

        }

    }

}
