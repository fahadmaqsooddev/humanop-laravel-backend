<?php

namespace App\Models\Client\Feedback;

use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function storeClientFeedback($data = null){

        $data['user_id'] = Helpers::getWebUser()->id;

        self::create($data);
    }
}
