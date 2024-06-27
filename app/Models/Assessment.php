<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Assessment extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function createAssessment($data = null)
    {
        return self::create($data);
    }

    public static function updateAssessment($data = null, $id = null)
    {
        return self::find($id)->update($data);
    }

    public static function getLastPage()
    {
        $page = self::where('user_id', Auth::user()->id)->select(['page'])->latest()->first();
        if ($page) {
            return $page->page;
        } else {
            return 0;
        }
    }

    public static function getGrid($id = null)
    {
        return self::whereId($id)->where('user_id', Auth::user()['id'])->first();
    }

    public static function getAssessment()
    {
        return static::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    }

    public static function allAssessment()
    {
        return self::with('user')->get();
    }

}
