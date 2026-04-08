<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use App\Helpers\Helpers;
use App\Models\User;
use App\Traits\AuditsAdminActions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminTeamController extends Controller
{
    use AuditsAdminActions;

    /**
     * List all admin team members.
     */
    public function index(): JsonResponse
    {
        try {
            $admins = User::role(['super_admin', 'admin', 'support', 'read_only'])
                ->select(['id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at'])
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                        'role' => $user->getRoleNames()->first() ?? 'read_only',
                        'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                        'last_login' => $user->updated_at,
                        'status' => $user->deleted_at ? 'inactive' : 'active',
                    ];
                });

            return Helpers::successResponse($admins, 'Admin team retrieved');
        } catch (\Throwable $e) {
            Log::error('AdminTeamController@index error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Invite a new admin user.
     */
    public function invite(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'role' => 'required|in:admin,support,read_only',
                'permissions' => 'array',
            ]);

            // Check if user exists
            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return Helpers::errorResponse('User not found. They must have an account first.', 404);
            }

            // Assign role via Spatie
            $user->syncRoles([$request->input('role')]);

            // Assign permissions
            if ($request->has('permissions')) {
                $user->syncPermissions($request->input('permissions'));
            }

            $this->logAdminAction('invite_admin', 'user', $user->id, null, [
                'role' => $request->input('role'),
                'permissions' => $request->input('permissions'),
            ]);

            return Helpers::successResponse(['id' => $user->id], 'Admin invited', 201);
        } catch (\Throwable $e) {
            Log::error('AdminTeamController@invite error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Show a single admin team member.
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            return Helpers::successResponse([
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first() ?? 'read_only',
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'last_login' => $user->updated_at,
                'status' => $user->deleted_at ? 'inactive' : 'active',
            ], 'Admin detail retrieved');
        } catch (\Throwable $e) {
            Log::error('AdminTeamController@show error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Update an admin's role and permissions.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'role' => 'sometimes|in:super_admin,admin,support,read_only',
                'permissions' => 'sometimes|array',
            ]);

            $user = User::findOrFail($id);
            $oldRole = $user->getRoleNames()->first();

            if ($request->has('role')) {
                $user->syncRoles([$request->input('role')]);
            }

            if ($request->has('permissions')) {
                $user->syncPermissions($request->input('permissions'));
            }

            $this->logAdminAction('update_admin', 'user', (int) $id, [
                'role' => $oldRole,
            ], [
                'role' => $request->input('role', $oldRole),
            ]);

            return Helpers::successResponse(null, 'Admin updated');
        } catch (\Throwable $e) {
            Log::error('AdminTeamController@update error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Deactivate an admin.
     */
    public function deactivate($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // Don't allow deactivating super admins
            if ($user->hasRole('super_admin')) {
                return Helpers::errorResponse('Cannot deactivate a super admin.', 403);
            }

            $user->removeRole($user->getRoleNames()->first());

            $this->logAdminAction('deactivate_admin', 'user', (int) $id);

            return Helpers::successResponse(null, 'Admin deactivated');
        } catch (\Throwable $e) {
            Log::error('AdminTeamController@deactivate error', ['error' => $e->getMessage()]);
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }
}
