<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\User;
use Livewire\Component;

class DailyTipCreateForm extends Component
{
    public $title, $description, $code, $tip_id, $min_point, $max_point, $interval_of_life, $pv, $ep;
    public $subscription_type = 'Freemium';
    public $min_point_array = ['SA' => 0, 'MA' => 0, 'JO' => 0, 'LU' => 0, 'VEN' => 0, 'MER' => 0, 'SO' => 0, 'DE' => 0, 'DOM' => 0, 'FE' => 0, 'GRE' => 0, 'LUN' => 0, 'NAI' => 0, 'NE' => 0, 'POW' => 0, 'SP' => 0, 'TRA' => 0, 'VAN' => 0, 'WIL' => 0, 'G' => 502, 'S' => 52, 'C' => 7, 'GS' => 403, 'SG' => 322, 'SC' => 43, 'CS' => 34, 'EM' => 3, 'INS' => 3, 'INT' => 3, 'MOV' => 3];
    public $max_point_array = ['SA' => 0, 'MA' => 0, 'JO' => 0, 'LU' => 0, 'VEN' => 0, 'MER' => 0, 'SO' => 0, 'DE' => 0, 'DOM' => 0, 'FE' => 0, 'GRE' => 0, 'LUN' => 0, 'NAI' => 0, 'NE' => 0, 'POW' => 0, 'SP' => 0, 'TRA' => 0, 'VAN' => 0, 'WIL' => 0, 'G' => 502, 'S' => 52, 'C' => 7, 'GS' => 403, 'SG' => 322, 'SC' => 43, 'CS' => 34, 'EM' => 3, 'INS' => 3, 'INT' => 3, 'MOV' => 3];
    public $interval_of_life_array = ['motivation_life_cycle' => 'Motivation Life Cycle', 'roadworthy_life_cycle' => 'Roadworthy Life Cycle', 'power_life_cycle' => 'Power Life Cycle', 'mid_life_cycle' => 'Mid Life Cycle', 'awareness_life_cycle' => 'Awareness Life Cycle', 'forward_life_cycle' => 'Forward Life Cycle', 'liberated_life_cycle' => 'Liberated Life Cycle', 'being_life_cycle' => 'Being Life Cycle', 'review_life_cycle' => 'Review Life Cycle'];
    protected $listeners = ['updateEditTipValues', 'emptyDailyTipValues', 'updateContent'];

    protected $rules = [
        'code' => 'required',
        'title' => 'required',
        'description' => 'required',
        'subscription_type' => 'required',
        'min_point' => 'nullable',
        'max_point' => 'nullable',
        'interval_of_life' => 'nullable'
    ];

    protected $messages = [
        'code.required' => 'Code is required',
        'title.required' => 'Title is required',
        'subscription_type.required' => 'Please Select Subscription Type',
        'description.required' => 'Description is required',
    ];

    public function resetPointArray()
    {
        $this->min_point_array = ['SA' => 0, 'MA' => 0, 'JO' => 0, 'LU' => 0, 'VEN' => 0, 'MER' => 0, 'SO' => 0, 'DE' => 0, 'DOM' => 0, 'FE' => 0, 'GRE' => 0, 'LUN' => 0, 'NAI' => 0, 'NE' => 0, 'POW' => 0, 'SP' => 0, 'TRA' => 0, 'VAN' => 0, 'WIL' => 0, 'G' => 502, 'S' => 52, 'C' => 7, 'GS' => 403, 'SG' => 322, 'SC' => 43, 'CS' => 34, 'EM' => 3, 'INS' => 3, 'INT' => 3, 'MOV' => 3];
        $this->max_point_array = ['SA' => 0, 'MA' => 0, 'JO' => 0, 'LU' => 0, 'VEN' => 0, 'MER' => 0, 'SO' => 0, 'DE' => 0, 'DOM' => 0, 'FE' => 0, 'GRE' => 0, 'LUN' => 0, 'NAI' => 0, 'NE' => 0, 'POW' => 0, 'SP' => 0, 'TRA' => 0, 'VAN' => 0, 'WIL' => 0, 'G' => 502, 'S' => 52, 'C' => 7, 'GS' => 403, 'SG' => 322, 'SC' => 43, 'CS' => 34, 'EM' => 3, 'INS' => 3, 'INT' => 3, 'MOV' => 3];
    }

    public function selectCode($selectedCode)
    {
        $this->code = $selectedCode;
        $this->ep = null;
        $this->pv = null;
        $this->emit('codeSelected');
    }

    public function updateContent($editorId, $data)
    {
        $this->description = $data;
    }

    public function updateEditTipValues($id, $code, $title, $description, $interval, $subscription, $min_point, $max_point)
    {
        $this->emptyDailyTipValues();
        $this->tip_id = $id;
        $this->code = $code;
        $this->title = $title;
        $this->description = $description;
        $this->interval_of_life = $interval;
        $this->subscription_type = $subscription;
        $this->min_point = $min_point;
        $this->max_point = $max_point;

        if ($code != 'pv' && $code != 'ep') {
            $this->min_point_array[$code] = $min_point;
            $this->max_point_array[$code] = $max_point;
        } else {
            $this->resetPointArray();
        }
        $this->emit('contentUpdated', $this->description);
        $this->emit('sliderUpdated', $code, $min_point);
    }

    public function emptyDailyTipValues()
    {
        $this->tip_id = '';
        $this->code = '';
        $this->title = '';
        $this->description = '';
    }

    public function updateTip()
    {
        try {
            if (isset($this->ep)) {
                $this->code = 'ep';
                $this->min_point = $this->ep;
            } else if (isset($this->pv)) {
                $this->code = 'pv';
                $this->min_point = $this->pv;
            } else {
                if ($this->code) {
                    $this->min_point = $this->min_point_array[$this->code];
                    $this->max_point = $this->max_point_array[$this->code];
                }
            }

            $validatedData = $this->validate();

            if ($this->tip_id) {
                DailyTip::updateIntentionPlan($validatedData, $this->tip_id);
                $this->emit('closeModal');
                $this->reset();
                $this->emit('refreshDailyTips');
                $this->emit('updateSession', 'Updated');
            } else {
                DailyTip::createTip($validatedData);
                $this->emit('closeModal');
                $this->reset();
                $this->emit('refreshDailyTips');
                $this->emit('updateSession', 'Created');
            }
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.setting.daily-tip-create-form');
    }
}
