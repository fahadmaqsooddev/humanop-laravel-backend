<?php

namespace App\Models\Client\Dashboard;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Assessment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPlan extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // accessor

    public function getPlanTextAttribute($value){

        $environment = env('APP_ENV');

        if ($environment === 'local' || $environment === 'development'){

            return $value;

        }else{

            return "Coming Soon";
        }
    }

    public function getTextAttribute($value){

        $environment = env('APP_ENV');

        if ($environment === 'local' || $environment === 'development'){

            return $value;

        }else{

            return "<h1>Coming Soon</h1>";
        }
    }


    // query

    public static function storeUserActionPlan(){

        $app_env = env('APP_ENV');

        if ($app_env != 'production' || $app_env != 'staging'){

            $user = Helpers::getWebUser() ?? Helpers::getUser();

            $plan = $user['plan_name'];

            $days_according_to_plan = $user['plan_name'] === 'Freemium' || $user['plan_name'] === 'Core' ?

                $user['plan_name'] === 'Core' ? 30 : 90 : 7;

            $user_action_plan = self::where('user_id', $user->id)->first();

            if ($user_action_plan){

                if (Carbon::parse($user_action_plan['updated_at'])->addDays($days_according_to_plan)->lessThan(Carbon::today())){

                    $latestAssessment = Assessment::getLatestAssessment($user->id);

                    if ($latestAssessment){

                        $assessmentDetails = Assessment::getAllRowGrid($latestAssessment->id);
                    }

                    $body = ['grid' => $assessmentDetails ?? null,'plan' => $plan];

                    $data = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/90day_plan',$body);

                    $user_action_plan->update(['plan_text' => $data[0], 'text' => $data[1]]);

                }

            }else{

                $latestAssessment = Assessment::getLatestAssessment($user->id);

                if ($latestAssessment){

                    $assessmentDetails = Assessment::getAllRowGrid($latestAssessment->id);
                }

                $body = ['grid' => $assessmentDetails ?? null,'plan' => $plan];

                $data = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/90day_plan',$body);

                self::create(['plan_text' => $data[0], 'text' => $data[1], 'user_id' => $user->id]);

            }

        }

    }

    public static function userActionPlan(){

        $user_id = Helpers::getUser()->id ?? Helpers::getWebUser()->id;

        return self::where('user_id', $user_id)->select(['id','plan_text','text'])->first();
    }


}
