<?php

namespace App\Http\Requests;

use App\Models\Client\MessageThread\MessageThread;
use Illuminate\Foundation\Http\FormRequest;

class ChangeParticipantRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    public function rules()
    {
        return [
            "thread_id" => "required|exists:message_threads,id",

            "owner_id" => [
                "required",
                function ($attribute, $value, $fail) {
                    $thread = MessageThread::find(request('thread_id'));

                    if (!$thread) {
                        $fail("The selected group does not exist.");
                        return;
                    }

                    if ($thread->owner_id != $value) {
                        $fail("The selected owner does not belong to this group.");
                    }
                },
            ],

            "participant_id" => [
                "required",
                function ($attribute, $value, $fail) {
                    $thread = MessageThread::with('participants')->find(request('thread_id'));

                    if (!$thread) {
                        $fail("The selected group does not exist.");
                        return;
                    }

                    $isParticipant = $thread->participants()
                        ->where('user_id', $value)
                        ->exists();

                    if (!$isParticipant) {
                        $fail("The selected member does not exist in this group.");
                    }
                },
            ],

            "role" => "required|string",
        ];
    }

    public function messages()
    {
        return [
            "thread_id.required" => "Group ID is required.",
            "thread_id.exists" => "The selected group does not exist.",
            "owner_id.required" => "Owner ID is required.",
            "participant_id.required" => "Participant ID is required.",
            "role.required" => "Role is required.",
            "role.string" => "Role must be a valid string.",
        ];
    }


}
