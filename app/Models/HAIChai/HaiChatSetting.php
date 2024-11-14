<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaiChatSetting extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
    }

    public static function getHaiChatSetting()
    {
        return self::first();
    }

    public static function updateHaiChatSetting($temperature = null, $max_token = null, $chunk = null)
    {
        return self::first()->update([
            'temperature' => $temperature,
            'max_token' => $max_token,
            'chunk' => $chunk
        ]);
    }
}
