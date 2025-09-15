<?php

namespace App\Models\Admin\Signup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignupScreen extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function allScreens()
    {
        return self::all();
    }

    public static function updateOnboarding($id = null, $title = null, $description = null)
    {

        $singleOnboardingScreen = self::whereId($id)->first();

        $singleOnboardingScreen->screen_name = $title;
        $singleOnboardingScreen->description = $description;
        $singleOnboardingScreen->save();
    }
}
