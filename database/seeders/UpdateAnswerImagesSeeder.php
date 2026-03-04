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
        $answers = [
            1  => ['male' => 'black-hair-male.jpg',        'female' => 'hair-black.png'],
            2  => ['male' => 'brown-hair-male.jpg',        'female' => 'hair-brown.png'],
            3  => ['male' => 'light-brown-hair-male.jpg',  'female' => 'hair-light-brown.png'],
            4  => ['male' => 'blonde-hair-male.jpg',       'female' => 'hair-blonde.png'],
            5  => ['male' => 'red-hair-male.jpg',          'female' => 'hair-red.png'],
            6  => ['male' => 'eye-dark-brown.png',    'female' => 'eye-dark-brown.png'],
            7  => ['male' => 'eye-blue.png',          'female' => 'eye-blue.png'],
            8  => ['male' => 'eye-hazel.png',         'female' => 'eye-hazel.png'],
            9  => ['male' => 'eye-golden-brown.png',  'female' => 'eye-golden-brown.png'],
            10 => ['male' => 'eye-blue-violet.png',   'female' => 'eye-blue-violet.png'],
            11 => ['male' => 'eye-green.png',         'female' => 'eye-green.png'],
        ];

        foreach ($answers as $id => $images) {
            DB::table('answers')->where('id', $id)->update([
                'male_image'   => $images['male'],
                'female_image' => $images['female'],
                'updated_at'   => now(),
            ]);
        }
    }
}