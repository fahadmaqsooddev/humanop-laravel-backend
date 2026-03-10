<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_result_videos', function (Blueprint $table) {
            $table->unsignedBigInteger('image_id')->nullable()->after('video_embed_link');
            $table->foreign('image_id')->references('id')->on('uploads')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_result_videos', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
        });
    }
};

