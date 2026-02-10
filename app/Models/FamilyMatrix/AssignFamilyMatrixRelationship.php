<?php

namespace App\Models\FamilyMatrix;

use App\Enums\Admin\Admin;
use App\Helpers\ActivityLogs\ActivityLogger;
use App\Models\Admin\Notification\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Events\FamilyMatrix\FamilyMatrixPermission;
use App\Events\FamilyMatrix\FamilyMatrixPermissionApproved;
use App\Models\User;
use App\Helpers\Helpers;


use Illuminate\Support\Facades\Log;


class AssignFamilyMatrixRelationship extends Model
{
    use HasFactory;

    const CONSENT_PENDING = 0;
    const CONSENT_GRANTED = 1;
    const CONSENT_REJECTED  = 2;


    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getRelationships($userId = null)
    {
        return self::where([
            'user_id' => $userId,
            'consent' => self::CONSENT_GRANTED
        ])->orderBy('created_at', 'desc')->get();
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
            'user_id'         => $userId,
            'target_id'       => $dataArray['target_id'],
            'relationship_id' => $dataArray['relationship_id'],
        ]);

        $user   = Helpers::getUser();
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
            $user->id,
            1,
            Admin::FAMILY_MATRIX_RELATIONSHIP_PERMISSION,
            Admin::B2C_NOTIFICATION,
            $assignRelationship->target_id,

        );

        return $assignRelationship;
    }




    public static function deleteRelationship($targetId = null, $userId = null)
    {
        return self::where('user_id', $userId)->where('target_id', $targetId)->delete();
    }

    public static function findRelation(int $userId, int $targetId)
    {
        return self::where(function($q) use ($userId, $targetId) {
            $q->where('user_id', $userId)
                ->where('target_id', $targetId);
        })
        ->orWhere(function($q) use ($userId, $targetId) {
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

        $relation->update([
            'consent' => $consent,
        ]);

        $requester = User::find($targetId);
        $approver  = User::find($userId);

        if (!$requester || !$approver) {
            return $relation;
        }


        $actionText = $consent === 1 ? 'approved' : ($consent === 2 ? 'declined' : null);

        if ($actionText) {
            $message = "Your Family Matrix permission request has been {$actionText} by " . ($approver->first_name ?? '');

            event(new FamilyMatrixPermissionApproved(
                $targetId,
                $userId,
                $message,
                $consent
            ));

            ActivityLogger::addLog(
                'Family Matrix Permission ' . ucfirst($actionText),
                $message
            );

            Notification::createNotification(
                $consent === 1 ? 'family_matrix_approved' : 'family_matrix_rejected',
                $message,
                null,
                $targetId,
                1,
                Admin::FAMILY_MATRIX_RELATIONSHIP_PERMISSION,
                Admin::B2C_NOTIFICATION,
                Helpers::getUser()['id']
            );
        }

        return $relation;
    }

}
