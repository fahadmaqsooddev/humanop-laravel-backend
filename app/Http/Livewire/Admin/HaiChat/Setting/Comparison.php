<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Helpers\OpenRouterHelper;
use App\Models\HAIChai\HaiChatConversation;
use Livewire\Component;
use App\Models\HAIChai\LlmModel;
use App\Models\HAIChai\AnalyticsModel;
use App\Models\User;

class Comparison extends Component
{

    public $modelTypes = [];
    public $model_value;
    public $user_details;
    public $user_id;
    public $selectedModels = [];
    public $val = 2;
    public $maxVal = 4;
    public $user;
    public $message;
    public $modelResponse = [];

    protected $rules = [
        'message' => 'required|max:2000',
        'selectedModels' => 'required',
    ];

    protected $messages = [
        'selectedModels.required' => 'At least one model must be selected.',
//        'selectedModels.min' => 'At least one model must be selected.',
        'message.required' => 'The Message field is required.',
        'message.max' => 'Query cannot contain more than 2000 characters.',
    ];

    public function addMore()
    {
        if ($this->val < $this->maxVal) {

            $this->val++;
        }
    }

    public function submitForm()
    {

        $this->validate();

        $this->message = '';

        if (!empty($this->selectedModels)) {

            foreach ($this->selectedModels as $llmModel) {

                $openRouterResponse = OpenRouterHelper::callOpenRouterApi();

                if (!empty($openRouterResponse['choices'])) {

                    foreach ($openRouterResponse['choices'] as $choice) {

                        if (isset($choice['message']['content'])) {

                            $this->modelResponse[] = [
                                'model' => $openRouterResponse['model'],
                                'response' => $choice['message']['content']
                            ];

                        }

                    }

                }

            }

        }
    }

    public function render()
    {
        $this->modelTypes = LlmModel::GetModels()->toArray();

        $this->user_details = User::getUserDetailByIds();

        return view('livewire.admin.hai-chat.setting.comparison');
    }
}
