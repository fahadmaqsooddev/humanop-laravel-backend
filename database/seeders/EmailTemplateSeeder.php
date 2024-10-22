<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')->truncate();

        $email_verification = '<p>email verification send</p>';

        $email_template = [
            ['name' => 'email-verification', 'format' => $email_verification],
        ];

        DB::table('email_templates')->insert($email_template);
    }
}
