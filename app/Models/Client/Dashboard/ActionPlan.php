<?php

namespace App\Models\Client\Dashboard;

use App\Helpers\GuzzleHelper\GuzzleHelpers;
use App\Helpers\Helpers;
use App\Models\Assessment;
use Carbon\Carbon;
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

    public static function storeUserActionPlan(){

        $user = Helpers::getWebUser();

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

                $body = ['grid' => $assessmentDetails ?? [],'plan' => $plan];

                $data = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/90day_plan',$body);

                $user_action_plan->update(['plan_text' => $data]);

            }

        }else{

            $latestAssessment = Assessment::getLatestAssessment($user->id);

            if ($latestAssessment){

                $assessmentDetails = Assessment::getAllRowGrid($latestAssessment->id);
            }

            $body = ['grid' => $assessmentDetails ?? null,'plan' => $plan];

            $data = GuzzleHelpers::sendRequestFromGuzzle('post', 'http://44.201.128.253:8000/90day_plan',$body);

            self::create(['plan_text' => $data, 'user_id' => $user->id]);

        }

    }

    public static function userActionPlan($user_id){

        return self::where('user_id', $user_id)->first();
    }


}
