<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ClientQuery extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function singleQuery($id = null)
    {
        return self::whereId($id)->first();
    }

    public static function getQueries()
    {
        return self::where('response', 0)
            ->with(['users' => function ($q) {
                $q->select('id', 'first_name', 'last_name', 'email');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function createQuery($userId = null, $query = null)
    {
        return self::create([
            'user_id' => $userId,
            'query' => $query
        ]);
    }

    public static function updateQuery($id = null)
    {
        self::whereId($id)->update([
            'response' => 1
        ]);

        return self::singleQuery($id);
    }

}
