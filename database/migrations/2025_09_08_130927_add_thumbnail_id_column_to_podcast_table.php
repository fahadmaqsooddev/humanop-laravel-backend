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
        Schema::table('podcast', function (Blueprint $table) {

            $table->unsignedBigInteger('thumbnail_id')->nullable();

            $table->foreign('thumbnail_id')->references('id')->on('uploads')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('podcast', function (Blueprint $table) {

            $table->dropForeign('podcast_thumbnail_id');
            $table->dropColumn('thumbnail_id');

        });
    }
};
