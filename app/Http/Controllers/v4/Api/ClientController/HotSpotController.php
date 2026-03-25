<?php

namespace App\Http\Controllers\v4\Api\ClientController;

use App\Enums\Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotSpotUser;
use App\Models\HotSpot;
use App\Helpers\Helpers;
use App\Models\Assessment;

class HotSpotController extends Controller
{
    public function ensureArray($data)
    {
        if (!$data) return [];
        return is_array($data) && array_key_exists(0, $data) ? $data : [$data];
    }

    public function getTrendDirection(Request $request)
    {
        try {

            $userId = Helpers::getUser()->id;

            if (Helpers::getUser()->plan_name != Admin::PREMIUM_PLAN_NAME) {

                return Helpers::validationResponse("Only Premium Users can access this feature");

            }

            $dob = Helpers::getUser()->date_of_birth;

            $latestAssessmentId = HotSpotUser::getLatestAssessmentId($userId);

            if (!$latestAssessmentId) {

                return Helpers::validationResponse("No assessment data found");

            }

            $previousAssessmentId = HotSpotUser::getPreviousAssessmentId($userId, $latestAssessmentId);

            if (!$previousAssessmentId) {

                return Helpers::validationResponse("First Assessment.No Previous Data");

            }

            $latestAssessment = Assessment::singleAssessmentFromId($latestAssessmentId, null);

            $latestCore = Assessment::getCoreState($latestAssessment, $dob);

            $previousAssessment = Assessment::singleAssessmentFromId($previousAssessmentId, null);

            $prevCore = Assessment::getCoreState($previousAssessment, $dob);

            $normalize = fn($item) => is_array($item) && isset($item[0]) ? $item : ($item ? [$item] : []);

            $calculateShifts = function ($category, $prevData, $currData, $descField) use ($normalize) {

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
                        'description' => $descField
                    ];

                }

                return $shifts;

            };

            $hotspotMessages = config('hotspot_messages');

            $traitsShifts = $calculateShifts(

                $hotspotMessages['traits']['label'],

                $prevCore['topThreeStyles'] ?? [],

                $latestCore['topThreeStyles'] ?? [],

                $hotspotMessages['traits']['message']

            );

            $driversShifts = $calculateShifts(

                $hotspotMessages['drivers']['label'],

                $prevCore['topTwoFeatures'] ?? [],

                $latestCore['topTwoFeatures'] ?? [],

                $hotspotMessages['drivers']['message']

            );

            $alchemyShifts = $calculateShifts(

                $hotspotMessages['alchemy']['label'],

                $prevCore['boundary'] ?? [],

                $latestCore['boundary'] ?? [],

                $hotspotMessages['alchemy']['message']

            );

            $commShifts = $calculateShifts(

                $hotspotMessages['comm_style']['label'],

                $prevCore['topCommunication'] ?? [],

                $latestCore['topCommunication'] ?? [],

                $hotspotMessages['comm_style']['message']

            );

            $perceptionShifts = $calculateShifts(

                $hotspotMessages['perception']['label'],

                $prevCore['perception'] ?? [],

                $latestCore['perception'] ?? [],

                $hotspotMessages['perception']['message']

            );

            $energyShifts = $calculateShifts(

                $hotspotMessages['energy_pool']['label'],

                $prevCore['energyPool'] ?? [],

                $latestCore['energyPool'] ?? [],

                $hotspotMessages['energy_pool']['message']

            );

            $prevRows = HotSpotUser::getRows($userId, $previousAssessmentId);

            $currRows = HotSpotUser::getRows($userId, $latestAssessmentId);

            $prevShift = optional($prevRows->first())->shift_interval;

            $currShift = optional($currRows->first())->shift_interval;

            $intervalShift = $prevShift !== $currShift;

            $prevHotspots = $prevRows->pluck('hotspot_score')->filter()->toArray();

            $currHotspots = $currRows->pluck('hotspot_score')->filter()->toArray();

            $minPrevPriority = !empty($prevHotspots) ? min($prevHotspots) : null;

            $minCurrPriority = !empty($currHotspots) ? min($currHotspots) : null;

            if ($minPrevPriority === null || $minCurrPriority === null) {

                $trend = 'NEUTRAL';

            } elseif ($minCurrPriority > $minPrevPriority) {

                $trend = 'POSITIVE';

            } elseif ($minCurrPriority < $minPrevPriority) {

                $trend = 'NEGATIVE';

            } else {

                $trend = 'NEUTRAL';

            }


            $hotspotNameMap = HotSpot::pluck('name', 'id')->toArray();

            $resolved = collect(array_diff($prevHotspots, $currHotspots))
                ->values()
                ->map(function ($hotspotId, $index) use ($hotspotNameMap) {

                    $hotspotName = $hotspotNameMap[$hotspotId] ?? 'Unknown';

                    return [
                        'id' => 'HOTSPOT_' . str_pad($hotspotId, 2, '0', STR_PAD_LEFT),
                        'name' => $hotspotName,
                        'priority' => $hotspotId,
                        'description' => 'Eliminated Priority #' . $hotspotId . ' (' . $hotspotName . ')',
                        'count' => $index + 1
                    ];

                })
                ->toArray();


            $new = collect(array_diff($currHotspots, $prevHotspots))
                ->values()
                ->map(function ($hotspotId, $index) use ($hotspotNameMap) {

                    $hotspotName = $hotspotNameMap[$hotspotId] ?? 'Unknown';

                    return [
                        'id' => 'HOTSPOT_' . str_pad($hotspotId, 2, '0', STR_PAD_LEFT),
                        'name' => $hotspotName,
                        'priority' => $hotspotId,
                        'description' => 'New Priority #' . $hotspotId . ' (' . $hotspotName . ')',
                        'count' => $index + 1
                    ];

                })
                ->toArray();


            $persistent = collect(array_intersect($prevHotspots, $currHotspots))
                ->values()
                ->map(function ($hotspotId, $index) use ($hotspotNameMap) {

                    $hotspotName = $hotspotNameMap[$hotspotId] ?? 'Unknown';

                    return [
                        'id' => 'HOTSPOT_' . str_pad($hotspotId, 2, '0', STR_PAD_LEFT),
                        'name' => $hotspotName,
                        'priority' => $hotspotId,
                        'description' => 'Persistent Priority #' . $hotspotId . ' (' . $hotspotName . ')',
                        'count' => $index + 1
                    ];

                })
                ->toArray();

            $authenticShifts = [
                'interval_of_life' => $intervalShift,
                'traits' => $traitsShifts,
                'drivers' => $driversShifts,
                'alchemy' => $alchemyShifts,
                'comm_style' => $commShifts,
                'perception' => $perceptionShifts,
                'energy_pool' => $energyShifts
            ];

            $primaryWinText = null;

            if (!empty($prevRows)) {

                $minPrevRow = $prevRows->sortBy('hotspot_score')->first(); // lowest score

                $minPrevScore = $minPrevRow->hotspot_score;

                $minPrevName = $minPrevRow->names; // DB se name

                if (!in_array($minPrevScore, $currRows->pluck('hotspot_score')->toArray())) {

                    $primaryWinText = "Eliminated Priority #{$minPrevScore} Drain ({$minPrevName})";

                }

            }

            $currentHighestDrain = null;

            if (!empty($currRows)) {

                $minCurrRow = $currRows->sortBy('hotspot_score')->first(); // lowest score

                $minCurrScore = $minCurrRow->hotspot_score;

                $minCurrName = $minCurrRow->names;

                $currentHighestDrain = "Priority #{$minCurrScore} ({$minCurrName})";

            }

            $contextNote = $hotspotMessages['trend_context'][$trend] ?? $hotspotMessages['trend_context']['NEUTRAL'];

            $data = [
                'trend' => $trend,
                'interval_shift' => $intervalShift,
                'previous_assessment_date' => $previousAssessment->updated_at,
                'current_assessment_date' => $latestAssessment->updated_at,
                'hotspot_delta' => [
                    'resolved' => $resolved,
                    'new' => $new,
                    'persistent' => $persistent
                ],
                'authentic_shifts' => $authenticShifts,
                'hai_analysis_prompt_context' => [
                    'primary_win' => $primaryWinText,
                    'current_highest_drain' => $currentHighestDrain,
                    'context_note' => $contextNote
                ]

            ];

            return Helpers::successResponse('Trend data', $data);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }
}
