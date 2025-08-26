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
        Schema::create('assessment_video_track', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('video_name')->nullable();
            $table->string('video_time')->nullable();

            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessment_video_track');
    }
};
