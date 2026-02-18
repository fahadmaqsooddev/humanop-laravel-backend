<?php

namespace App\Models\v4\FamilyMatrix;

use App\Enums\Admin\Admin;
use App\Helpers\v4\ActivityLogs\ActivityLogger;
use App\Helpers\v4\Assessments\AssessmentHelper;
use App\Models\Admin\Notification\Notification;
use App\Models\v4\Assessment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Events\v4\FamilyMatrix\FamilyMatrixPermission;
use App\Events\v4\FamilyMatrix\FamilyMatrixPermissionApproved;
use App\Models\v4\User;
use App\Helpers\v4\Helpers;


use Illuminate\Support\Facades\Log;


class AssignFamilyMatrixRelationship extends Model
{
    use HasFactory;

    const CONSENT_PENDING = 0; // ya null agar aap null use karte hain pending ke liye
    const CONSENT_APPROVED = 1;
    const CONSENT_REJECTED = 2;

    // Consent text/status for frontend or logs
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';


    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_id');
    }

    public function relationship()
    {
        return $this->belongsTo(FamilyMatrixRelationship::class, 'relationship_id');
    }


    public static function getRelationships($userId = null)
    {

        return self::with([
            'targetUser:id,first_name,last_name,email',
            'relationship:id,relationship_name'
        ])

            ->where('user_id', $userId)

            ->orderBy('created_at', 'desc')

            ->get()

            ->map(function ($relation) {

                $compatibilityResult = [];

                if ($relation->consent == 1) {

                    $targetId = $relation->target_id;

                    $userId = $relation->user_id;

                    $assessments = AssessmentHelper::getUserAssessments([$userId, $targetId]);

                    if (count($assessments) == 2) {

                        $userAssessment = $assessments[$userId];

                        $targetAssessment = $assessments[$targetId];

                        $loginUserTraitWeight = Assessment::getTopThreeTraitWeight($targetAssessment);

                        $userTraitWeight = Assessment::getTopThreeTraitWeight($userAssessment);

                        $compatibilityScore = Helpers::getCompatabilityBetweenTwoPerson($loginUserTraitWeight, $userTraitWeight, $targetAssessment, $userAssessment);

                        $compatibilityResult = [
                            'compatibility_score' => $compatibilityScore,
                            'compatibility_matrix' => AssessmentHelper::buildCompatibilityMatrix($userAssessment, $targetAssessment),
                        ];

                    }

                }

                return [
                    'id' => $relation->id,
                    'user_id' => $relation->user_id,
                    'target_id' => $relation->target_id,
                    'relationship_id' => $relation->relationship_id,
                    'consent' => $relation->consent,
                    'consent_status' => match ($relation->consent) {
                        self::CONSENT_APPROVED => self::STATUS_APPROVED,
                        self::CONSENT_REJECTED => self::STATUS_REJECTED,
                        default => self::STATUS_PENDING,
                    },
                    'relationship_name' => $relation->relationship->relationship_name ?? null,
                    'target_name' => trim(($relation->targetUser?->first_name ?? '') . ' ' . ($relation->targetUser?->last_name ?? '')),
                    'target_email' => $relation->targetUser->email ?? null,
                    'compatibility_result' => $compatibilityResult ?? [],
                ];
            });
    }

    public static function checkRelationship($userId = null, $dataArray = null)
    {

        return self::where('user_id', $userId)
            ->where('target_id', $dataArray['target_id'])
            ->where('relationship_id', $dataArray['relationship_id'])
            ->exists();

    }

    public static function createAssignRelationships($userId = null, $dataArray = null)
    {

        $assignRelationship = self::create([
            'user_id' => $userId,
            'target_id' => $dataArray['target_id'],
            'relationship_id' => $dataArray['relationship_id'],
        ]);

        $user = Helpers::getUser();

        $target = User::find($dataArray['target_id']);


        $msg = $user->first_name . ' ' . $user->last_name . ' has requested access to your Family Matrix.';

        if ($user && $target) {
            event(new FamilyMatrixPermission(
                $user->id,
                $target->id,
                $msg
            ));
        }


        ActivityLogger::addLog(
            'Family Matrix Permission Requested',
            "You have requested access to " . ($target->first_name . ' ' . $target->last_name) . "'s Family Matrix."
        );

        Notification::createNotification(
            'family_matrix_request',
            $msg,
            null,
            $assignRelationship->target_id,
            1,
            Admin::FAMILY_MATRIX_RELATIONSHIP_PERMISSION,
            Admin::B2C_NOTIFICATION,
            $user->id
        );

        return $assignRelationship;
    }

    public static function deleteRelationship($targetId = null, $userId = null)
    {
        return self::where('user_id', $userId)->where('target_id', $targetId)->delete();
    }

    public static function findRelation(int $userId, int $targetId)
    {

        return self::where(function ($q) use ($userId, $targetId) {
            $q->where('user_id', $userId)
                ->where('target_id', $targetId);
        })
            ->orWhere(function ($q) use ($userId, $targetId) {
                $q->where('user_id', $targetId)
                    ->where('target_id', $userId);
            })->first();
    }


    public static function updateConsent(int $userId, int $targetId, int $consent)
    {

        $relation = self::findRelation($userId, $targetId);

        if (!$relation) {

            return null;

        }

        $relation->update(['consent' => $consent,]);

        $requester = User::find($targetId);

        $approver = User::find($userId);

        if (!$requester || !$approver) {

            return $relation;

        }

        $actionText = $consent === 1 ? 'approved' : ($consent === 2 ? 'declined' : null);

        if ($actionText) {

            $message = "Your Family Matrix permission request has been {$actionText} by " . ($approver->first_name ?? '');

            event(new FamilyMatrixPermissionApproved($targetId, $userId, $message, $consent));

            ActivityLogger::addLog('Family Matrix Permission ' . ucfirst($actionText), $message);

            Notification::createNotification($consent === 1 ? 'family_matrix_approved' : 'family_matrix_rejected', $message, null, $targetId, 1, Admin::FAMILY_MATRIX_RELATIONSHIP_PERMISSION, Admin::B2C_NOTIFICATION, Helpers::getUser()['id']);

        }

        return $relation;

    }

}
