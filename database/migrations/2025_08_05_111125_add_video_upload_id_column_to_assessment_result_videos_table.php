<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_result_videos', function (Blueprint $table) {

            $table->unsignedBigInteger('video_upload_id')->nullable();

            $table->foreign('video_upload_id')->references('id')->on('uploads')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_result_videos', function (Blueprint $table) {

            $table->dropConstrainedForeignId('video_upload_id');

        });
    }
};
