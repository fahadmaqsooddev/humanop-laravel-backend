<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Models\HAIChai\ChatPrompt;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use GuzzleHttp\Client;
class Prompt extends Component
{
    public $prompt,$restriction;
    public $name;
    protected $rules = [
        'name' => 'required',
        'prompt' => 'required',
        'restriction' => 'required',
    ];

    protected $messages = [
        'name.required' => 'Something went during updating prompt.',
        'prompt.required' => 'Prompt Field is required.',
        'restriction.required' => 'Restriction Field is required.',
    ];

    public function mount($name)
    {
        $this->name = $name;
        $detail= ChatPrompt::singlePromptByName($this->name);
        if($detail){
            $this->prompt = $detail['prompt'];
            $this->restriction = $detail['restriction'];
        }
    }
    public function update(){
        try {
            $this->validate();
            $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/update-prompt', ['vendor_name' => $this->name,'base_data' => $this->prompt,'restriction_data' => $this->restriction]);
          if($aiReply > 0) {
              $prompt = ChatPrompt::createUpdatePrompt($this->name, $this->prompt, $this->restriction);
              if ($prompt) {
                  session()->flash('success', "Updated Successfully.");
              }
          }else{
              session()->flash('error', "Something went wrong.");
          }
        }catch (\Exception $exception)
        {
            session()->flash('error', $exception->getMessage());
        }
    }
    public function sendRequestFromGuzzle($method = null, $route_name = null, $body = [])
    {

        $authorization = Request::header('Authorization');

        $queryArray = [
            'headers' => ['Authorization' => $authorization],
            'json' => $body
        ];

        $client = new Client(['http_errors' => false, 'timeout' => 180]);

        $route = $route_name;

        $response = $client->request($method, $route, $queryArray);

        $response_body = json_decode($response->getBody()->getContents(), true);

        return $response_body;
    }
    public function render()
    {
        return view('livewire.admin.hai-chat.setting.prompt');
    }
}
