<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use App\Helpers\Helpers;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use App\Models\User;
use App\Traits\AuditsAdminActions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RdLabController extends Controller
{
    use AuditsAdminActions;

    /**
     * Search across all assessments by trait/driver/alchemy filters.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 50);
            $traits = $request->input('traits', []);
            $drivers = $request->input('drivers', []);
            $alchemy = $request->input('alchemy');
            $pvPolarity = $request->input('pv_polarity');
            $gender = $request->input('gender');
            $dateRange = $request->input('date_range');

            $query = Assessment::query()
                ->where('page', 0) // Only completed assessments
                ->with(['users:id,first_name,last_name,email,gender']);

            // Trait filters
            if (!empty($traits)) {
                foreach ($traits as $filter) {
                    $code = strtolower($filter['code'] ?? '');
                    if (!in_array($code, ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'])) continue;

                    if (isset($filter['min_volume'])) {
                        $query->where($code, '>=', $filter['min_volume']);
                    }
                    if (isset($filter['max_volume'])) {
                        $query->where($code, '<=', $filter['max_volume']);
                    }
                    if (isset($filter['classification'])) {
                        switch ($filter['classification']) {
                            case 'authentic':
                                $query->where($code, '>=', 5);
                                break;
                            case 'inauthentic':
                                $query->where($code, '<', 5);
                                break;
                        }
                    }
                }
            }

            // Driver filters
            if (!empty($drivers)) {
                foreach ($drivers as $filter) {
                    $code = strtolower($filter['code'] ?? '');
                    if (!in_array($code, ['de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil'])) continue;

                    if (isset($filter['fuel_type'])) {
                        // Strong = volume >= 5, Weak = volume < 5
                        if ($filter['fuel_type'] === 'strong') {
                            $query->where($code, '>=', 5);
                        } elseif ($filter['fuel_type'] === 'weak') {
                            $query->where($code, '<', 5);
                        }
                    }
                }
            }

            // Gender filter (via user relationship)
            if ($gender && $gender !== 'any') {
                $query->whereHas('users', function ($q) use ($gender) {
                    $q->where('gender', strtolower($gender));
                });
            }

            // Date range
            if ($dateRange) {
                if (!empty($dateRange['start'])) {
                    $query->where('updated_at', '>=', $dateRange['start']);
                }
                if (!empty($dateRange['end'])) {
                    $query->where('updated_at', '<=', $dateRange['end']);
                }
            }

            $results = $query->orderBy('updated_at', 'desc')->paginate($perPage);

            $this->logAdminAction('rd_lab_search', null, null, null, [
                'filter_count' => count($traits) + count($drivers),
                'results_count' => $results->total(),
            ]);

            return Helpers::successResponse($results, 'R&D Lab search results');
        } catch (\Throwable $e) {
            Log::error('RdLabController@search error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Aggregate stats for the R&D Lab dashboard.
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = Cache::remember('rd_lab_stats', 300, function () {
                $totalCompleted = Assessment::where('page', 0)->count();

                // Distribution by dominant trait
                $traitCols = ['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'];
                $traitLabels = ['Regal', 'Energetic', 'Absorptive', 'Romantic', 'Sympathetic', 'Perceptive', 'Effervescent'];
                $distribution = [];

                foreach ($traitCols as $i => $col) {
                    $count = Assessment::where('page', 0)->where($col, '>=', 5)->count();
                    $distribution[$traitLabels[$i]] = $count;
                }

                return [
                    'total_completed' => $totalCompleted,
                    'trait_distribution' => $distribution,
                ];
            });

            return Helpers::successResponse($stats, 'R&D Lab stats retrieved');
        } catch (\Throwable $e) {
            Log::error('RdLabController@stats error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }
}
