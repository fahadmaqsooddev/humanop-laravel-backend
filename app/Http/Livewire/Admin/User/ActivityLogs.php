<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\Activity;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogs extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $per_page = 10, $userId;

    public function mount($user_id)
    {
        $this->userId = $user_id;
    }

    public function getActivityLogsProperty()
    {
        return Activity::getLogs($this->userId, $this->per_page);
    }

    public function render()
    {
        return view('livewire.admin.user.activity-logs', [
            'activityLogs' => $this->activityLogs,
        ]);
    }
}
