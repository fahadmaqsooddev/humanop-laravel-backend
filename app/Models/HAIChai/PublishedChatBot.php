<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishedChatBot extends Model
{
    use HasFactory;

    protected $casts = [
        'embedding_ids' => 'array',
        'restricted_keywords' => 'array'
    ];

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    // query
    public static function addPublishedChatBot($data){

        self::truncate();

        self::create($data);

    }
}
