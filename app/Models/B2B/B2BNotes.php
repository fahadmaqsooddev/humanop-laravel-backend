<?php

namespace App\Models\B2B;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class B2BNotes extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    // relation
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function CreateNote($data = null)
    {

        return self::create([
            'business_id' => Helpers::getUser()['id'],
            'user_id' => $data['user_id'],
            'note' => $data['note'],
        ]);
    }

    public static function UpdateNote($data = null)
    {

        return self::where('id', $data['note_id'])->update([
            'note' => $data['note']
        ]);
    }

    public static function singleNote($id = null)
    {
        return self::whereId($id)->first();
    }

    public static function getNoteFromUserId($userId = null)
    {
        return self::where('user_id', $userId)->first();
    }


}
