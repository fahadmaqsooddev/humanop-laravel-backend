<?php

namespace App\Models\HAIChai;

use FontLib\Table\Type\name;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chatbot extends Model
{
    use HasFactory, SoftDeletes;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function createChat($name = null, $description = null)
    {
        return self::create([
            'name' => $name,
            'description' => $description,
        ]);
    }

    public static function allChats()
    {
        return self::orderBy('created_at', 'desc')->get(['id', 'name', 'description']);
    }

    public static function singleChat($id = null)
    {
        return self::whereId($id)->first();
    }

    public static function getChatFromVendorName($vendor_name = null)
    {

        return self::where('name', $vendor_name)->first();
    }

    public static function deleteChat($id = null)
    {
        return self::whereId($id)->delete();


    }
}
