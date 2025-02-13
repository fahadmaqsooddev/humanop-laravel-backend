<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatPrompt extends Model
{
    use HasFactory;
    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

     public static function createUpdatePrompt($name = null, $prompt = null,$restriction = null, $keyword_restriction_message = null)
     {
        $existingPrompt =  self::singlePromptByName($name);
         if($existingPrompt){
             return self::where('id',$existingPrompt['id'])->update([
                 'name' => $name,
                 'prompt' => $prompt ?? null,
                 'restriction' => $restriction ?? null
             ]);
         }else{
             return self::create([
                 'name' => $name,
                 'prompt' => $prompt ?? null,
                 'restriction' => $restriction ?? null
             ]);
         }
     }

     public static function singlePromptByName($name = null)
     {
         return self::where('name',$name)->first();
     }
}
