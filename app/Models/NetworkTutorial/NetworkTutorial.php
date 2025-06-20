<?php

namespace App\Models\NetworkTutorial;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkTutorial extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    protected $appends = ['icon_url'];


    public function getIconUrlAttribute()
    {

        return Helpers::getImage($this->icon_id, null);

    }

    public static function allTutorials()
    {
        return self::orderBy('created_at', 'desc')->get();
    }

    public static function createTutorial($data = null)
    {

        return self::create($data);
    }

    public static function deleteTutorial($id = null)
    {

        return self::where('id', $id)->delete();
    }
}
