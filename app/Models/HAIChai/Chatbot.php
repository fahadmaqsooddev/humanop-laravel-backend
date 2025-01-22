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

    protected $appends = ['chat_bot_color','is_chat_bot_connected'];

    // Appends
    public function getChatBotColorAttribute(){

        $plan_name = $this->plan()?->first()?->name ?? false;

        if($plan_name){

            if ($plan_name === 'Freemium'){

                return '#F3DEBA';

            }elseif($plan_name === 'Core'){

                return '#8BB1AB';

            }elseif ($plan_name === 'Premium') {

                return '#1A7D9E';
            }
        }

        return '#F3DEBA';
    }

    public function getIsChatBotConnectedAttribute(){

        return self::where('plan_id', $this->plan_id)

            ->where('is_connected', 1)->exists();

    }


    // Queries
    public static function createChat($name = null, $description = null)
    {
        return self::create([
            'name' => $name,
            'description' => $description,
            'plan_id' => $plan_id,
        ]);
    }

    public static function allChats()
    {
        return self::orderBy('created_at', 'desc')->with('setting.plan')->get(['id', 'name', 'description']);
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
