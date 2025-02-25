<?php

namespace App\Helpers;

use App\Models\Upload\Upload;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Assessment;

class OpenRouterHelper
{
    public static function callOpenRouterApi()
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
            "model" => "deepseek/deepseek-chat","qwen/qvq-72b-preview","deepseek/deepseek-r1-distill-qwen-1.5b","openai/gpt-3.5-turbo","anthropic/claude-3-haiku","google/gemini-2.0-flash-001",
            "allow_fallbacks" => true,
            "tokens" => 500,
            "messages" => [
                [
                    "role" => "user",
                    "content" => "What is Copper-Silver  Alchemy?"
                ],
                [
                    "role" => "system",
                    "content" => "People  who  have  a  Copper-Silver  Alchemy  align  with  utility  and  practicality.  In  other  words,  they
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
}
