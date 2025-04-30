<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Helpers\LearningCluster\LearningClusterHelpers;
use App\Helpers\OpenRouterHelper;
use App\Models\Admin\FineTuneContent\FineTuneContent;
use App\Models\HAIChai\AnalyticsModel;
use App\Models\HAIChai\BrainCluster;
use App\Models\HAIChai\Chatbot;
use App\Models\Assessment;
use App\Models\HAIChai\ChatbotKeyword;
use App\Models\HAIChai\ChatPrompt;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatConversation;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\HAIChai\LlmModel;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Conversation extends Component
{

    public $message, $name, $conversations,$user_details,$user_id, $is_restricted_word = false, $disliked = 0,

        $editConversation = null, $updated_reply = null, $convo_id;

    protected $listeners = ['updateUserId','updateChatBotId','viewEditPersona'];

    protected $rules = [
        'message' => 'required|max:2000',
        'name' => 'required',
    ];

    protected $messages = [
        'message.required' => 'The Message field is required.',
        'message.max' => 'Query does not contain more than 2000 characters',
        'name.required' => 'Select chat-bot first',
    ];

//    public function mount($name){
//
//        $this->chat_bot_id = Chatbot::where('name', $name)->first()->id ?? null;
//
//        $this->name = $name;
//
//    }

    public function updateChatBotId($value){

        $this->chat_bot_id = $value;

        if ($this->chat_bot_id){

            $chatBotName = Chatbot::whereId($this->chat_bot_id)->first()->name;

            if ($chatBotName){

                $this->name = $chatBotName;

                $this->user_details = User::getUserDetailByIds();

                $this->is_restricted_word ? '' : $this->getChatBotConversation();

                $this->emit('scrollToBottom');

            }

        }

    }

    public function viewEditPersona($id = null){

        $chat_bot_id = HaiChatSetting::whereId($id)->first()->chat_bot_id ?? null;

        if ($chat_bot_id) {

            $this->name = Chatbot::whereId($chat_bot_id)->first()?->name;

        }else{

            $this->reset('name');
        }
    }

    public function submitForm()
    {
        try {

            $this->validate();

            $chat_bot_id = Chatbot::getChatFromVendorName($this->name)->id ?? null;

            $prompts = ChatPrompt::where('name',$this->name)->first();

            $setting = HaiChatSetting::getHaiChatSetting($chat_bot_id);

            $selectedModel = LlmModel::getSelectedModel($setting['model_type']);

            $activeChatAndEmbedding = BrainCluster::connectedClusterEmbeddingIds($chat_bot_id);

            $this->is_restricted_word = ChatbotKeyword::checkChatBotKeywords($this->name, $this->message);

            if (!$this->is_restricted_word){

                if ($this->user_id){

                    $user_grid = Assessment::getAssessmentFromUserId($this->user_id);

                    $user_name = User::userNameForHAi($this->user_id);
                }

                $subFolder = env("APP_ENV") === 'local' || env("APP_ENV") === 'development' ? 'dev' : env("APP_ENV");

                $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'sonnet','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked, 'loc' => $subFolder, 'user_name' => $user_name ?? "null", 'user_id' => (int)$this->user_id];

                if ($setting && $setting['model_type'] === 5){

                    $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'temp-llm-model', $body);

                    Log::info(['ai Reply' => $aiReply]);

                    $llm_prompt = OpenRouterHelper::addUserDetailsIntoPrompt($this->user_id, $aiReply['combined_output']);

                    $authorization = \request()->header('Authorization');

                    $queryArray = [
                        'headers' => ['Authorization' => $authorization]
                    ];

                    $client = new Client(['http_errors' => false, 'timeout' => 180]);

                    $route = "ec2-34-233-15-190.compute-1.amazonaws.com/bedrock/bedrock.php?persona=" . $prompts['prompt'] . "&prompt=". $llm_prompt ."&query=" . $this->message;

                    $response = $client->request("get", $route, $queryArray);

                    Log::info(['bedrock body response' => $response->getBody()]);

                    if ($response->getStatusCode() === 200){

                        $reply = $response->getBody()->getContents();

                        Log::info(['bedrock response' => $reply]);

                        HaiChatConversation::createConversation($this->name, $this->message,$reply, $this->user_id);

                    }else{

                        session()->flash("error", "Try again.");
                    }


                }else{

                    $aiReply = GuzzleHelpers::sendRequestFromGuzzle('post', 'llm-model', $body);

                    Log::info(['ai Reply' => $aiReply]);

                    $prompt = OpenRouterHelper::addUserDetailsIntoPrompt($this->user_id, $aiReply['prompt']);

                    $promptMessages = self::makePromptForChat($aiReply, $prompts);

//                $aiReply = $this->sendRequestFromGuzzle('post', 'http://54.227.7.149:8000/llm-model', $body);

                    $openRouterResponse = OpenRouterHelper::callOpenRouterApiWithHistory($setting, $selectedModel['model_value'], $promptMessages);

                    // $openRouterResponse = OpenRouterHelper::callOpenRouterApi($this->message, $setting, $prompt, $selectedModel['model_value'], $prompts['prompt'] ?? null);

                    foreach ($openRouterResponse['choices'] as $choice)
                    {

                        $reply = OpenRouterHelper::removeIrregularHtmlSyntax($choice['message']['content']);

                        HaiChatConversation::createConversation($this->name, $this->message,$reply, $this->user_id);
//                HaiChatConversation::createConversation($this->name, $this->message,$aiReply['response'], $this->user_id);

                    }

                    AnalyticsModel::createAnalytics($this->message, $setting->model_type, $openRouterResponse['usage']);

                }

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

            LearningClusterHelpers::updateLearningCluster($this->name, $body['question'],$body['answer'],'Like');

//            $app_env = env('APP_ENV');
//
//            $url = $app_env === 'staging' ? 'http://54.227.7.149:8000/qa_bucket' : 'http://54.227.7.149:8000/qa_bucket';

            GuzzleHelpers::sendRequestFromGuzzle('post', 'qa_bucket', $body);

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

        LearningClusterHelpers::updateLearningCluster($this->name, $convo->message, $convo->reply,'Dislike');

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

            LearningClusterHelpers::updateLearningCluster($this->name, $data['question'],$data['answer'],'Edited');

        }

        $conversation_id = $this->convo_id;

        session()->flash('Hai Reply updated');

        $this->reset('convo_id','updated_reply');

        $this->emit('closeEditHaiReplyModal', $conversation_id);

    }

    public function makePromptForChat($aiReply = [], $prompts = null){

        $history = HaiChatConversation::when($this->user_id, function ($query, $user_id){

            $query->where('user_id', $user_id);

        })->limit(5)->latest()->get()->reverse();

        $formattedHistory = $history->flatMap(function ($message){

            return [
                ['role' => 'user', 'content' => $message['message']],
                ['role' => 'assistant', 'content' => $message['reply']]
            ];

        })->toArray();

        $formattedRagContext = "--- Relevant Information ---\n" . $aiReply['prompt'] . "\n--- End Information ---";

        $promptMessages = [];

        if (isset($prompts['prompt']) || isset($prompts['restriction'])){

            $promptMessages[] = ['role' => 'system', 'content' => ($prompts['prompt'] ?? null) . "\n**Restrictions:**\n" . ($prompts['restriction'] ?? null)];
        }

        $promptMessages = array_merge($promptMessages, $formattedHistory);

        $finalUserMessageContent = $this->message;

        if (isset($aiReply['prompt'])) {
            $finalUserMessageContent = $formattedRagContext . "\n\n Based on the above information and our conversation history, please answer the following:\n" . $this->message;
        }

        $promptMessages[] = ['role' => 'user', 'content' => $finalUserMessageContent];

        return $promptMessages;

    }


    public function render()
    {
        // $this->dispatchBrowserEvent('livewire:load');

        $this->user_details = User::getUserDetailByIds();

        $this->is_restricted_word ? '' : $this->getChatBotConversation();

        $this->emit('scrollToBottom');

        return view('livewire.admin.hai-chat.setting.conversation', ['conversation' => $this->conversations]);

    }
}
