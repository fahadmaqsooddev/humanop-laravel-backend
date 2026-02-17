<?php

namespace App\Helpers\v4\ActivityLogs;

use App\Helpers\v4\Helpers;
use App\Models\v4\Activity;

class ActivityLogger
{

    public static function addLog($title, $message, $properties = [], $model = null)
    {
        $user = Helpers::getUser();

        activity()
            ->causedBy($user)
            ->withProperties(array_merge($properties, [
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]))
            ->tap(function (Activity $activity) use ($title, $message, $model, $user) {

                // Custom fields
                $activity->action_title = $title;
                $activity->action_description = $message;
                $activity->url = request()->fullUrl();

                // Requested fields
                $activity->description = $message;
                $activity->event = $title;

                // ⭐ SAFE SUBJECT HANDLING ⭐
                if (!empty($model) && is_object($model)) {
                    // Model provided → use it safely
                    $activity->subject_type = get_class($model);
                    $activity->subject_id = $model->id ?? null;
                } else {
                    // Model NOT provided → fall back to logged-in user
                    if (!empty($user)) {
                        $activity->subject_type = get_class($user);
                        $activity->subject_id = $user->id;
                    } else {
                        // User bhi null ho → safe default (avoid crashing)
                        $activity->subject_type = null;
                        $activity->subject_id = null;
                    }
                }
            })
            ->log($message);
    }


}
