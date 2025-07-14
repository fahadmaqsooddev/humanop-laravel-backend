<?php

namespace App\Models\User;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTagline extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getTags($userId = null)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function deleteTags($userId = null)
    {
        return self::where('user_id', $userId)->delete();
    }

    public static function createTags($userId = null, $tag = null)
    {

        return self::create([
            'user_id' => $userId,
            'tagline' => $tag,
        ]);

    }

    public static function updateTags($tags = null)
    {

        $userId = Helpers::getUser()['id'];

        $allTags = self::getTags($userId);

        if (!$allTags->isEmpty()) {

            self::deleteTags($userId);

        }

        foreach ($tags as $tag) {

            self::createTags($userId, $tag);

        }

        return true;
    }

}
