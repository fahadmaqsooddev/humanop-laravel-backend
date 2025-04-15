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
        Schema::create('brain_clusters', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('chat_bot_id')->nullable();
            $table->unsignedBigInteger('cluster_id')->nullable();

            $table->foreign('chat_bot_id')->references('id')->on('chatbot');
            $table->foreign('cluster_id')->references('id')->on('embedding_groups');

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
        Schema::dropIfExists('brain_clusters');
    }
};
