<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Assessment extends Model
{
    use HasFactory;
    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
    }
    public static function createAssessment($data = null){
        return self::create($data);
    }
    public static function updateAssessment($data = null,$id = null){
        return self::find($id)->update($data);
    }

    public static function getLastPage(){
       $page =  self::where('user_id',Auth::user()->id)->select(['page'])->latest()->first();
        if($page){
            return $page->page;
        }else{
            return 0;
        }
    }
}
