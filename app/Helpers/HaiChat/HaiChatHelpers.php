<?php

namespace App\Helpers\HaiChat;

use App\Models\Upload\Upload;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Assessment;
use App\Models\Admin\StripeSetting\StripeSetting;
use App\Models\Client\Plan\Plan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use OpenAI\Client;
use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use Stripe\BaseStripeClient;
use Stripe\Stripe;
use Stripe\StripeClient;
use App\Models\User;
use App\Services\TwilioServices\TwilioServices;
class HaiChatHelpers
{
    public static function cosineSimilarity($vectorA, $vectorB) {

        $dotProduct = array_sum(array_map(fn($a, $b) => (float)$a * (float)$b, $vectorA, $vectorB));

        $magnitudeA = sqrt(array_sum(array_map(fn($a) => $a ** 2, $vectorA)));

        $magnitudeB = sqrt(array_sum(array_map(fn($b) => $b ** 2, $vectorB)));

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }

    public static function findRelevantChunks($query, $knowledgeBase, $chunks = 1) {

        $embeddingModel = \OpenAI::client(env('OPEN_AI_API_KEY'));;

        $queryEmbedding = $embeddingModel->embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $query,
            ]);

        $queryEmbedding = $queryEmbedding->toArray();

        $queryEmbedding = $queryEmbedding['data'][0] ?? [];

        $results = [];

        foreach ($knowledgeBase as $chunk) {
            $similarity = self::cosineSimilarity($queryEmbedding['embedding'], json_decode($chunk['embedding'], true));
            $results[] = ['content' => $chunk['content'], 'similarity' => $similarity];
        }

        usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

//        $threshold = 0.3;
//
//        $results = array_filter($results, function ($match) use ($threshold) {
//            return $match['similarity'] >= $threshold;
//        });

        return array_slice($results, 0, $chunks);
    }

    public static function findRelevantChunksForGrid($publicNames, $knowledgeBase, $chunks = 1) {

        $embeddingModel = \OpenAI::client(env('OPEN_AI_API_KEY'));;

        $results = [];

        foreach ($publicNames as $key => $publicName){

            $queryEmbedding = $embeddingModel->embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => "Detailed overview of " . $key,
            ]);

            $queryEmbedding = $queryEmbedding->toArray();

            $queryEmbedding = $queryEmbedding['data'][0] ?? [];

            foreach ($knowledgeBase as $chunk) {
                $similarity = self::cosineSimilarity($queryEmbedding['embedding'], json_decode($chunk['embedding'], true));
                $results[] = ['content' => $chunk['content'], 'similarity' => $similarity];
            }

            usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

        }

        $threshold = 0.3;

        $results = array_filter($results, function ($match) use ($threshold) {
            return $match['similarity'] >= $threshold;
        });

        return array_slice($results, 0, $chunks);
    }


}
