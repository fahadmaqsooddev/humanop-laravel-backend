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
        Schema::create('hai_chat_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->longText('prompt')->nullable();
            $table->longText('restriction')->nullable();
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
        Schema::dropIfExists('hai_chat_prompts');
    }
};
