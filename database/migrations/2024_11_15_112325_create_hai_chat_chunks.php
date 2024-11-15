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
        Schema::create('hai_chat_chunks', function (Blueprint $table) {
            $table->id();
            $table->string('chatbot')->nullable();
            $table->string('embedding')->nullable();
            $table->string('query')->nullable();
            $table->longText('retrieved_docs')->nullable();
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
        Schema::dropIfExists('hai_chat_chunks');
    }
};
