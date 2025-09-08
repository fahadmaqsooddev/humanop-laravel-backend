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
        Schema::table('playlist', function (Blueprint $table) {

//            $table->unsignedBigInteger('image_id')->nullable();
//
//            $table->foreign('image_id')->references('id')->on('uploads')->onDelete('cascade');

            $table->dropConstrainedForeignId('audio_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlist', function (Blueprint $table) {

//            $table->dropConstrainedForeignId('image_id');
//            $table->dropColumn('image_id');

//            $table->unsignedBigInteger('audio_id')->nullable();
//            $table->foreign('audio_id')->references('id')->on('uploads')->onDelete('cascade');

        });
    }
};
