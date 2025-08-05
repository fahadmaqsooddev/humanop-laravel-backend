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
        Schema::table('assessment_intro', function (Blueprint $table) {


            $table->dropColumn('video');
            $table->dropColumn('p_name');

            $table->unsignedBigInteger('video_id')->nullable();

            $table->foreign('video_id')->references('id')->on('assessment_result_videos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_intro', function (Blueprint $table) {


            $table->string('video')->nullable();
            $table->string('p_name')->nullable();
            $table->dropConstrainedForeignId('video_id');

        });
    }
};
