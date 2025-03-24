<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\OpenRouterHelper;
use App\Models\Admin\FineTuneContent\FineTuneContent;
use App\Models\HAIChai\AnalyticsModel;
use App\Models\HAIChai\Chatbot;
use App\Models\Assessment;
use App\Models\HAIChai\ChatbotKeyword;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatConversation;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\HAIChai\LlmModel;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Conversation extends Component
{

    public $message, $name, $conversations,$user_details,$user_id, $is_restricted_word = false, $disliked = 0,

        $editConversation = null, $updated_reply = null, $convo_id, $is_pine_cone = false;

    protected $listeners = ['updateUserId'];

    protected $rules = [
        'message' => 'required|max:2000',
    ];

    protected $messages = [
        'message.required' => 'The Message field is required.',
        'message.max' => 'Query does not contain more than 2000 characters',
    ];

//    public function mount(){
//
//
//
//    }

    public function submitForm()
    {
        try {

            $chat_bot_id = Chatbot::getChatFromVendorName($this->name)->id ?? null;

            $setting = HaiChatSetting::getHaiChatSetting($chat_bot_id);

            $selectedModel = LlmModel::getSelectedModel($setting['model_type']);

            $activeChatAndEmbedding = HaiChatActiveEmbedding::getChatActiveEmbedding($this->name);

            $this->is_restricted_word = ChatbotKeyword::checkChatBotKeywords($this->name, $this->message);

            if (!$this->is_restricted_word){

                if ($this->user_id){

                    $user_grid = Assessment::getAssessmentFromUserId($this->user_id);
                }

//                if (HaiChatSetting::GPT_4o_MINI === $setting->model_type){
//
//                    $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'gpt-4o-mini','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked];
//
//                    $aiReply = $this->sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/llm-gpt-model', $body);
//
//                }elseif(HaiChatSetting::GPT_4o === $setting->model_type){
//
//                    $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'gpt-4o','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked];
//
//                    $aiReply = $this->sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/llm-gpt-model', $body);
//
//                }elseif(HaiChatSetting::GPT_4o_FINE_TUNED === $setting->model_type){
//
//                    $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'ft:gpt-4o-mini-2024-07-18:personal::AdxDqOYu','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked];
//
//                    $aiReply = $this->sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/llm-gpt-model', $body);
//
//                }else{
//
//                    $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'sonnet','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked];
//
//                    $aiReply = $this->sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/llm-model', $body);
//                }

                $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

                $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'sonnet','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked, 'loc' => $subFolder];

                $aiReply = $this->sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/llm-model', $body);

                dd($body, $aiReply);

                $openRouterResponse = OpenRouterHelper::callOpenRouterApi($this->message, $setting, $aiReply, $selectedModel['model_value']);

                foreach ($openRouterResponse['choices'] as $choice)
                {

                    HaiChatConversation::createConversation($this->name, $this->message,$choice['message']['content'], $this->user_id);

                }

                AnalyticsModel::createAnalytics($this->message, $setting->model_type, $openRouterResponse['usage']);

            }else{

                $conversationsArray = $this->conversations->toArray();

                $restrictedResponse = [
                    'reply' => $this->is_restricted_word ?? 'Your query contains restricted keywords. So, I am unalble to response you about these.',
                    'message' => $this->message,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];

                if (count($conversationsArray) > 0){

                    $final = array_merge($conversationsArray,[$restrictedResponse]);

                    $this->conversations = collect($final);

                }else{

                    $this->conversations = collect([$restrictedResponse]);
                }
            }

            $this->disliked = 0;

            $this->message = '';

        } catch (\Exception $exception) {

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

    public function getChatBotConversation()
    {
        $this->conversations = HaiChatConversation::getConversation($this->name, $this->user_id);
    }

    public function updateUserId($id){

        if ($id > 0){

            $this->user_id = $this->user_details[$id-1]->id ?? null;

        }else{

            $this->user_id = null;
        }


        $this->is_restricted_word = false;
    }

    public function likeReply($id){

        $conversation = HaiChatConversation::singleConversation($id);

        if ($conversation){

            $body = [
                'question' => $conversation->message ?? null,
                'answer' => strip_tags($conversation->reply ?? null),
            ];

            FineTuneContent::addLisaApprovedQuestionAnswers($body);

            $app_env = env('APP_ENV');

            $url = $app_env === 'staging' ? 'http://44.201.128.253:8000/qa_bucket' : 'http://44.201.128.253:8000/qa_bucket';

            GuzzleHelpers::sendRequestFromGuzzle('post', $url, $body);

        }

    }

    public function dislikeReply($id){

//        $last_convo = HaiChatConversation::where('chatbot', $this->name)
//
//            ->where('user_id', $this->user_id)
//
//            ->latest()->skip(1)->take(1)->get();
//
//        $convo = HaiChatConversation::whereId($id)->first();
//
//        $is_liked = $last_convo[0]->is_liked ?? null;
//
//        if ($is_liked === 1){
//
//            $convo->update(['is_liked' => 0]);
//
//            $this->message = $convo->message;
//
//            $this->emit('submitQuery');
//
//            $this->disliked = 1;
//
//        }elseif ($is_liked === 0){ // second dislike
//
//            // update Client Query
//
////            \App\Models\HAIChai\ClientQuery::createQuery($this->user_id, $convo->message, null, $convo->id);
//
////            session()->flash('admin_conversation', 'Query submitted to Admin');
//
//            $convo->update(['is_liked' => 2]);
//
//        }elseif ($is_liked === 2){ // when last message query send to Lisa. Then 2nd dislike functionality repeats
//
//            if ($is_liked === 2 && $convo->is_liked === 2){
//
//                // do nothing
//            }else{
//
//                $convo->update(['is_liked' => 0]);
//
//                $this->message = $convo->message;
//
//                $this->emit('submitQuery');
//
//                $this->disliked = 1;
//
//            }
//
//        }else{
//
//            $convo->update(['is_liked' => 0]);
//
//            $this->message = $convo->message;
//
//            $this->emit('submitQuery');
//
//            $this->disliked = 1;
//        }

//        $last_convo = HaiChatConversation::where('chatbot', $this->name)
//
//            ->where('user_id', $this->user_id)
//
//            ->latest()->skip(1)->take(1)->get();

        $convo = HaiChatConversation::whereId($id)->first();

//        $is_liked = $last_convo[0]->is_liked ?? null;

        $convo->update(['is_liked' => 2]);

        $this->message = $convo->message;

        $this->emit('submitQuery');

        $this->disliked = 1;

    }



public function editHaiResponse($id)
{
    $this->reset('updated_reply');
    $this->convo_id = $id;
    $data = HaiChatConversation::where('id', $this->convo_id)->first();

    $this->updated_reply = $data['reply'];

    // First load CKEditor if not already loaded
    $this->dispatchBrowserEvent('livewire:load');

    // Give a small delay to ensure CKEditor is initialized
    $this->dispatchBrowserEvent('updateEditorContent', [
        'content' => $this->updated_reply,
        'id' => $id
    ]);
}

    public function updateHaiReply(){

//        $this->validate(['updated_reply' => 'required|max:100000'],
//            ['updated_reply.required' => 'Reply is required']);

        $conversation = HaiChatConversation::whereId($this->convo_id)->first();

        if ($conversation){

            $conversation->update(['reply' => $this->updated_reply]);

            $data = [
                'question' => $conversation->message,
                'answer' => strip_tags($conversation->reply),
            ];

            FineTuneContent::addLisaApprovedQuestionAnswers($data);

        }

        $conversation_id = $this->convo_id;

        session()->flash('Hai Reply updated');

        $this->reset('convo_id','updated_reply');

        $this->emit('closeEditHaiReplyModal', $conversation_id);

    }


    public function render()
    {

        $this->user_details = User::getUserDetailByIds();

        $this->is_restricted_word ? '' : $this->getChatBotConversation();

        $this->emit('scrollToBottom');


        // $this->dispatchBrowserEvent('livewire:load');

        return view('livewire.admin.hai-chat.setting.conversation', ['conversation' => $this->conversations]);

    }
}
