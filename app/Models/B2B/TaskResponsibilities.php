<?php

namespace App\Models\B2B;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResponsibilities extends Model
{
    use HasFactory;

    

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }


    public function roleTemplate()
    {
        // return $this->belongsTo(RoleTemplate::class, 'role_template_id');
        return $this->belongsTo(RoleTemplate::class);
    }



    public static function createTags($tags, $roleTemplateId)
    {
    foreach ($tags as $tag) {
        self::create([
            'role_template_id' => $roleTemplateId, 
            'tags' => $tag, 
        ]);
    }
    }

    public static function DeleteTags($roleTemplateId)
    {
      self::where('role_template_id',$roleTemplateId)->delete();
    }



}
