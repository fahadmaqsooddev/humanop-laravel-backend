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
        Schema::create('media_player_resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_player_category_id')->nullable();
            $table->unsignedBigInteger('video_id')->nullable();
            $table->unsignedBigInteger('audio_id')->nullable();
            $table->unsignedBigInteger('thumbnail_id')->nullable();
            $table->string('heading')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->integer('permission')->default(1)->comment('1: freemium, 2: premium');
            $table->integer('prices')->default(0);
            $table->integer('points')->default(0);

            $table->foreign('media_player_category_id')->references('id')->on('media_player_categories')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('uploads')->onDelete('cascade');
            $table->foreign('audio_id')->references('id')->on('uploads')->onDelete('cascade');
            $table->foreign('thumbnail_id')->references('id')->on('uploads')->onDelete('cascade');
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
        Schema::dropIfExists('media_player_resources');
    }
};
