<?php

namespace App\Models\Admin\AssessmentWalkthrough;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentWalkThrough extends Model
{
    use HasFactory;
    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function storeData($overview,$code,$optimal,$optimization,$title){
        $data = [
            'overview' => $overview,
            'code_name' => $code[0], // Entire array
            'optimal' => $optimal,
            'optimization' => $optimization,
            'title' => $title,
        ];
        $check = self::where('code_name', $data['code_name'])
    ->where('title', $data['title'])
    ->first();

    if ($check) {
       
        return $check->update($data); 
    } else {
      
        return self::create($data);
    }
    }



    public static function getData($title,$code){
        return  self::where('code_name', $code[0])
        ->where('title', $title)
        ->first();
    }
}
