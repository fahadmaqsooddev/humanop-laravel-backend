<?php

namespace App\Http\Livewire\Admin\HaiChat\Setting;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\HaiChat\HaiChatHelpers;
use App\Models\Admin\Code\CodeDetail;
use App\Models\AssessmentColorCode;
use App\Models\HAIChai\Chatbot;
use App\Models\Assessment;
use App\Models\HAIChai\ChatbotKeyword;
use App\Models\HAIChai\HaiChat;
use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatConversation;
use App\Models\HAIChai\HaiChatSetting;
use App\Models\KnowledgeBase\KnowledgeBase;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class Conversation extends Component
{

    public $message, $conversations, $user_details, $user_id, $is_restricted_word = false, $disliked = 0,

        $editConversation = null, $updated_reply = null, $convo_id, $is_pine_cone = false;

    public $chatBot;

    protected $listeners = ['updateUserId'];

    protected $rules = [
        'message' => 'required|max:2000',
    ];

    protected $messages = [
        'message.required' => 'The Message field is required.',
        'message.max' => 'Query does not contain more than 2000 characters',
    ];

    public function mount(){

        $this->is_pine_cone = \request()->input('pine_cone_database', false);
    }

    public function submitForm()
    {
        try {

//            $this->validate();

//            $this->validate([
//                'message' => 'required|max:2000',
//            ],
//            [
//                'message.required' => 'The Message field is required.',
//                'message.max' => 'Query does not contain more than 2000 characters',
//            ]);


            $client = \OpenAI::client("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");

            $this->is_restricted_word = ChatbotKeyword::checkChatBotKeywords($this->chatBot->id, $this->message);

            if (!$this->is_restricted_word){

                $grid_info = [];

                if ($this->user_id){
//
////                    $user_grid = Assessment::getAssessmentFromUserId($this->user_id);

                    $latest_assessment = Assessment::getLatestAssessment($this->user_id);

                    if ($latest_assessment){

                        $assessment = Assessment::getAllRowGrid($latest_assessment->id);

                        $gridPublicNames = AssessmentColorCode::getAssessmentCodeAndNumber($latest_assessment->id);

//                        $traits = ['SA','MA','JO','LU','VEN','MER','SO'];
                        $traits = ['Regal','Energetic','Absorptive','Romantic','Sympathetic','Perceptive','Effervescent'];
//                        $drivers = ['DE','DOM','SP','FE','GRE','LUN','NAI','NE','POW','TRA','VAN','WIL'];
                        $drivers = ['Initiates Change','Creating Order','Compassion','Creates Protection','Monetary Discernment','Visionary',
                            'Optimistic','Humility','Accomplishment','The Traveler','Aesthetic Sensibility','Perseverance'];
//                        $energyCenter = ['EM','INS','INT','MOV'];
                        $energyCenter = ['Emotionally','Instinctually','Intellectually','Moving'];

                        arsort($gridPublicNames);
                        arsort($assessment['firstRow']);

                        $knowledge = KnowledgeBase::all();

                        $chunks = HaiChatHelpers::findRelevantChunksForGrid($gridPublicNames, $knowledge);

                        $gridChunks = array_column($chunks,'content');

                        $grid_info = [[
                            'role' => 'user',
//                        'content' => "If user ask something any key from ['SA' => 1, 'JO' => 2] then just return like that Your question {key name} value is {value}.",
//                            'content' => "The list of all traits are: {" . json_encode($traits) . "} and list of all drivers are: {" . json_encode($drivers) . "} and list of all energy centers are: {" . json_encode($energyCenter) .'} \n ' .
//                                "If user ask about their top traits \ drivers \ energy centers then answer from this according to their relevant categories: {" . json_encode($gridPublicNames) .
//                                "} \n If user ask any value of code then reply according to this : {" . json_encode($assessment['firstRow']) . "}. If code is not present in the top codes then
////                            said {code} is not available and if user ask from any n top {driver or trait or energy center}  and its not available then said {driver/trait/energy center} has not any nth top.",
//                            'content' => "The list of all traits are: {" . json_encode($traits) . "} and list of all drivers are: {" . json_encode($drivers) . "} and list of all energy centers are: {" . json_encode($energyCenter) .'} \n ' .
//                                "If user ask about their top {traits/drivers/energy centers} then answer from this according to their relevant categories with detailed overviews: {" . json_encode($gridPublicNames) .
//                                "}. \n If user ask any value of code then reply according to this : {" . json_encode($assessment['firstRow']) . "}. If code is not present in the top codes then
//                            said {code} is not available and if user ask from any n top {driver or trait or energy center}  and its not available then said {driver/trait/energy center} has not any nth top. \n
//                            If user ask for any top {traits/drivers/energy centers} the respond it with their description text:" . implode('\n', $gridChunks),
                        "content" => "The list of all traits are: {".json_encode($traits)."} \n list of all drivers are: {".json_encode($drivers)."} \n list of energy centers are: ".json_encode($energyCenter)." \n
                        User top {traits/drivers/energy centers} are: {".json_encode($gridPublicNames)."}. Answer should be in detail with their definition and description. For example: If user ask for their
                        top 2 traits then respond like. Your top 3 traits are
                        1. Regal :
//                        Description of the regal in 2 to 3 lines.
                        Detail description of that code.
                        2. Effervescent :
                        Detail description of that code. \n
                        If user ask any value of code then reply according to this : {" . json_encode($assessment['firstRow']) . "}. If code is not present in the top codes then
                        said {code} is not available and if user ask from any n top {driver or trait or energy center}  and its not available then said {driver/trait/energy center} has not any nth top."
                        ]];

                    }
                }

                if ($this->is_pine_cone){

                    $client = \OpenAI::client("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");

                    $response = $client->embeddings()->create([
                        'model' => 'text-embedding-3-small',
                        'input' => $this->message,
                    ]);

                    $response = $response->toArray();

//                    $pinecone = new \Probots\Pinecone\Client(env('PINECONE_API_KEY'),);

                    $pine_cone_ids = HaiChatActiveEmbedding::activeEmbeddingsPineConeId($this->chatBot->id, $this->is_pine_cone);

                    foreach ($response['data'] as $embedding){

//                        $response = $pinecone->data()->vectors()->query(
//                            vector:$embedding['embedding'],
//                            topK : $this->chatBot->chunks ?? 1,
//                            includeMetadata : true,
//                            filter : [
//                            'database_id' => ['$in' => $pine_cone_ids]
////                        'id' => ['$in' => ['vector_1']],
//                            ]
//                        );

                        $url = "https://my-index-wgj0px8.svc.aped-4627-b74a.pinecone.io/query"; // dev
//                        $url = "https://local-index-wgj0px8.svc.aped-4627-b74a.pinecone.io/query"; // local

                        $response = Http::withHeaders([
                            'Api-Key' => "pcsk_RvRK3_8wKwiqZAapNbMNhEpPZvP6nx9szRX3UtKv49VPX25L4VP7vt8MXsRs1C2Csx5xk",
                            'Content-Type' => 'application/json',
                        ])->post($url, [
                            'vector' => $embedding['embedding'],
                            'topK' => $this->chatBot->chunks ?? 1,
                            'includeMetadata' => true,
                            'filter' => [
                                'database_id' => ['$in' => $pine_cone_ids],
                            ]
                        ]);

//                        $result = $response->array();

                        if ($response->successful()){

                            $result = $response->json();

                            $chunks = array_filter($result['matches'] ?? [], function ($match) {
                                return $match['score'] >= 0.4;
                            });

                            $chunks = array_column($chunks,'metadata');

                            $chunks = array_column($chunks,'text');

                            $final_chunks = array_merge($chunks, $gridChunks ?? []);

                        }else{

                            session()->flash('error', $response->json());
                        }

                    }

                }else{

                    $knowledge = HaiChatActiveEmbedding::activeEmbeddings($this->chatBot->id, $this->is_pine_cone);

                    $chunks = HaiChatHelpers::findRelevantChunks($this->message, $knowledge, $this->chatBot->chunks);

                    $chunks = array_column($chunks,'content');

                    $final_chunks = array_merge($chunks, $gridChunks ?? []);

                }

                $messages = [
                    [
                        'role' => 'system',
                        'content' => $this->chatBot->prompt . ".Always provide answers in detailed HTML format. Use tags like <h6>, <p>, <ul>, <li>, and <strong> to structure the output. Ensure the response is informative and formatted correctly." . $this->chatBot->restriction,
                    ],
                    [
                        'role' => 'assistant',
//                        'content' => "Here is the related context understand it and answer in detail according to it : ". implode('\n',$chunks) .".",
//                        'content' => "Answer the question using this contents: {". implode('\n',$final_chunks) ."}. If user greets respond it casually.",
                        'content' => "Summarize the text delimited by < > \n <" . implode('\n',$final_chunks) . ">",
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->message . ".The answer must be in detailed HTML format using tags like <h6>, <p>, <ul>, and <li>.",
                    ]
                ];

                array_splice($messages, 1, 0, $grid_info);

                $reply = $client->chat()->create([
                    'model' => 'ft:gpt-4o-mini-2024-07-18:personal::AdxDqOYu',
                    'messages' => $messages,
                    'max_tokens' => $this->chatBot->max_tokens ?? 500,
                    'temperature' => $this->chatBot->temperature ?? 0.4,
                ]);

                if (isset($reply->toArray()['choices'][0]['message']['content'])){

//                    HaiChatConversation::deleteOldChat();

                    HaiChatConversation::createConversation($this->chatBot->id, $this->message,($reply->toArray()['choices'][0]['message']['content'] ?? null), $this->user_id);

                }

//                if (HaiChatSetting::GPT_4o_MINI === $this->chatBot->model_type){
//
//                    $body = ['query' => $this->message, 'temperature' => $this->chatBot['temperature'], 'max_tokens' => $['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'gpt-4o-mini','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked];
//
//                    $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/llm-gpt-model', $body);
//
//                }elseif(HaiChatSetting::GPT_4o === $setting->model_type){
//
//                    $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'gpt-4o','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked];
//
//                    $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/llm-gpt-model', $body);
//
//                }else{
//
//                    $body = ['query' => $this->message, 'temperature' => $setting['temperature'], 'max_tokens' => $setting['max_token'], 'file_name' => $activeChatAndEmbedding['file_name'], 'prompt_folder' => $this->name, 'total_chunks' => $setting['chunk'], 'gpt_model' => 'sonnet','user_grid' => $user_grid ?? [], 'dislike' => $this->disliked];
//
//                    $aiReply = $this->sendRequestFromGuzzle('post', 'http://18.234.162.68:8000/llm-model', $body);
//                }

//                HaiChatConversation::deleteOldChat();

//                HaiChatConversation::createConversation($this->name, $this->message,($reply->toArray()['choices'][0]['text'] ?? null), $this->user_id);

            }else{

                $conversationsArray = $this->conversations->toArray();

                $restrictedResponse = [
                    'reply' => $this->is_restricted_word ?? 'Your query contains restricted keywords. So, I am unable to response you about these.',
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

//    public function sendRequestFromGuzzle($method = null, $route_name = null, $body = [])
//    {
//
//        $authorization = Request::header('Authorization');
//
//        $queryArray = [
//            'headers' => ['Authorization' => $authorization],
//            'json' => $body
//        ];
//
//        $client = new Client(['http_errors' => false, 'timeout' => 180]);
//
//        $route = $route_name;
//
//        $response = $client->request($method, $route, $queryArray);
//
//        $response_body = json_decode($response->getBody()->getContents(), true);
//
//        return $response_body;
//    }

    public function getChatBotConversation()
    {
        $this->conversations = HaiChatConversation::getConversation($this->chatBot->id, $this->user_id);
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

            $filePath = public_path('lisa_question_answer_doc/Lisa Document.txt');

            $fileContent = file_get_contents($filePath);

            $newContent = $fileContent . "\n Question: $conversation->message \n Answer: $conversation->reply";

            file_put_contents($filePath, $newContent);


//            $text = $section->addText($request->get('number'),array('name'=>'Arial','size' => 20,'bold' => true));

//            $phpWord = \PhpOffice\PhpWord\IOFactory::load(public_path('lisa_question_answer_doc/Lisa Document.docx'), 'Word2007');
//
//            $section = $phpWord->addSection();
//
//            $section->addText("Question: $conversation->message");
//            $section->addText("Answer: $conversation->reply");
//
//            IOFactory::createWriter($phpWord, 'Word2007')->save(public_path('lisa_question_answer_doc/Lisa Document.docx'));


//            $app_env = env('APP_ENV');
//
//            $url = $app_env === 'staging' ? 'http://18.234.162.68:8000/qa_bucket' : 'http://44.201.128.253:8000/qa_bucket';
//
//            GuzzleHelpers::sendRequestFromGuzzle('post', $url, $body);

        }

    }

    public function dislikeReply($id){

        $last_convo = HaiChatConversation::where('chat_bot_id', $this->chatBot->id)

            ->where('user_id', $this->user_id)

            ->latest()->skip(1)->take(1)->get();

        $convo = HaiChatConversation::whereId($id)->first();

        $is_liked = $last_convo[0]->is_liked ?? null;

        if ($is_liked === 1){

            $convo->update(['is_liked' => 0]);

            $this->message = $convo->message;

            $this->emit('submitQuery');

            $this->disliked = 1;

        }elseif ($is_liked === 0){

            // update Client Query

            \App\Models\HAIChai\ClientQuery::createQuery($this->user_id, $convo->message, null, $convo->id);

            session()->flash('admin_conversation', 'Query submitted to Admin');

            $convo->update(['is_liked' => 2]);

        }elseif ($is_liked === 2){ // when last message query send to Lisa. Then 2nd dislike functionality repeats

            if ($is_liked === 2 && $convo->is_liked === 2){

                // do nothing
            }else{

                $convo->update(['is_liked' => 0]);

                $this->message = $convo->message;

                $this->emit('submitQuery');

                $this->disliked = 1;

            }

        }else{

            $convo->update(['is_liked' => 0]);

            $this->message = $convo->message;

            $this->emit('submitQuery');

            $this->disliked = 1;
        }

    }

    public function editHaiResponse($id){

        $this->convo_id = $id;
    }

    public function updateHaiReply(){

        $this->validate(['updated_reply' => 'required|max:100000'],
            ['updated_reply.required' => 'Reply is required']);

        HaiChatConversation::whereId($this->convo_id)->update(['reply' => $this->updated_reply]);

        $conversation_id = $this->convo_id;

        session()->flash('Hai Reply updated');

        $this->reset('convo_id','updated_reply');

        $this->emit('closeEditHaiReplyModal', $conversation_id);

    }


    public function render()
    {

        $this->is_restricted_word ? '' : $this->getChatBotConversation();

        $this->user_details = User::getUserDetailByIds();

        $this->emit('scrollToBottom');

        return view('livewire.admin.hai-chat.setting.conversation', ['conversation' => $this->conversations]);

    }
}
