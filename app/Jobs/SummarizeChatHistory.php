<?php

namespace App\Jobs;

use App\Helpers\OpenRouterHelper;
use App\Models\HAIChai\HaiChatConversation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SummarizeChatHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $user_id;

    public function __construct($user_id = null)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

//        if ($this->chat_bot_name){
//
//            $chats = HaiChatConversation::where('chatbot', $this->chat_bot_name)->latest()->limit(20)->get();
//
//        }else{

            $chats = HaiChatConversation::where('user_id', $this->user_id)->latest()->limit(20)->get();
//        }

        $question_answer_string = "";

        foreach ($chats as $key => $chat){

            $question = $chat['message'];
            $answer = $chat['reply'];

            $key += 1;

            $question_answer_string .= "$key. Question: $question \n Answer: $answer \n\n";

        }

        $openRouterResponse = OpenRouterHelper::callOpenRouterApiForSummarizeChat('anthropic/claude-3-haiku', $question_answer_string);

        foreach ($openRouterResponse['choices'] as $choice) {

            if ($choice['message']['content'] && $this->user_id){

                User::whereId($this->user_id)->update([
                    'chat_summary' => $choice['message']['content'],
                ]);
            }

        }


    }
}
