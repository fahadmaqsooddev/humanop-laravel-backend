<?php

namespace App\Models\Admin\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function allNotification()
    {
        return self::orderBy('created_at', 'desc')->get(['heading', 'notification', 'created_at', 'read']);
    }

    public static function createNotification($heading = null, $message = null)
    {
        return self::create([
            'heading' => $heading,
            'notification' => $message,
        ]);
    }
}
