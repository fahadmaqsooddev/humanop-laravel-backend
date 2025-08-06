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
        Schema::table('playlist_log', function (Blueprint $table) {

            $table->unsignedBigInteger('podcast_id')->nullable();

            $table->foreign('podcast_id')->references('id')->on('podcast')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlist_log', function (Blueprint $table) {

            $table->dropConstrainedForeignId('podcast_id');

        });
    }
};
