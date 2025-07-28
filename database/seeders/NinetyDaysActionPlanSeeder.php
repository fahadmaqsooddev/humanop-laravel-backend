<?php

namespace Database\Seeders;

use App\Enums\Admin\Admin;
use App\Models\Client\Dashboard\ActionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NinetyDaysActionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $actionPlans = ActionPlan::all();

        foreach ($actionPlans as $actionPlan) {

            $actionPlan->type = Admin::NINETY_DAYS_ACTION_PLAN;

            $actionPlan->save();

        }

    }

}
