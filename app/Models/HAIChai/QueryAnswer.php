<?php

namespace App\Models\HAIChai;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Admin\FineTuneContent\FineTuneContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryAnswer extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function question()
    {

        return $this->belongsTo(ClientQuery::class, 'query_id', 'id');
    }

    public static function createAnswer($queryId = null, $answer = null)
    {
        return self::create([
            'query_id' => $queryId,
            'answer' => $answer,
        ]);
    }

    public static function unApprovedQueries($perPage = 10)
    {

        return self::has('question')->where('approved', 0)->with('question')->latest()->paginate($perPage)->setPath(route('admin_approve_queries'));
    }

    public static function approveAnswer($id = null)
    {

        self::whereId($id)->update(['approved' => 1]);

        self::updateBucketFromApprovedAnswer($id);
    }

    public static function updateQueryAnswer($data = null, $id = null)
    {

        self::approveAnswer($id);

        self::whereId($id)->update($data);
    }

    public static function userQueryAnswer()
    {

        return self::whereHas('question', function ($q) {

            $q->where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)
                ->where('response', 1)->whereNull('conversation_id');

        })
            ->with('question')
            ->where('approved', 1)
            ->latest()
            ->first();
    }

    public static function updateBucketFromApprovedAnswer($id = null)
    {

        $answer = self::whereId($id)->with('question')->first();

        $body = [
            'question' => $answer['question']['query'] ?? null,
            'answer' => strip_tags($answer->answer ?? null),
        ];

        FineTuneContent::addLisaApprovedQuestionAnswers($body);

        $app_env = env('APP_ENV');

        $url = $app_env === 'staging' ? 'http://54.227.7.149:8000/qa_bucket' : 'http://54.227.7.149:8000/qa_bucket';

        GuzzleHelpers::sendRequestFromGuzzle('post', $url, $body);

    }
}
