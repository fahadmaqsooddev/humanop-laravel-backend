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
        Schema::create('playlist_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('playlist_id')->nullable();
            $table->unsignedBigInteger('resource_item_id')->nullable();
            $table->unsignedBigInteger('shop_item_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('playlist_id')->references('id')->on('playlist')->onDelete('cascade');
            $table->foreign('resource_item_id')->references('id')->on('library_resources')->onDelete('cascade');
            $table->foreign('shop_item_id')->references('id')->on('humanop_shop_resources')->onDelete('cascade');
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
        Schema::dropIfExists('playlist_log');
    }
};
