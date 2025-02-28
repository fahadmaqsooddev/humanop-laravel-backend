<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmbeddingSetting extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

     public static function getEmbeddingSetting($name = null)
     {
         return self::where('embedding', $name)->first();
     }

    // public static function createEmbedding($name = null, $chunk = null)
    // {
    //     return self::create([
    //         'embedding' => $name,
    //         'chunk' => $chunk,
    //     ]);
    // }

    // public static function checkAndUpdateEmbedding($name = null, $chunk = 1)
    // {
    //     $embedding = self::getEmbeddingSetting($name);

    //     if (!empty($embedding))
    //     {
    //         self::deleteEmbedding($name);

    //         self::createEmbedding($name, $chunk ?? 1);

    //     }else
    //     {
    //         self::createEmbedding($name, $chunk ?? 1);

    //     }
    // }

    // public static function deleteEmbedding($name = null)
    // {
    //     return self::where('embedding', $name)->delete();
    // }
}
