<?php

namespace App\Http\Livewire\Admin\User;

use Livewire\Component;
use App\Models\Assessment;
use Livewire\WithPagination;

class AllUser extends Component
{
    use WithPagination;

    public $name = '';
    public $email = '';
    public $age = '';
    protected $assessments = [];
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    protected $updatesQueryString = [
        'name' => ['except' => ''],
        'email' => ['except' => ''],
        'age' => ['except' => ''],
    ];

    public function mount()
    {
        $this->fill(request()->only('name', 'email', 'age'));
        $this->searchFilter();
    }

    public function updated()
    {
        $this->searchFilter();
    }

    public function searchFilter()
    {
        $this->assessments = Assessment::allAssessment($this->name, $this->email, $this->age)->paginate($this->perPage);

    }

    public function render()
    {

        return view('livewire.admin.user.all-user', [
            'assessments' => $this->assessments,
        ]);
    }
}
