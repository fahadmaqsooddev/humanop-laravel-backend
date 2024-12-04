<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptimizationPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan = [

            ['priority' => 'priority_1','condition' => 'Aesthetic Sensibility is 0','content' => config('actionPlan.priority_1'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_2_regal','condition' => 'Regal is 0','content' => config('actionPlan.priority_2.regal'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_2_energetic','condition' => 'Energetic is 0','content' => config('actionPlan.priority_2.energetic'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_2_absorptive','condition' => 'Absorptive is 0','content' => config('actionPlan.priority_2.absorptive'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_2_sympathetic','condition' => 'Sympathetic is 0','content' => config('actionPlan.priority_2.sympathetic'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_2_perceptive','condition' => 'Perceptive is 0','content' => config('actionPlan.priority_2.perceptive'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_2_effervescent','condition' => 'Effervescent is 0','content' => config('actionPlan.priority_2.effervescent'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_3','condition' => 'IF [JO, MER, and SO MAIN VOLUME are all <5] AND [NEITHER JO or MER are BRIDGED] AND [JO, MER, and SO each have a 2ND TOGGLE VOLUME of <30]','content' => config('actionPlan.priority_3'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_4','condition' => 'IF the number of AUTHENTIC TRAITS (Green Boxes) is less than (<) 3','content' => config('actionPlan.priority_4'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_5','condition' => 'IF [MA and LU MAIN VOLUME are both <5] AND [NEITHER MA or LU are BRIDGED TRAITS] AND [Both MA And LU have a 2ND TOGGLE VOLUME of <30]','content' => config('actionPlan.priority_5'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_6','condition' => 'IF [SA and VEN MAIN VOLUMES are BOTH <5] AND [NEITHER SA or VEN are BRIDGED TRAITS] AND [BOTH SA And VEN have a 2ND TOGGLE VOLUME of <30]','content' => config('actionPlan.priority_6'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_7','condition' => 'IF there are 4+ INAUTHENTIC DRIVERS (4+ Red Boxes)','content' => config('actionPlan.priority_7'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_8','condition' => 'IF there are exactly THREE (3) Drivers that are INAUTHENTIC DRIVERS (3 Red Boxes) ','content' => config('actionPlan.priority_8'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_9','condition' => 'IF there are exactly TWO (2) Drivers that are INAUTHENTIC DRIVERS (2 Red Boxes) ','content' => config('actionPlan.priority_9'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_10','condition' => 'IF there is exactly ONE (1) Driver that is an INAUTHENTIC DRIVER (1 Red Box)','content' => config('actionPlan.priority_10'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_11','condition' => 'IF both PILOT and CO-PILOT (Green Boxes) DRIVERS are LESS THAN (<) 3 in MAIN VOLUME.','content' => config('actionPlan.priority_11'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_12','condition' => 'IF JUST ONE of the PILOT or CO-PILOT (Green Box) Drives is LESS THAN (<) 3 in MAIN VOLUME…','content' => config('actionPlan.priority_12'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_13','condition' => 'IF total of MAIN VOLUME (1st Row) of ALL Drivers is GREATER THAN (>) 21','content' => config('actionPlan.priority_13'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_14','condition' => 'IF total MAIN VOLUME (1st Row) of ALL DRIVERS is LESS THAN (<) 16','content' => config('actionPlan.priority_14'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_15','condition' => 'IF the Alchemy number is ANY ONE of these numbers: 700, 610, 601, 520, 511, 502, 430','content' => config('actionPlan.priority_15'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_16','condition' => 'IF the Alchemy number is ANY ONE of these numbers: 223, 133, 043, 214, 124, 115, 034, 007','content' => config('actionPlan.priority_16'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_17','condition' => 'IF ANY TWO (2) of the Energy Center (EM, INS, INT, or MOV) are GREATER THAN (>) 12 (1st Row)','content' => config('actionPlan.priority_17'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_18','condition' => 'IF ANY ONE (1) of the Energy Center (EM, INS, INT, or MOV) is GREATER THAN (>) 12 (1st Row)','content' => config('actionPlan.priority_18'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_19','condition' => 'IF ANY TWO (2) of the Energy Center (EM, INS, INT, or MOV) are LESS THAN (<) 7 (1st Row)','content' => config('actionPlan.priority_19'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_20','condition' => 'IF ANY ONE (1) of the Energy Center (EM, INS, INT, or MOV) is LESS THAN (<) 7 (1st Row)','content' => config('actionPlan.priority_20'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_21','condition' => 'IF the PV# = 0','content' => config('actionPlan.priority_21'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_22','condition' => 'IF the PV# < 0','content' => config('actionPlan.priority_22'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_23','condition' => 'IF the PV# > 12','content' => config('actionPlan.priority_23'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_24','condition' => 'IF the EP# < 25','content' => config('actionPlan.priority_24'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_25','condition' => 'IF the EP# > 35','content' => config('actionPlan.priority_25'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_26','condition' => 'If ALL the above conditions are FALSE then use the following 90-Day Optimization Action Plan','content' => config('actionPlan.priority_26'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        ];

        DB::table('optimization_plan')->insert($plan);
    }
}
