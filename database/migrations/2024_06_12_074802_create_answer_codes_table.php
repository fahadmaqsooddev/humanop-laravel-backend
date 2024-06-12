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
        Schema::create('answer_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('answer_id')->nullable();
            $table->unsignedBigInteger('code_id')->nullable();

            $table->foreign('answer_id')->references('id')->on('answers')->onDelete('cascade');
            $table->foreign('code_id')->references('id')->on('codes')->onDelete('cascade');
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
        Schema::dropIfExists('answer_codes');
    }
};
