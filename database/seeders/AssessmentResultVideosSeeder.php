<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentResultVideosSeeder extends Seeder
{
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        DB::table('assessment_result_videos')->truncate();
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        $videos = [
            ['code'=>'JO','public_name'=>'Absorptive','video'=>'The Absorptive Trait.mp4','video_upload_id'=>547,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4c91fb0bb97fd980ef8be/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'JO','public_name'=>'Absorptivesa','video'=>'The Absorptive Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4c91fb0bb97fd980ef8be/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'POW','public_name'=>'Accomplishment','video'=>'Accomplishment.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4cc5caf70352b0d45ffd1/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'VAN','public_name'=>'Aesthetic Sensibility','video'=>'Aesthetic Sensibility.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4cf64b0bb97fd980fe449/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'SP','public_name'=>'Compassion','video'=>'Compassion.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4cf6430d9a408df21f3ca/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'C','public_name'=>'Copper','video'=>'Copper Alchemy.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4cf64b0bb97fd980fe447/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'CS','public_name'=>'Copper-Silver','video'=>'Copper-Silver Alchemy.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d07d30d9a408df220cac/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'FE','public_name'=>'Creates Protection','video'=>'Creating Protection.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d07d30d9a408df220cbd/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'DOM','public_name'=>'Creating Order','video'=>'Creating Order.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d07eb0bb97fd980ff899/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'SO','public_name'=>'Effervescent','video'=>'The Effervescent Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d803b0bb97fd98109bee/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'SO','public_name'=>'Effervescent','video'=>'The Effervescent Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d803b0bb97fd98109bee/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'EM','public_name'=>'Emotionally','video'=>'The Emotional Energy Center.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d85fb0bb97fd9810a90b/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'MA','public_name'=>'Energetic','video'=>'The Energetic Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d86030d9a408df22bad5/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'MA','public_name'=>'Energetic','video'=>'The Energetic Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d86030d9a408df22bad5/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'AE','public_name'=>'Energy Above Excellent','video'=>'Energy Pool - Above Excellent.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d1cd30d9a408df2226c9/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'A','public_name'=>'Energy Average','video'=>'Energy Pool - Average.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d1cdaf70352b0d46a1c4/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'E','public_name'=>'Energy Excellent','video'=>'Energy Pool - Energy Excellent.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d1cdaf70352b0d46c1/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'F','public_name'=>'Energy Fair','video'=>'Energy Pool - Fair.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d1ce30d9a408df2226ed/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'EP','public_name'=>'Energy Pool','video'=>null,'video_upload_id'=>null,'video_embed_link'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['code'=>'G','public_name'=>'Gold','video'=>'Gold Alchemy.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d1ceb0bb97fd9810145e/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'GS','public_name'=>'Gold-Silver','video'=>'Gold-Silver Alchemy.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d39530d9a408df22458a/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'NE','public_name'=>'Humility','video'=>'Humility.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d395af70352b0d46c141/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'DE','public_name'=>'Initiates Change','video'=>'Initiating Change.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d39530d9a408df22458c/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'INS','public_name'=>'Instinctually','video'=>'The Instinctual Energy Center.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d85faf70352b0d4739cf/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'INT','public_name'=>'Intellectually','video'=>'The Intellectual Energy Center.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d86caf70352b0d473b50/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'GRE','public_name'=>'Monetary Discernment','video'=>'Monetary Discernment.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d58caf70352b0d46e216/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'MOV','public_name'=>'Moving','video'=>'The Moving Energy Center.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d86cb0bb97fd9810aa18/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'NAI','public_name'=>'Optimism','video'=>'Optimism.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d58c30d9a408df22660d/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'MER','public_name'=>'Perceptive','video'=>'The Perceptive Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d870b0bb97fd9810aa7e/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'MER','public_name'=>'Perceptive','video'=>'The Perceptive Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d870b0bb97fd9810aa7e/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'WIL','public_name'=>'Perseverance','video'=>'Perseverance.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fcaf70352b0d4723c2/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'NE','public_name'=>'Negative','video'=>'Perception of LIfe - Negative.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d58c30d9a408df22662b/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'N','public_name'=>'Neutral','video'=>'Perception of Life - Neutral.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d58caf70352b0d46e227/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'P','public_name'=>'Positive','video'=>'Perception of Life - Positive.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d58c30d9a408df22662d/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'SA','public_name'=>'Regal','video'=>'The Regal Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d871af70352b0d473bb2/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'SA','public_name'=>'Regal','video'=>'The Regal Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d871af70352b0d473bb2/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'SA','public_name'=>'Regal','video'=>'The Regal Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d871af70352b0d473bb2/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'LU','public_name'=>'Romantic','video'=>'The Romantic Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d87230d9a408df22bf5e/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'LU','public_name'=>'Romantic','video'=>'The Romantic Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d87230d9a408df22bf5e/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'S','public_name'=>'Silver','video'=>'Silver Alchemy.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fc30d9a408df22ad41/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'SC','public_name'=>'Silver-Copper','video'=>'Silver-Copper Alchemy.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fc30d9a408df22ad5f/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'SG','public_name'=>'Silver-Gold','video'=>'Silver-Gold Alchemy.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fcaf70352b0d4723c4/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'VEN','public_name'=>'Sympathetic','video'=>'The Sympathetic Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d874af70352b0d473c03/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'VEN','public_name'=>'Sympathetic','video'=>'The Sympathetic Trait.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d874af70352b0d473c03/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'TRA','public_name'=>'The Traveler','video'=>'Traveler.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d874af70352b0d473c19/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'LUN','public_name'=>'Visionary','video'=>'The Visionary.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d87430d9a408df22c275/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'SI','public_name'=>'Summary Introduction','video'=>null,'video_upload_id'=>null,'video_embed_link'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['code'=>'MRI','public_name'=>'Main Result Introduction','video'=>'HumanOp ULT Results Intro - Lisa Nelson.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d395af70352b0d46c13e/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'CLI','public_name'=>'Cycle Of Life Introduction','video'=>'Intro to The Cycle of Life.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68df5ab33232f7b8d6ffb41f/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'TI','public_name'=>'Trait Introduction','video'=>'Intro to Traits.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68df5ab33232f7b8d6ffb41d/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'MI','public_name'=>'Motivation Introduction','video'=>'Intro to Motivation (Drivers).mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68df5ab3cb6417cef53d66ca/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'BI','public_name'=>'Boundary Introduction','video'=>'Intro to Alchemy.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4dbe630d9a408df2356f6/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'EI','public_name'=>'Energy Pool Introduction','video'=>'Intro to Energy Pool.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68df5ac53232f7b8d6ffb88c/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'PLI','public_name'=>'Perception Life Introduction','video'=>'Perception of Life Intro.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68df5ab33232f7b8d6ffb429/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'CI','public_name'=>'Communication Introduction','video'=>'Intro to Communication Style.mp4','video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68df5ac3cb6417cef53d6a59/main.m3u8','created_at'=>now(),'updated_at'=>now()],
            ['code'=>null,'public_name'=>'connecting_communicating','slug_name'=>'Connecting & Communicating','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fcaf70352b0d4723ee/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'alchemical_revelation','slug_name'=>'Alchemical Revelation','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fcaf70352b0d4723ee/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'motivation','slug_name'=>'Motivation','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fcaf70352b0d4723ee/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'roadworthy','slug_name'=>'Roadworthy','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d1cd30d9a408df2226c7/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'power','slug_name'=>'Power','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fcb0bb97fd98109b79/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'midLife_transformation','slug_name'=>'Mid Life Transformation','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fc30d9a408df22ad66/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'awareness','slug_name'=>'Awareness','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d07db0bb97fd980ff897/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'payit_forward','slug_name'=>'Pay It Forward','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d1cd30d9a408df2226c4/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'liberated','slug_name'=>'Liberated','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d1cdb0bb97fd9810141f/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'being','slug_name'=>'Being','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fc30d9a408df22ad64/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'life_review','slug_name'=>'Life Review','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fcaf70352b0d4723ee/main.m3u8','created_at'=>now(),'updated_at'=>now()],

            ['code'=>null,'public_name'=>'surrender','slug_name'=>'Surrender','video'=>null,'video_upload_id'=>null,'video_embed_link'=>'https://video.gumlet.io/675260ac948718dd9422d8bb/68e4d7fcaf70352b0d4723ee/main.m3u8','created_at'=>now(),'updated_at'=>now()],
        ];

        $videos = array_map(function ($video) {
            if (!array_key_exists('slug_name', $video)) {
                $video['slug_name'] = null;
            }
            return $video;
        }, $videos);

        DB::table('assessment_result_videos')->insert($videos);
    }
}