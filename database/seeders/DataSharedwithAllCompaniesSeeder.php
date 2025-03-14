<?php

namespace Database\Seeders;

use App\Models\B2B\B2BBusinessCandidates;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataSharedwithAllCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $companies = B2BBusinessCandidates::allCompaniesInfo();

        if (!empty($companies) && $companies) {
            foreach ($companies as $company) {

                if ($company['share_data'] == 0)
                {
                    $company->update(['share_data' => 1]);

                }
            }
        }
    }
}
