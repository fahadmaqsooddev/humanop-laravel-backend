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
            ['priority' => 'priority_3','condition' => '[JO, MER, and SO Volume (First Row of #s) are all <5] AND [Neither JO or MER are bridged on both sides by >=5] AND [JO, MER, and SO each have a multiplier volume on the 3rd row of <30]','content' => config('actionPlan.priority_3'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_4','condition' => 'IF the number of authentic Traits (volume of first row is >=5 aka green boxes) is less than (<) 3','content' => config('actionPlan.priority_4'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_5','condition' => '[MA and LU are both <5] AND [Neither MA or LU is bridged on both sides by >=5] AND [Both MA And LU have a volume on the third row of <30]','content' => config('actionPlan.priority_5'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_6','condition' => '[SA and VEN are both <5] AND [Neither SA or VEN is bridged on both sides by >=5] AND [Both SA And VEN have a volume on the third row of <30]','content' => config('actionPlan.priority_6'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_7','condition' => 'IF there are GREATER THAN (>) 3 Drivers that have Volumes (1st row) that are GREATER THAN (>) 2  AND are NOT authentic drivers (aka “red boxes”)','content' => config('actionPlan.priority_7'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_8','condition' => 'IF there are exactly THREE (3) Drivers that are GREATER THAN >2 AND are NOT authentic drivers (i.e. “red boxes”)','content' => config('actionPlan.priority_8'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_9','condition' => 'IF there are exactly TWO (2) Drivers that are GREATER THAN >2 AND are NOT authentic drivers (i.e. “red boxes”)','content' => config('actionPlan.priority_9'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_10','condition' => 'IF there is exactly ONE (1) Driver that is GREATER THAN >2 AND is NOT an authentic driver (i.e. “red box”)','content' => config('actionPlan.priority_10'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_11','condition' => 'IF both Pilot and Co-Pilot (Green Box) Drivers are LESS THAN (<) 3 in Volume (1st Row)','content' => config('actionPlan.priority_11'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_12','condition' => 'IF one of the Pilot or Co-Pilot (Green Box) Drivers is LESS THAN (<) 3 in Volume (1st Row)','content' => config('actionPlan.priority_12'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_13','condition' => 'IF total Volume (1st Row) of Drivers is GREATER THAN (>) 21','content' => config('actionPlan.priority_13'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['priority' => 'priority_14','condition' => 'IF total Volume (1st Row) of Drivers is LESS THAN (<) 16','content' => config('actionPlan.priority_14'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
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
            ['priority' => 'priority_26','condition' => 'If ALL the above conditions are FALSE','content' => config('actionPlan.priority_26'),'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 'created_at' => Carbon::now()->format('Y-m-d H:i:s')],

        ];

        DB::table('optimization_plan')->insert($plan);
    }
}
