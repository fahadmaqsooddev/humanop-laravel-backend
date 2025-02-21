<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Helpers\OpenRouterHelper;
use Livewire\Component;
use App\Models\HAIChai\LlmModel;

class Comparison extends Component
{

    public $modelTypes = [];
    public $model_value;
    public $selectedModels = [];
    public $val = 2;
    public $maxVal = 4;
    public $message;
    public $modelResponse = [];

    protected $rules = [
        'message' => 'required|max:2000',
        'selectedModels' => 'required',
    ];

    protected $messages = [
        'selectedModels.required' => 'At least one model must be selected.',
        'message.required' => 'The Message field is required.',
        'message.max' => 'Query cannot contain more than 2000 characters.',
    ];

    public function addMore()
    {
        if ($this->val < $this->maxVal) {

            $this->val++;
        }
    }
    public function refreshComponent()
    {

      $this->model_value = "";
      $this->modelResponse = [];
       $this->val = 2;
       $this->selectedModels = [];
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

                            $selectedModel = ['Deepseek' => 'deepseek/deepseek-chat', 'Qwen' => 'qwen/qvq-72b-preview', 'Deepseek R1-Qwen' => 'deepseek/deepseek-r1-distill-qwen-1.5b', 'OpenAI' => 'openai/gpt-3.5-turbo'];

                            $modelKey = array_search($openRouterResponse['model'], $selectedModel, true);

                            $this->modelResponse[] = [
                                'model' => $modelKey !== false ? $modelKey : $openRouterResponse['model'],
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

        return view('livewire.admin.hai-chat.setting.comparison');
    }
}
