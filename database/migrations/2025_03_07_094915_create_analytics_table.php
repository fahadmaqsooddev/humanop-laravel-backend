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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('llm_model_id')->nullable();
            $table->integer('prompt_token')->nullable();
            $table->integer('completion_token')->nullable();
            $table->integer('total_token')->nullable();
            $table->string('query')->nullable();

            $table->foreign('llm_model_id')->references('id')->on('llm_models')->onDelete('cascade');
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
        Schema::dropIfExists('analytics');
    }
};
