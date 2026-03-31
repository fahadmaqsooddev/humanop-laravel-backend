<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\HotSpotUser;
use Illuminate\Database\Seeder;

class createUserHotSpotSeeder extends Seeder
{
    public function run()
    {

        $trendTracker = new HotSpotUser();

        $assessments = Assessment::query()

            ->select(['assessments.id', 'assessments.user_id', 'users.date_of_birth'])

            ->join('users', 'users.id', '=', 'assessments.user_id')

            ->whereNull('users.deleted_at')

            ->where(function ($query) {

                $query->where('assessments.page', 0)

                    ->orWhere('assessments.web_page', 0)

                    ->orWhere('assessments.app_page', 0);

            })

            ->orderBy('assessments.id')

            ->cursor();

        foreach ($assessments as $assessment) {

            $data = Assessment::getAllRowGrid($assessment->id);

            if (!$data) {

                continue;

            }

            DB::transaction(function () use ($assessment, $data, $trendTracker) {

                HotSpotUser::query()
                    ->where('assessment_id', $assessment->id)
                    ->where('user_id', $assessment->user_id)
                    ->delete();

                $trendTracker->insertData(
                    $assessment->id,
                    $data,
                    (int) $assessment->user_id,
                    $assessment->date_of_birth
                );

            });

        }

    }

}
