<?php

namespace App\Helpers;

use App\Enums\Admin\Admin;
use App\Models\Upload\Upload;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Assessment;
use Illuminate\Support\Facades\Log;

class OpenRouterHelper
{
    public static function callOpenRouterApi($question, $setting, $prompt, $llmModel, $persona)
    {
        $apiKey = "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";
        $siteUrl = "humanop.com"; // Optional
        $siteName = "HumanOp"; // Optional

        $url = "https://openrouter.ai/api/v1/chat/completions";

        $headers = [
            "Authorization: Bearer $apiKey",
            "HTTP-Referer: $siteUrl",
            "X-Title: $siteName",
            "Content-Type: application/json"
        ];

//        Log::info(['prompt' => $prompt]);

        $data = [
//            "model" => "deepseek/deepseek-chat","qwen/qvq-72b-preview","deepseek/deepseek-r1-distill-qwen-1.5b","openai/gpt-3.5-turbo","anthropic/claude-3-haiku","google/gemini-2.0-flash-001",
            "model" => $llmModel ?? "deepseek/deepseek-chat",
            "allow_fallbacks" => true,
            "tokens" => $setting['max_tokens'] ?? 500,
            "messages" => [
                [
                    "role" => "user",
                    "content" => $persona . "\n\n User: " . $question,
                ],
                [
                    "role" => "system",
                    "content" => $prompt ?? "People  who  have  a  Copper-Silver  Alchemy  align  with  utility  and  practicality.  In  other  words,  they
are  attracted  to  getting  the  most  use  out  of  everything,  whether  it  be  value  or  longevity,  and
they're  more  aligned  with  the  most  practical  choice  in  the  moment.
Their  relaxed  nature  will  let  piles  of  things  lay  about  and  accumulate,  and  they  rarely  feel  the
need  to  remove  them,  even  if  company  were  to  arrive.  This  more  organic  side  to  their  nature
can  sometimes  be  judged  unfairly  by  others  when  they're  simply  displaying  their
low-maintenance  nature  that  happens  to  thrive  in  a  more  lived-in  environment.
Because  they  naturally  can,  and  in  an  effort  to  reach  others  within  their  boundaries  of  tolerance,
it's  recommended  that  they  make  slight  adjustments  towards  their  silver  aspect  at  times  and
with  certain  audiences.  This  can  be  done  by  moving  into  the  practical  aspect  of  their  Alchemy
and  paying  a  little  more  attention  to  their  home  and  themselves  in  terms  of  upkeep  and  value
maintenance."
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            return json_decode($response, true);
        } else {
            return [
                'error' => true,
                'status_code' => $httpCode,
                'response' => $response
            ];
        }
    }

    public static function removeIrregularHtmlSyntax($text){

        if (str_starts_with($text, '```html') && str_ends_with($text, '```')){

            $text = substr($text, 7);

            $text = substr($text, 0,-3);
        }

        return $text;

    }

    public static function callOpenRouterApiWithHistory($setting, $llmModel, $messagePrompt = [])
    {
        $apiKey = "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";
        $siteUrl = "humanop.com"; // Optional
        $siteName = "HumanOp"; // Optional

        $url = "https://openrouter.ai/api/v1/chat/completions";

        $headers = [
            "Authorization: Bearer $apiKey",
            "HTTP-Referer: $siteUrl",
            "X-Title: $siteName",
            "Content-Type: application/json"
        ];

        $data = [
            "model" => $llmModel,
            "allow_fallbacks" => true,
            "tokens" => $setting['max_tokens'] ?? 500,
            "messages" => $messagePrompt
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            return json_decode($response, true);
        } else {
            return [
                'error' => true,
                'status_code' => $httpCode,
                'response' => $response
            ];
        }
    }

    public static function addUserDetailsIntoPrompt($user_id, $prompt){

        if ($user_id){

            $user = User::whereId($user_id)->first();

            $age = Carbon::parse($user['date_of_birth'])->age;

            $user_name = $user['first_name'];

            $gender = $user['gender'] == Admin::IS_MALE ? 'male' : 'female';

            $history = $user['chat_summary'];

            $user_detail_text = "** user name = $user_name, Gender=$gender,Age=$age ** \n\n

            <history>$history</history> \n";

            return ($user_detail_text . $prompt);
        }

        return $prompt;
    }

    public static function callOpenRouterApiForSummarizeChat($llmModel, $question_answers_string)
    {
        $apiKey = "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";
        $siteUrl = "humanop.com"; // Optional
        $siteName = "HumanOp"; // Optional

        $url = "https://openrouter.ai/api/v1/chat/completions";

        $headers = [
            "Authorization: Bearer $apiKey",
            "HTTP-Referer: $siteUrl",
            "X-Title: $siteName",
            "Content-Type: application/json"
        ];

        $data = [
//            "model" => "deepseek/deepseek-chat","qwen/qvq-72b-preview","deepseek/deepseek-r1-distill-qwen-1.5b","openai/gpt-3.5-turbo","anthropic/claude-3-haiku","google/gemini-2.0-flash-001",
            "model" => $llmModel,
            "allow_fallbacks" => true,
            "tokens" => 500,
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a helpful assistant that summarizes multiple question-and-answer pairs into key themes and insights.",
                ],
                [
                    "role" => "user",
                    "content" => "I will provide 20 question-and-answer pairs. Please read them and provide a concise summary of the main ideas, grouped by themes or recurring patterns. Use bullet points or short paragraphs. Avoid simply repeating the answers — instead, synthesize the information.
                                 Here are the Q&A pairs: \n $question_answers_string"
                ],
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            return json_decode($response, true);
        } else {
            return [
                'error' => true,
                'status_code' => $httpCode,
                'response' => $response
            ];
        }
    }

    public static function createFinalPersona($persona){

        $persona = "You are a personalized assistant that always identifies and addresses the user by name. The prompt you receive will contain user-specific details in a clear format like:

user name = John, age = 28, gender = male

Use these details to personalize your responses. You *must always refer to the user by their name* when speaking to them, using it naturally in conversation.

Additionally, the prompt will include a section enclosed in <history>...</history> tags. This section contains a *summary of the user's previous chats* with you. You have full permission and capability to extract relevant insights, preferences, goals, or context from this history. If you need to recall any detail about the user, first look inside the <history> section.

You also have access to internal user grids and metadata. You are highly familiar with the user's name, behavior, and preferences. Assume maximum familiarity with the user based on all available context.

Your goals:
- Speak to the user as if you know them well.
- Use their *name in every reply* where it sounds natural.
- Use the <history> section to reference past conversations or preferences.
- Adapt tone and suggestions according to user profile (name, age, gender, and memory). \n" . $persona;

        return $persona;

    }
}
