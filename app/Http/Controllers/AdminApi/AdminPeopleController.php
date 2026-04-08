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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminPeopleController extends Controller
{
    use AuditsAdminActions;

    /**
     * Unified people list (merged users + assessment status).
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 20);
            $search = $request->input('search');
            $tab = $request->input('tab', 'all');

            $query = User::query()->select([
                'id', 'first_name', 'last_name', 'email', 'gender', 'plan',
                'created_at', 'updated_at',
            ]);

            // Tab filtering
            switch ($tab) {
                case 'premium':
                    $query->where('plan', '!=', 'freemium');
                    break;
                case 'free':
                    $query->where('plan', 'freemium');
                    break;
                case 'completed':
                    $query->whereHas('assessment', function ($q) {
                        $q->where('page', 0);
                    });
                    break;
                case 'no_assessment':
                    $query->whereDoesntHave('assessment');
                    break;
            }

            // Search
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

            // Append assessment status + top trait
            $users->getCollection()->transform(function ($user) {
                $assessment = Assessment::where('user_id', $user->id)->latest()->first();

                $assessmentStatus = 'not_started';
                $topTrait = null;

                if ($assessment) {
                    $assessmentStatus = $assessment->page === 0 ? 'completed' : 'in_progress';

                    if ($assessment->page === 0) {
                        $traitMap = [
                            'sa' => 'Regal', 'ma' => 'Energetic', 'jo' => 'Absorptive',
                            'lu' => 'Romantic', 'ven' => 'Sympathetic', 'mer' => 'Perceptive',
                            'so' => 'Effervescent',
                        ];
                        $maxTrait = null;
                        $maxVal = -1;
                        foreach ($traitMap as $col => $label) {
                            if ($assessment->$col > $maxVal) {
                                $maxVal = $assessment->$col;
                                $maxTrait = $label;
                            }
                        }
                        $topTrait = $maxTrait;
                    }
                }

                $user->assessment_status = $assessmentStatus;
                $user->top_trait = $topTrait;
                return $user;
            });

            return Helpers::successResponse($users, 'People retrieved');
        } catch (\Throwable $e) {
            Log::error('AdminPeopleController@index error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Single person detail with subscription info.
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::with(['subscription'])->findOrFail($id);
            $assessment = Assessment::where('user_id', $id)->latest()->first();

            return Helpers::successResponse([
                'user' => $user,
                'assessment_status' => $assessment
                    ? ($assessment->page === 0 ? 'completed' : 'in_progress')
                    : 'not_started',
            ], 'Person detail retrieved');
        } catch (\Throwable $e) {
            Log::error('AdminPeopleController@show error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Full assessment grid for a user (3-row grid with all sections).
     */
    public function grid($id): JsonResponse
    {
        try {
            $assessment = Assessment::where('user_id', $id)->where('page', 0)->latest()->firstOrFail();

            $gridData = Assessment::getAllRowGrid($assessment->id);
            $colorCodes = AssessmentColorCode::getCodeColor($assessment->id);

            $allCodes = AssessmentColorCode::where('assessment_id', $assessment->id)->get();
            $authenticDrivers = $allCodes->where('code_color', 'green')
                ->whereIn('code', ['de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil'])
                ->pluck('code')
                ->toArray();

            $pilot = $authenticDrivers[0] ?? null;
            $coPilot = $authenticDrivers[1] ?? null;

            return Helpers::successResponse([
                'grid' => $gridData,
                'colorCodes' => $colorCodes,
                'pilot' => $pilot,
                'coPilot' => $coPilot,
                'pv' => $gridData['firstRow']['pv'] ?? null,
                'ep' => $gridData['firstRow']['ep'] ?? null,
            ], 'Assessment grid retrieved');
        } catch (\Throwable $e) {
            Log::error('AdminPeopleController@grid error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Activity log for a person.
     */
    public function activity($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Pull from admin audit log + login activity
            $activity = DB::table('admin_audit_logs')
                ->where('target_type', 'user')
                ->where('target_id', $id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return Helpers::successResponse($activity, 'Activity retrieved');
        } catch (\Throwable $e) {
            Log::error('AdminPeopleController@activity error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * HAi conversation history for a person.
     */
    public function haiHistory($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            $history = DB::table('hai_conversations')
                ->where('user_id', $id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return Helpers::successResponse($history, 'HAi history retrieved');
        } catch (\Throwable $e) {
            Log::error('AdminPeopleController@haiHistory error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Admin notes for a person.
     */
    public function notes($id): JsonResponse
    {
        try {
            $notes = DB::table('admin_notes')
                ->where('target_type', 'user')
                ->where('target_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            return Helpers::successResponse($notes, 'Notes retrieved');
        } catch (\Throwable $e) {
            Log::error('AdminPeopleController@notes error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Add an admin note for a person.
     */
    public function addNote(Request $request, $id): JsonResponse
    {
        try {
            $request->validate(['text' => 'required|string|max:5000']);

            $noteId = DB::table('admin_notes')->insertGetId([
                'admin_id' => Helpers::getUser()->id,
                'target_type' => 'user',
                'target_id' => $id,
                'text' => $request->input('text'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->logAdminAction('add_note', 'user', (int) $id);

            return Helpers::successResponse(['id' => $noteId], 'Note added', 201);
        } catch (\Throwable $e) {
            Log::error('AdminPeopleController@addNote error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }
}
