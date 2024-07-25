<?php

namespace App\Http\Livewire\Admin\User;

use Livewire\Component;
use App\Models\Assessment;
use Livewire\WithPagination;

class AllUser extends Component
{
    use WithPagination;

    public $code = '';
    public $color = '';
    public $name = '';
    public $email = '';
    public $age = '';
    protected $assessments = [];
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['selectCode'];

    protected $updatesQueryString = [
        'name' => ['except' => ''],
        'email' => ['except' => ''],
        'age' => ['except' => ''],
        'code' => ['except' => ''],
        'color' => ['except' => ''],
    ];

    public function mount()
    {
        $this->fill(request()->only('name', 'email', 'age', 'code', 'color'));
        $this->searchFilter();
    }

    public function updated()
    {
        $this->searchFilter();
    }

    public function selectCode($select_code, $select_code_color)
    {

        $this->code = $select_code;
        $this->color = $select_code_color;
        $this->searchFilter();

    }

    public function searchFilter()
    {

        $this->assessments = Assessment::allAssessment($this->name, $this->email, $this->age, $this->code, $this->color);

    }

    public function render()
    {
        return view('livewire.admin.user.all-user', [
            'assessments' => $this->assessments,
        ]);
    }
}
