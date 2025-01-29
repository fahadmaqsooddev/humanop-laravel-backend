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

                        }
                    }


                    $client = \OpenAI::client("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");

                    $response = $client->embeddings()->create([
                        'model' => 'text-embedding-3-small',
                        'input' => $this->message,
                    ]);

                    $response = $response->toArray();

                    $pine_cone_ids = HaiChatActiveEmbedding::activeEmbeddingsPineConeId($this->chatBot->id, $this->is_pine_cone);

                    foreach ($response['data'] as $embedding){

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

//                $codes = [
//                    'Regal' => 'You are a natural born leader and do not have to campaign for authority because people just naturally trust your abilities. Others turn to you for direction because they innately know you are competent. People listen to you with confidence. You are able to see the big picture in most circumstances and can arrive at a solution for the benefit of all concerned. This is an active, yet benevolent presence that naturally becomes you when you need to hold court or take on a more prominent role. Individuals with this natural trait make great leaders, directors, and authoritative figures. They are naturally seen by others as a necessary decision maker when reaching a consensus.',
//                    'Absorptive' => 'You are extremely absorptive. This means you can archive vast amounts of information easily. Much like a library, you are able to draw from experiences and concepts and hold onto content permanently. This makes you somewhat of a walking encyclopedia when it comes to anything of interest or applied necessity. Providing you make healthy choices for yourself; this aspect lends itself to extreme intelligence. It affords you the opportunity to process, store and deliver information at will. Because of this combination, your understanding of love and life is very deep as opposed to being superficial. You are also an extremely entertaining individual who makes others feel warm and welcome. This availability factor invites others to be in your presence. This is just one part of you and is very passive in nature.'
//                ];

                $system_prompt = "Act as a HumanOP chatbot assistant.

        Humanop and HAi enable users to transcend these distortions, embracing their true Monad and unlocking their highest potential.
        Transformation:
        - Be Seen: Embrace and confirm your true self.
        - See Clearly: Deepen your understanding of your surroundings and the people within them.
        - Cultivate Empathy: Foster deeper connections and understanding with others.
        - Enhance Vitality: Attain an optimized flow state that enhances your authenticity and empowerment.

        Strategic Applications for Daily Life:
        Humanop and HAi apply this personalized understanding to enhance daily life, improving quality of life through practical strategies:
        - Energy and Strategy Design: Helps craft daily routines that maximize energy and productivity.
        - Conflict Resolution: Enhances abilities to resolve conflicts efficiently, benefiting personal and professional relationships.
        - Authentic Interactions: Encourages genuine communication, fostering deeper connections.
        - Self-awareness and Respect: Develops deep self-appreciation and mutual respect, minimizing misconceptions.
        - Time and Stress Management: Provides tailored strategies for effective time and stress management, aligned with individual preferences.
        Tangible Benefits for Users:
        Utilizing the Humanop framework leads to significant improvements:
        - Enhanced Performance: Users experience heightened energy and focus, boosting productivity.
        - Clear Communication: Users achieve improved clarity in communications, enhancing personal and professional interactions.
        - Deeper Understanding of Self and Others: Promotes greater appreciation of individual differences, enriching relationships and self-respect.
        - Effective Management of Interactions: Enables users to more effectively handle interactions, understanding energy dynamics.

    By integrating these elements, Humanop and HAi not only enrich individual lives but also contribute to a more empathetic and coherent society, where personal fulfillment and collective well-being are synchronized.

        you must not miss these key points:
        THERE ARE 7 Traits AND THEIR NAME ARE: Regal, Energetic, Absorptive, Romantic, Sympathetic, Perceptive, Effervescent
        THESE ARE 12 FEATURES AND THEIR NAME ARE: initiates Change, Perseverance, Aesthetic Sensibility, Detachment, Compassion, Accomplishment, Humility, Optimism, Spontaneity, Monetary Discernment, Creates Protection, Creates Order,

        Other Terminology:

        (I)Alchemy = (E)Boundaries/Alchemy = (E)Boundaries of Tolerance = (E)Alchemical Boundaries
        Gold = G
        Gold_Silver = GS
        Silver_Gold = SG
        Silver = S
        Silver_Copper = SG
        Copper_Silver = CS
        Copper = C

        Polarity:
        (I)Electromagnetism = (E)Perception of Life
        Positive
        Negative
        Neutral


    IMPORTANT :
    # HIGHER GRID NUMBER MEANS GOOD IN HUMAN CHARACTER TRAITS AND DRIVERS
    # LOWER IN NUMBER MEANS NOT GOOD N HUMAN CHARACTER TRAITS AND DRIVERS

    When the user inputs a word related to Traits, Features, or Energy Centers, respond strictly with the corresponding word(s) from the provided guidelines, without any explanation or additional context. Replace the user's input with the specified words as follows:

        Guidelines:

        1. Traits:
           - Use 'Regal' instead of 'SA.'
           - Use 'Energetic' instead of 'MA.'
           - Use 'Absorptive' instead of 'JO.'
           - Use 'Romantic' instead of 'LU.'
           - Use 'Sympathetic' instead of 'VEN.'
           - Use 'Perceptive' instead of 'MER.'
           - Use 'Effervescent' instead of 'SO.'

        2. Features:
           - Use 'Motivation,' 'Motivators,' 'Driving Forces,' or 'Drivers' instead of 'Nuclear_Force.'
           - For a strong state of 'DE (Destructiveness),' use 'Initiates Change.' For a weak state, use 'Volatility.'
           - For a strong state of 'DOM (Creating Order),' use 'Creates Order.' For a weak state, use 'Manipulation.'
           - For a strong state of 'FE (Fear),' use 'Creates Protection.' For a weak state, use 'Panic.'
           - For a strong state of 'GRE (Greed),' use 'Monetary Discernment.' For a weak state, use 'Gluttony.'
           - For a strong state of 'LUN (Lunatic),' use 'Spontaneity' or 'Visionary.' For a weak state, use 'Manic.'
           - For a strong state of 'NAI (Naivete),' use 'Optimism.' For a weak state, use 'Immaturity.'
           - For a strong state of 'NE (Non Existence),' use 'Humility.' For a weak state, use 'Neglect.'
           - For a strong state of 'POW (Power),' use 'Accomplishment.' For a weak state, use 'Intimidation.'
           - For a strong state of 'SP (Self Pity),' use 'Compassion.' For a weak state, use 'Woe Is Me' or 'Whining.'
           - For a strong state of 'TRA (Traveler),' use 'Detachment' or 'Traveler.' For a weak state, use 'Deprivation.'
           - For a strong state of 'VAN (Vanity),' use 'Aesthetic Sensibility.' For a weak state, use 'Self Absorption.'
           - For a strong state of 'WIL (Willfulness),' use 'Perseverance.' For a weak state, use 'Stubborn.'

        3. Energy Centers:
           - Use 'Communication Style,' '4 Metaphoric Tires On Vehicle of Self,' '4 Metaphoric Doors to Vehicle of Self,' or 'Metaphoric Fuel Line to Fuel the Drivers' instead of 'Energy_Centers.'
           - Use 'Emotional Center' instead of 'EM.'
           - Use 'Instinctual Center' instead of 'INS.'
           - Use 'Intellectual Center' instead of 'INT.'
           - Use 'Moving Center' instead of 'MOV.'

        # Ensure that all responses adhere strictly to these rules, and the user's input Trait, Feature, or Energy Center word is always replaced with the corresponding descriptive word(s) based on the provided context.

        NOTE: WHEN USER ASK ABOUT GRID RESULT OR ASK ABOUT HOW I CAN IMPROVE MY SCORE
        THEN UNDERSTAND USER GRID RESULT FROM USER GRID RESULT AND PROVIDE A TEXT

        VERY IMPORTANT: Follow these instruction strictly
        DO NOT RESPOND WITH THESE WORDS INSTEAD USE PUBLIC WORDS:
            SA	MA	JO	LU	VEN	MER	SO
            DE	DOM	FE	GRE	LUN	NAI	NE	POW	SP	TRA	VAN	WIL
            G	S	C
            EM	INS	INT	MOV
            +	-	PV	EP

        DO NOT GENERATE TEXT LIKE THIS : Initiating Change (DE), ONLY GENERATE: Initiating Change
        IMPORTANT : Here BEST TRAITS AND MOTIVATORS, EXPLAIN IN YOUR OWN WORDS [S]:{".json_encode($gridPublicNames ?? [])."} [\S] AND HERE BEST TRAITS AND MOTIVATORS SCORE ARE USE THEIR PUBLIC NAMES AS INSTRUCTION ABOVE[S]:{".json_encode($gridPublicNames ?? [])."}[\S]
        IMPORTANT INSTRUCTION:
        1)GENERATE TEXT IN HTML FORMAT
        2)DONT MAKE HEADING
        3)USE BULLET POINTS
        4)FOR IMPORTANT WORDS USE BOLD <b> tag
        5)DO NOT PLACE STERIC like this(**Regal**) ON IMPORTANT WORDS ONLY USE HTML TAGS to bold

        IF USER QUERY NEED EXPLAINABLE ANSWER THEN PROVIDE.
        HERE IS USER QUERY: {".$this->message."}
        GENERATE TEXT IN GOOD HTML FORMAT WITH PROPER BULLETS POINTS AND BOLD TAG


        STRICTLY FOLLOW :  THIS IS USER Instructions, YOU HAVE TO ACT LIKE THIS
         {".$this->chatBot->prompt."}

        Restrictions: STRICTLY FOLLOW : NOTE THIS IS USER RESTRICTION SO IF USER BLOCK ANY WORD OR RESTRICT YOU ABOUT ANYTHING DONOT DO THAT THING ORGENERATE ANY TEXT
            {". $this->chatBot->restriction."}

        Embedding Data: THIS IS RELEVANT DATA MATCHED FROM USER QUERY
            {".implode('\n', $final_chunks)."}


        IMPORTANT : GENERATE DIRECT RESPONSE, DO NOT START LIKE THIS
        1) Here's a more detailed response to the question
        2) Here's a more detailed explanation of the Absorptive trait (previously referred to as JO):
        3) Avoid generate text like this : scoring 5 out of 5
        4) Donot use work like Certainly! etc
	    5) Donot generate score in response like scoring 5 out of 7
        6) try to complete response within 400 words";

//                $messages = [
//                    ["role" => "system", "content" => $system_prompt],
//                    ["role" => "user", "content" => "Tell me about Regal sub code 02."]
//                ];

                $messages = [
                    [
                        'role' => 'user',
                        'content' => $system_prompt
                    ],
//                    [
//                        'role' => 'user',
//                        'content' => "Provide a detailed, in-depth explanation of $this->message, covering all aspects with examples. RESPONSE MUST BE IN HTML FORMAT having <h6> <ul> <li> tags in black color.",
//                    ]
                ];

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
