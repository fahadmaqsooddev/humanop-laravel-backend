<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Helpers\Helpers;
use App\Models\Admin\DailyTip\DailyTip;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DailyTipCreateForm extends Component
{
    public $title, $description,  $tip_id, $min_point, $max_point, $interval_of_life, $pv, $ep;
    public $code = [];
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

    public function changeSubscriptionType(){
        $this->code = [];
        $this->emit('emptyEp');
        $this->emit('emptyPv');
    }

    public function updated($propertyName)
    {
        if($this->subscription_type != 'Freemium'){
            if ($propertyName == 'ep') {
                if(!in_array('ep',$this->code)){
                    $this->selectCode('ep');
                }
            }
            if ($propertyName == 'pv') {
                if(!in_array('pv',$this->code)){
                    $this->selectCode('pv');
                }
            }

        }else{

            if ($propertyName == 'ep') {
                $this->code = [];
                $this->emit('emptyPv');
                $this->code[] = 'ep';
            }
            if ($propertyName == 'pv') {
                $this->code = [];
                $this->emit('emptyEp');
                $this->code[] = 'pv';
            }
        }

    }

    public function selectCode($selectedCode)
    {
        if ($this->subscription_type == 'Freemium') {
            $this->code = [];
            $this->code[] = $selectedCode;
            $this->emit('emptyEp');
            $this->emit('emptyPv');
        } else
            if (($this->subscription_type == 'Core' || $this->subscription_type == 'Premium') && count($this->code) < 2) {

                $this->code[] = $selectedCode;
            } else {

                if (count($this->code) >= 2) {
                    if ($this->code[count($this->code) - 2] == 'ep') {
                        $this->emit('emptyEp');
                    }
                    if ($this->code[count($this->code) - 2] == 'pv') {
                        $this->emit('emptyPv');
                    }
                    unset($this->code[count($this->code) - 2]);
                    $this->code = array_values($this->code);
                }
                $this->code[] = $selectedCode;
            }

    }

    public function updateContent($editorId, $data)
    {
        $this->description = $data;
    }

    public function updateEditTipValues($id, $code, $title, $description, $interval, $subscription, $min_point, $max_point)
    {

        $this->resetPointArray();
        $this->emptyDailyTipValues();
        $minValues = explode(',',$min_point);
        $maxValues = explode(',',$max_point);
        $this->code = explode(',',$code);

        if(count($this->code) > 1){
            $this->min_point_array[$this->code[0]] = $minValues[0];

            $this->max_point_array[$this->code[0]] = $maxValues[0];


            $this->min_point_array[$this->code[1]] = $minValues[1];

            $this->max_point_array[$this->code[1]] = $maxValues[1];

        }else{
            $this->min_point_array[$this->code[0]] = $minValues[0];

            $this->max_point_array[$this->code[0]] = $maxValues[0];
        }

        $this->tip_id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->interval_of_life = $interval;
        $this->subscription_type = $subscription;


       if (!in_array('pv',$this->code) && !in_array('ep',$this->code)) {
           $this->emit('emptyEp');
           $this->emit('emptyPv');
       } else if(in_array('pv',$this->code) && in_array('ep',$this->code)){
                  if(count($this->code) > 1){
                      if($this->code[0] == 'pv'){
                          $this->emit('sliderUpdated','pv', $minValues[0]);
                          $this->emit('sliderUpdated','ep', $minValues[1]);
                      }else{
                          $this->emit('sliderUpdated','pv', $minValues[1]);
                          $this->emit('sliderUpdated','ep', $minValues[0]);
                      }
                  }
       }else if(in_array('pv',$this->code)) {
           if(count($this->code) > 1){
               $this->emit('emptyEp');
               if($this->code[0] == 'pv'){
                   $this->emit('sliderUpdated','pv', $minValues[0]);
               }else{
                   $this->emit('sliderUpdated','pv', $minValues[1]);
               }
           }else{
               $this->emit('sliderUpdated','pv', $minValues[0]);
           }
       } else if(in_array('ep',$this->code)) {
           $this->emit('emptyPv');
           if(count($this->code) > 1){
               if($this->code[0] == 'ep'){
                   $this->emit('sliderUpdated','ep', $minValues[0]);
               }else{
                   $this->emit('sliderUpdated','ep', $minValues[1]);
               }
           }else{
               $this->emit('sliderUpdated','ep', $minValues[0]);
           }
       }

        $this->emit('contentUpdated', $this->description);

    }

    public function emptyDailyTipValues()
    {

        $this->tip_id = '';
        $this->code = [];
        $this->title = '';
        $this->description = '';
    }

    public function updateTip()
    {

        try {
            if(!empty($this->code)){
                $codePoints = [];
                foreach ($this->code as $code){
                    if($code != 'ep' && $code != 'pv'){
                    $codePoints[$code] = ['min' => $this->min_point_array[$code],'max' => $this->max_point_array[$code]];
                    }else{
                        if($code == 'ep'){
                            $codePoints[$code] = ['min' => $this->ep,'max' => $this->ep];
                        }else if($code == 'pv'){
                            $codePoints[$code] = ['min' => $this->pv,'max' => $this->pv];
                        }
                    }
                }
                $this->code = $codePoints;
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
