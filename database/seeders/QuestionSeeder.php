<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->truncate();

        $questions = [
            ['question' => 'What was the hair color closest to your natural hair color at 21?', 'sort' => 0, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Which eye color is closest to yours?', 'sort' => 1, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are your eyes?', 'sort' => 2, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Have you been told your eyes sparkle or you have a twinkle in your eye?', 'sort' => 3, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Is your nose:', 'sort' => 4, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Which is your natural body shape?', 'sort' => 5, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you tall? (6"0" or taller)', 'sort' => 6, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you tall (5"7 or taller)', 'sort' => 7, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Do people see you as taller than you are?', 'sort' => 8, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Did your hair begin thinning before age 30?', 'sort' => 9, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'When you gain weight, where does it show first?', 'sort' => 10, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'When you gain weight, where does it show first?', 'sort' => 11, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you naturally hairy?', 'sort' => 12, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you naturally hairy?  Do you have any of these: hairy chest, back,  arms or legs?', 'sort' => 13, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'On a scale of 1 to 8, "1 being positive" and "8 being negative", where do you rate yourself?', 'sort' => 14, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you confrontational when provoked?', 'sort' => 15, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'For your ethnicity, what is your skin tone?', 'sort' => 16, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Do you have a childlike voice?', 'sort' => 17, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you easily distracted?', 'sort' => 18, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you an optimist?', 'sort' => 19, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'In non-emergency situations, are you quick to take action?', 'sort' => 20, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Is your natural chin or jaw line:', 'sort' => 21, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you naturally playful?', 'sort' => 22, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are your friends predominantly...', 'sort' => 23, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Do you like shiny things?', 'sort' => 24, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you aggressive?', 'sort' => 25, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'If you were a character in a fairy tale, would you want to be a:', 'sort' => 26, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you "frequently" volunteered for positions of authority?', 'sort' => 27, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you a micromanager?', 'sort' => 28, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you lazy?', 'sort' => 29, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Do you often feel other people"s pain?', 'sort' => 30, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you patient?', 'sort' => 31, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Do you naturally have a high forehead or receding hairline?', 'sort' => 32, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you afraid of life? (traffic, airplanes, germs)', 'sort' => 33, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Is a beautiful environment important to you?', 'sort' => 34, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Is it important for you to create order in your home or work place?', 'sort' => 35, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you very apologetic?', 'sort' => 36, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you stubborn?', 'sort' => 37, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you an instigator?', 'sort' => 38, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are you gullible?', 'sort' => 39, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Have you ever gone unnoticed at a party?', 'sort' => 40, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Are your moods unpredictable?', 'sort' => 41, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Do you share?', 'sort' => 42, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Have you ever gone unnoticed at a party?', 'sort' => 40, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Have you ever gone unnoticed at a party?', 'sort' => 40, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Have you ever gone unnoticed at a party?', 'sort' => 40, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Have you ever gone unnoticed at a party?', 'sort' => 40, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Have you ever gone unnoticed at a party?', 'sort' => 40, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Have you ever gone unnoticed at a party?', 'sort' => 40, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Have you ever gone unnoticed at a party?', 'sort' => 40, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['question' => 'Have you ever gone unnoticed at a party?', 'sort' => 40, 'active' => 1, 'gender' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('questions')->insert($questions);
    }
}
