<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruncateEnergyShieldTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all specified tables
        DB::table('biometric_samples')->truncate();
        DB::table('daily_metrics')->truncate();
        DB::table('energy_shield_states')->truncate();
        DB::table('events')->truncate();
        DB::table('boost_sessions')->truncate();
        DB::table('location_samples')->truncate();
        DB::table('user_humanop_profiles')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('All specified tables have been truncated successfully!');
    }
}
