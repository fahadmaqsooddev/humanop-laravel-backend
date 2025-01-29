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

                if ($this->is_pine_cone){

                    $client = \OpenAI::client("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");

                    $response = $client->embeddings()->create([
                        'model' => 'text-embedding-3-small',
                        'input' => $this->message,
                    ]);

                    $response = $response->toArray();

                    $pine_cone_ids = HaiChatActiveEmbedding::activeEmbeddingsPineConeId($this->chatBot->id, $this->is_pine_cone);

                    foreach ($response['data'] as $embedding){

//                        $url = "https://my-index-wgj0px8.svc.aped-4627-b74a.pinecone.io/query"; // dev
                        $url = "https://local-index-wgj0px8.svc.aped-4627-b74a.pinecone.io/query"; // local

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
                        'content' => "Act as a HumanOP chatbot assistant.
                        Follow some instructions while generating the response:
                        #GENERATE TEXT IN HTML FORMAT
                        #DONT MAKE HEADING
                        #USE BULLET POINTS
                        #FOR IMPORTANT WORDS USE BOLD <b> tag
                        #DO NOT PLACE STERIC like this(**Regal**) ON IMPORTANT WORDS ONLY USE HTML TAGS to bold.
                        AND Do not follow these INSTRUCTIONS:
                        # Here's a more detailed response to the question
                        # Here's a more detailed explanation of the Absorptive trait (previously referred to as JO):
                        # Avoid generate text like this : scoring 5 out of 5
                        # Donot use work like Certainly! etc.

                        1)VERY IMPORTANT:
                        # THESE ARE REFERENCE DOCUMENTS related to QUERY but you have to respond with complete information [S]:{". implode('\n', $final_chunks) ."}[/S]
                        # THESE ARE REFERENCE CODES WITH DESCRIPTIONS if user ask any code with description then respond from this content and replace code with their coresponding public names from guidlines [S]:{{'sa': {
            'Description': 'Visualize: Solutions to benefit all. See an issue from multiple angles before acting
                Reflection: This allows you to tap into your natural ability to see the big picture and envision solutions. Observe how your actions and the actions of others affect team morale
                Lead/Guide/Mentor: Lead a work or community project or event/Oversee a gathering/Mentor a colleague or student
                Facilitate a Brainstorming Session: With team or family…to get all options “on the table”...look at issues from multiple angles before making decisions
                Support Others to See Big Picture: Help colleagues, friends, or family members find solutions. Propose ideas that create win-win situations. Share your solutions by painting the picture of benefits for all.
                                                                                                                                                                                                                          Relationship with Respect/Disrespect: Recognize, appreciate and validate when others show respect (in certain moments).  Communicate your expectations clearly and respectfully. Address any perceived disrespect promptly and diplomatically
                Delegation:  Identify tasks that can be delegated to others. Empower team members or family members by assigning them responsibilities. Provide clear instructions and support for delegated tasks
                                                                                                                                                                                                   Activate Your Natural Benevolent Authority: Practice maintaining a confident and open posture. Speak clearly and thoughtfully, allowing for moments of reflection. Show genuine interest in others' perspectives and ideas. Consult with others to gather diverse viewpoints.  Practice active listening in every conversation. Ask follow-up questions to show engagement.  Summarize what others have said before responding
                Ponder Solutions to Benefit All: Explain your process when you're taking time to ponder and see solutions…to avoid being seen as indecisive'
            },}}[/S]

                        When the user inputs a word related to Traits, Features, or Energy Centers, respond strictly with the corresponding word(s) from the provided guidelines, with explanation or additional context. Replace the user's input with the specified words as follows:
                    Guidelines:
                    1. Traits:
                      - Use 'Regal' instead of 'SA'
                      - Use 'Energetic' instead of 'MA'
                      - Use 'Absorptive' instead of 'JO' or 'jo'
                      - Use 'Romantic' instead of 'LU' or 'lu'
                      - Use 'Sympathetic' instead of 'VEN' or 'ven
                      - Use 'Perceptive' instead of 'MER' or 'mer'
                      - Use 'Effervescent' instead of 'SO' or 'so'",
                    ],
                    [
                        'role' => 'assistant',
                        'content' => 'Act as a Human Op assistant',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Provide a detailed, in-depth explanation of $this->message, covering all aspects with examples.",
                    ]
                ];

                $reply = $client->chat()->create([
                    'model' => 'ft:gpt-4o-mini-2024-07-18:personal::AdxDqOYu',
                    'messages' => $messages,
                    'max_tokens' => 3000,//$this->chatBot->max_tokens ?? 500,
                    'temperature' => 0.7,//$this->chatBot->temperature ?? 0.4,
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
