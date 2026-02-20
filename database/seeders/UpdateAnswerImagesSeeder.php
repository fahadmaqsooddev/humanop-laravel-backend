<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UpdateAnswerImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            1  => '/hair-black.png',
            2  => '/hair-brown.png',
            3  => '/hair-light-brown.png',
            4  => '/hair-blonde.png',
            5  => '/hair-red.png',
            6  => '/eye-dark-brown.png',
            7  => '/eye-blue.png',
            8  => '/eye-hazel.png',
            9  => '/eye-golden-brown.png',
            10 => '/eye-blue-violet.png',
            11 => '/eye-green.png',
        ];

        foreach ($data as $id => $image) {
            DB::table('answers')
                ->where('id', $id)
                ->update([
                    'image' => $image,
                    'updated_at' => now(),
                ]);
        }
    }
}
