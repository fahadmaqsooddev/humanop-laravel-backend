<?php

namespace App\Http\Livewire\Admin\User;

use Livewire\Component;
use App\Models\Assessment;
use Livewire\WithPagination;

class AllUser extends Component
{
    use WithPagination;

    public $style_code = '';
    public $feature_code = '';
    public $style_color = '';
    public $feature_color = '';
    public $feature_number = '';
    public $feature_carousel_index = '';
    public $style_carousel_index = '';
    public $style_number = '';
    public $name = '';
    public $email = '';
    public $age = '';
    protected $assessments = [];
    public $perPage = 10;
    public $selectedStyleCells = [];
    public $selectedFeatureCells = [];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['selectStyleCode', 'selectFeatureCode','selectStyleNumber','selectFeatureNumber'];

    protected $updatesQueryString = [
        'name' => ['except' => ''],
        'email' => ['except' => ''],
        'age' => ['except' => ''],
        'style_code' => ['except' => ''],
        'feature_code' => ['except' => ''],
        'style_color' => ['except' => ''],
        'feature_color' => ['except' => ''],
        'feature_carousel_index' => ['except' => ''],
        'style_carousel_index' => ['except' => ''],
//        'number' => ['except' => ''],
    ];

    public function mount()
    {
        $this->fill(request()->only('name', 'email', 'age', 'style_code', 'style_color', 'feature_code', 'feature_color','feature_carousel_index','style_carousel_index'));
        $this->searchFilter();
    }

    public function updated()
    {
        $this->searchFilter();
    }

    public function selectStyleCode($select_style_code, $select_style_code_color)
    {
        $this->selectedStyleCells[$select_style_code] = $select_style_code_color;
        $this->style_code = $select_style_code;
        $this->style_color = $select_style_code_color;
        $this->style_number = '';
        $this->searchFilter();
    }

    public function selectFeatureCode($select_feature_code, $select_feature_code_color)
    {
        $this->selectedFeatureCells[$select_feature_code] = $select_feature_code_color;
        $this->feature_code = $select_feature_code;
        $this->feature_color = $select_feature_code_color;
        $this->feature_number = '';
        $this->searchFilter();
    }

    public function selectFeatureNumber($selectNum, $index, $selectColor, $selectCode)
    {

        $this->feature_number = $selectNum;
        $this->feature_code = $selectCode;
        $this->feature_color = $selectColor;
        $this->feature_carousel_index = $index;

        $this->searchFilter();
    }

    public function selectStyleNumber($selectNum, $index, $selectColor, $selectCode)
    {
        $this->style_number = $selectNum;
        $this->style_color = $selectColor;
        $this->style_code = $selectCode;
        $this->style_carousel_index = $index;
        $this->searchFilter();
    }

    public function searchFilter()
    {
        $this->assessments = Assessment::allAssessment($this->name, $this->email, $this->age, $this->style_code, $this->style_color, $this->style_number, $this->feature_code, $this->feature_color, $this->feature_number);
    }

    public function render()
    {
        return view('livewire.admin.user.all-user', [
            'assessments' => $this->assessments,
            'selectedStyleCells' => $this->selectedStyleCells,
            'selectedFeatureCells' => $this->selectedFeatureCells,
        ]);
    }

}
