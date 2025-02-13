<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaiChaiChunk extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getHaiChunk($embedding = null, $chatBot = null)
    {
        if (!empty($embedding))
        {
            return self::where('embedding', $embedding)->get();
        }
        elseif (!empty($chatBot))
        {
            return self::where('chatbot', $chatBot)->get();
        }

    }

    // public static function createHaiChunk($response = null, $embedding = null, $chatBot = null)
    // {

    //     if (!empty($embedding))
    //     {
    //         if (!empty($response))
    //         {
    //             foreach ($response['retrieved_docs'] as $retrieved)
    //             {
    //                 foreach ($retrieved as $data)
    //                 {
    //                     self::create([
    //                         'embedding' => $embedding,
    //                         'query' => $response['query'],
    //                         'retrieved_docs' => $data
    //                     ]);
    //                 }
    //             }
    //         }

    //     }
    //     elseif (!empty($chatBot))
    //     {
    //         if (!empty($response))
    //         {
    //             foreach ($response['retrieved_docs'] as $retrieved)
    //             {

    //                 foreach ($retrieved as $data)
    //                 {
    //                     self::create([
    //                         'chatbot' => $chatBot,
    //                         'query' => $response['query'],
    //                         'retrieved_docs' => $data
    //                     ]);
    //                 }
    //             }
    //         }
    //     }

    // }

    // public static function checkAndUpdateHaiChunks($response = null, $embedding = null, $chatBot = null)
    // {
    //     if (!empty($embedding) || !empty($chatBot))
    //     {
    //         $chunks = self::getHaiChunk($embedding, $chatBot);

    //         if (!empty($chunks))
    //         {
    //             self::deleteHaiChunk($embedding, $chatBot);

    //             self::createHaiChunk($response, $embedding, $chatBot);
    //         }
    //     }
    // }

    // public static function deleteHaiChunk($embedding = null, $chatBot = null)
    // {
    //     if (!empty($embedding))
    //     {
    //         return self::where('embedding', $embedding)->delete();
    //     }
    //     elseif (!empty($chatBot))
    //     {
    //         return self::where('chatbot', $chatBot)->delete();
    //     }
    //     else
    //     {
    //         return null;
    //     }

    // }

}
