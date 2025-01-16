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
        Schema::create('active_embeddings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('chat_bot_id')->nullable()->constrained();

            $table->unsignedBigInteger('knowledge_base_id')->nullable();
            $table->foreign('knowledge_base_id')->references('id')->on('knowledge_base');

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
        Schema::dropIfExists('active_embeddings');
    }
};
