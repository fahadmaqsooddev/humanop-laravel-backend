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
        Schema::create('answer_code_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('answer_code_id')->nullable();
            $table->unsignedBigInteger('number_id')->nullable();

            $table->foreign('answer_code_id')->references('id')->on('answer_codes')->onDelete('cascade');
            $table->foreign('number_id')->references('id')->on('code_numbers')->onDelete('cascade');
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
        Schema::dropIfExists('answer_code_numbers');
    }
};
