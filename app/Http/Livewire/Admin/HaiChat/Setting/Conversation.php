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



    Answer the user's question using only the information from the provided documents. Follow these rules strictly:

    1. Provide only the direct answer, without any additional text, code, or quotation marks.

    2. Do not include phrases like 'Here's the answer:' or 'The response is:' or 'Ans:'.

    3. IMPORTANT: MAKE CHAT MORE CONVERSATIONAL AND ATTRACTIVE.

    4. If you don't have enough information to generate an answer, then you can say 'I don't have enough information related to this.'



  ALL IMPORTANT INFORMATION RELATED TO TRAITS:[S] {'sa': {
            'Description': 'Visualize: Solutions to benefit all. See an issue from multiple angles before acting
                Reflection: This allows you to tap into your natural ability to see the big picture and envision solutions. Observe how your actions and the actions of others affect team morale
                Lead/Guide/Mentor: Lead a work or community project or event/Oversee a gathering/Mentor a colleague or student
                Facilitate a Brainstorming Session: With team or family…to get all options “on the table”...look at issues from multiple angles before making decisions
                Support Others to See Big Picture: Help colleagues, friends, or family members find solutions. Propose ideas that create win-win situations. Share your solutions by painting the picture of benefits for all.
                                                                                                                                                                                                                          Relationship with Respect/Disrespect: Recognize, appreciate and validate when others show respect (in certain moments).  Communicate your expectations clearly and respectfully. Address any perceived disrespect promptly and diplomatically
                Delegation:  Identify tasks that can be delegated to others. Empower team members or family members by assigning them responsibilities. Provide clear instructions and support for delegated tasks
                                                                                                                                                                                                   Activate Your Natural Benevolent Authority: Practice maintaining a confident and open posture. Speak clearly and thoughtfully, allowing for moments of reflection. Show genuine interest in others' perspectives and ideas. Consult with others to gather diverse viewpoints.  Practice active listening in every conversation. Ask follow-up questions to show engagement.  Summarize what others have said before responding
                Ponder Solutions to Benefit All: Explain your process when you're taking time to ponder and see solutions…to avoid being seen as indecisive'
            },

    'ma': {
            'Description': 'Diplomatic Actions when confronting challenges (particularly during this time of day):
                Count to three before responding in discussions
                Practice putting yourself in others' shoes before reacting
                Ask for input from a trusted source before making major decisions abruptly
                Choose the most diplomatic approach, even if it's not the most direct
                Seek a mediator for potentially contentious discussions
                    Activate Effective Time Management during this time of day:
                Use a time-blocking technique for your tasks
                                                  Prioritize your to-do list based on importance and urgency
                Evaluate your productivity at the end of the day
                Take short, frequent breaks to maintain high energy levels
                Practice deep breathing exercises throughout the day
                Find physical outlets for excess energy
                                          Pay Close Attention to how you Handle Disagreements during this time of day:
                Practice active listening during conflicts
                Use 'and' instead of 'but' when discussing different viewpoints
                Create an intention to seek common ground in all disagreements
                Engage in a calming activity when you feel tension rising
                Optimize your natural directness by... balancing it with tact during this time of day:
                Count to ten when feeling frustrated
                Rehearse important conversations beforehand
                Practice using softer language when giving direction or when receiving criticism
                Ask for feedback on your approach from a mentor
                Communicate to others that your “directness” or abrupt response in simply you wanting to “get the job done”
                Apologize sincerely if it’s been revealed that you’ve offended someone
                Fix what’s not working without encroaching on others time or space
                Let your integrity be your guide (particularly during this time of day):
                Align your actions with your stated values
                Recognize and praise ethical behavior in others
                Stand up for what’s right without allowing frustration or anger to lead. Remain objective in your need to align with what’s true for you
                                                                                                                                                     Reinforce ethical guidelines in your workplace or in your community
                Celebrate any milestones that can be acknowledged
                Admit and take responsibility for any mistakes
                                                  Because you're naturally On Your Way from A to B…Pause (Particularly During this Time of Day)... And:
                Schedule a coffee break with a colleague
                Show interest in a coworker's personal project
                Offer help to someone complete something without expecting anything in return'
            },

    'jo': {
            'Description': 'Because you have a wealth of archived information (and common-sense solutions)...During This Time of Day:
                Intend to actively listen before sharing with others
                Focus on understanding another's perspective fully before offering your insights
                Practice letting go of the need to always be right. Acknowledge that your perspective might not be the only valid one.
                Focus on sharing experiences rather than just facts. Personal stories can be more engaging and less likely to come across as lecturing
                Use humor when sharing information. This can make your insights more approachable and less intimidating
                Because you are Naturally Empathetic…Exhibit Empowered Empathy (particularly during this time of day):
                When supporting others, think about yourself standing at the edge of their emotional waters, throwing a life preserver of wisdom and support, rather than jumping in and risking drowning in their pain.
                Practice recognizing where your feelings end and another's begins. Attaching yourself to another's pain is not of benefit to them or to you.
                Gauge when it's appropriate to share your knowledge and when it's better to hold back, based on the emotional or mental state of another.
                Because You're Naturally Cheerful and Inviting:
                Use this joyful aspect of your nature to lift the spirits of others
                Tell stories (when appropriate)
                Because You’re Naturally Generous:
                Be mindful of what you offer to others in terms of assistance (this can include your time, energy ..and even your money)
                Pay attention to when your natural giving nature begins to sacrifice yourself for others
                                                                                                  Because You’re Naturally Collaborative:
                Find ways to collaborate with others on plans, projects or creations (particularly during this time of day)'
            },

    'lu': {

            'Description': 'Incorporate solitude into your work & home environment During This Time of Day:
                Select (or Create) a private workspace (that feels comfortable) to get your work done
                Personalize your workspace (or homespace) with meaningful objects or calming elements that help you feel connected and comfortable in your environment.
                Use noise-canceling headphones to simulate a sense of solitude (even in shared spaces)
                Use room dividers or reposition your desk or workspace (if need be to create more privacy)
                Soft background music can also minimize distractions
                Schedule dedicated blocks of uninterrupted work time during this time of day
                Communicate 'do not disturb' periods to colleagues, friends or family members and use status indicators on messaging apps, during this time of day
                Utilize asynchronous communication methods like email or messaging apps to maintain relationships without constant real-time interaction during this time of day.
                    Engage in 'parallel play' with others during this time of day. This involves being in the same space as others while each person focuses on their own task, providing a middle ground between complete solitude and active socializing.
                    Communicate with the people in your life your need for solitude (particularly during this time of day). This can help reduce social pressure and create a supportive network that aligns with your introspective nature
                Your Romantic Trait is naturally productive and problem-oriented:
                Set aside time to systematically address problems during this time of day when you're in this most productive state
                Because you take assignments seriously and execute thoroughly, break large projects into smaller, manageable tasks during this time of day. This allows you to focus deeply on each component without feeling overwhelmed
                Projects and tasks that can be completed independently and don't require constant collaboration and ones that are more administrative by nature are the most optimal to engage in during this time of day. Data or content management, filing, bill paying etc…
                Being near water (in Nature/a water feature in home/or even a virtual water feature):
                The water connection is important for this aspect of your nature.  Take a break to visit a natural water feature (in Nature) during this time of day. Alternatively, purchase a water fountain (big or small) for your home …or activate Apps that offer the sights and sounds of the flow of water and activate these in the background during this time of day.
                    Pay Attention to the Nature of Your Social Interactions During This Time of Day:
                Practice 'social batching' during this time of day by grouping social activities together. This allows you to fulfill social responsibilities efficiently while preserving larger blocks of solitude
                Practice self-reflection to understand your social energy limits. Keep a journal to track how different social interactions affect your well-being and adjust your commitments accordingly
                Use transition rituals to move between solitude and social time. Develop personal practices that help you prepare for social engagement after periods of solitude, and vice versa
                '
            },

    'ven': {

            'Description': 'Because you are naturally charming and a natural nurturer
                Check in with loved ones or colleagues during this time of day, offering your support and listening ear
                You can be a calming presence in any tense situation during this time of day. When this occurs, your active listening skills and your ability to naturally see what is needed to nurture the moment will help diffuse conflicts.
                    Share your sympathetic nature by expressing gratitude to those around you regularly, and particularly during this time of day.
                Use your natural charm during this time of day to build rapport and foster positive relationships in both personal and professional settings.
                    This is the time of day where it’s natural for you to ease into things:
                Regardless of what time of day this trait is most prominent in you…because this trait is a prominent trait in your “vehicle”...schedule a daily power nap (20-30 minutes) in the afternoon to recharge and maintain your energy levels.
                    Notice what’s needed during the time of day when this trait is most prominent in order for you to maintain productivity without feeling overwhelmed.
                Use technology to track and set reminders for yourself to nudge you back in time to ensure you meet responsibilities without stress.
                    '
    },

    'mer': {
            'Description': 'Because Your Perceptive Trait “Gets Things” So Quickly…it Can Be Impatient:
                Practice Active Listening During This Time of Day: Take time to listen to others without immediately offering a way to solve the problem. Show appreciation for the efforts and contributions of those around you. Practice patience when others need time to process information or make decisions
                Count to five before responding in heated discussions
                Seek to understand different perspectives before offering your own
                Practice active listening in conversations, during this time of day, to ensure your quick responses are well-informed and considerate of others' perspectives.
                Because Your Perceptive Trait Makes You a Critical Thinker:
                During this time of day, leverage your ability to trouble-shoot and focus intensely by tackling complex, analytical strategies that require deep concentration and attention to detail.
                Prioritize your workload during this time of day based on urgency and importance, allowing your strategic mind to focus on what truly matters.
                Because Your Perceptive Trait Can Be Covert and a Bit Territorial at Times:
                Practice being more open to others input during this time of day
                Practice recognizing at least one valuable asset that each individual offers in the strategic dynamic of the matter at hand
                Consider sharing a bit more than you normally would (with the appropriate people) in an effort to keep others aligned and engaged with you
                Because Your Perceptive Trait Provides you with a Naturally Competitive Nature:
                Align with your standards for yourself during this time of day
                Challenge a colleague, family member or friend to a friendly contest of skills
                Engage in activities that challenge your mind, such as puzzles or strategic games. This keeps your analytical skills sharp and provides a constructive outlet for your critical thinking.
                '
    },

    'so': {

            'Description': 'Because you are so accepting and Fun-loving:
                Embrace your natural ability to connect with others, particularly during this time of day
                During this time of day…Mentor/coach or align with those who need supportive encouragement. Lighten-up a situation or the mood in the room by engaging in some light-hearted fun with others. Your uplifting nature can be contagious (as long as you “read” your audience). Tune-in to make sure you’re not overly fun-loving in the presence of certain people. Your light-hearted energy can inspire and uplift those around you, creating a supportive environment.
                Cultivate an awareness in knowing that others are “wired” differently and not all are as naturally accepting as you. Rather than allowing yourself to be impacted in a sensitive manner by any criticism in the moment, accept the differences and release your sensitive feelings in real time.
                Since you process information quickly:
                Channel your speedy processing skills into brainstorming sessions or creative projects. This will help you generate innovative ideas and solutions, allowing you to contribute effectively in group settings.
                Schedule regular 'wonder-filled' moments during this time of day. Find your favorite space that inspires and allows for your brilliant, creative possibilities to activate. This will keep your curiosity alive and maintain your enthusiasm for discovering new possibilities.
                Remember, particularly during this time of day, that this Effervescent processor in you processes information fast …and that same information will escape just as fast. Share your insights or the information that comes in for you in real time (or write them down) otherwise they may escape.
                '
    }}



  IMPORTANT :

  # HIGHER GRID NUMBER MEANS GOOD IN HUMAN CHARACTER TRAITS AND DRIVERS

  # LOWER IN NUMBER MEANS NOT GOOD IN HUMAN CHARACTER TRAITS AND DRIVERS



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

    1)VERY IMPORTANT # THESE ARE REFERENCE DOCUMENTS related to QUERY but you have to respond with complete information [S]:{". implode('\n', $final_chunks) ."}[/S]



    DO NOT RESPOND WITH THESE WORDS INSTEAD USE PUBLIC WORDS:

      SA	MA	JO	LU	VEN	MER	SO

      DE	DOM	FE	GRE	LUN	NAI	NE	POW	SP	TRA	VAN	WIL

      G	S	C

      EM	INS	INT	MOV

                +	-	PV	EP

                IMPORTANT: EXPLAIN EACH POINT WITH EXAMPLES

    IMPORTANT : GENERATE DIRECT RESPONSE, DO NOT START LIKE THIS

    1) Here's a more detailed response to the question

    2) Here's a more detailed explanation of the Absorptive trait (previously referred to as JO):

    3) Avoid generate text like this : scoring 5 out of 5

    4) Donot use work like Certainly! etc



    IMPORTANT INSTRUCTION:

    1)GENERATE TEXT IN HTML FORMAT

    2)DONT MAKE HEADING

    3)USE BULLET POINTS

    4)FOR IMPORTANT WORDS USE BOLD <b> tag

    5)DO NOT PLACE STERIC like this(**Regal**) ON IMPORTANT WORDS ONLY USE HTML TAGS to bold",
                    ],
                    [
                        'role' => 'assistant',
                        'content' => 'Act as a Human Op assistant',
                    ],
                    [
                        'role' => 'user',
                        'content' => "IF USER QUERY NEED EXPLAINABLE ANSWER THEN PROVIDE.
                        HERE IS USER QUERY: $this->message
                        Generate answers so that users understand easily. Also, refer to real-life examples so it's a good way to communicate",
                    ]
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
