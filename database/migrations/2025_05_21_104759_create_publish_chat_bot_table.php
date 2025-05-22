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
        Schema::create('published_chat_bot', function (Blueprint $table) {

            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->float('temperature')->nullable();
            $table->unsignedSmallInteger('max_tokens')->nullable();
            $table->tinyInteger('chunks')->nullable();
            $table->tinyInteger('model_type')->nullable();
            $table->tinyInteger('is_connected')->default(0);
            $table->string('persona_name')->nullable();
            $table->text('prompt')->nullable();
            $table->text('restriction')->nullable();
            $table->json('embedding_ids')->nullable();
            $table->json('restricted_keywords')->nullable();
            $table->foreignId('chat_bot_id')->nullable()->constrained();
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
        Schema::dropIfExists('published_chat_bot');
    }
};
