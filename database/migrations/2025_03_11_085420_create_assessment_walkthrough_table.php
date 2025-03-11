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
        Schema::create('assessment_walkthrough', function (Blueprint $table) {
            $table->id();
            $table->string('code_name')->nullable();
            $table->string('title')->nullable();
            $table->longText('overview')->nullable();
            $table->longText('optimal')->nullable();
            $table->longText('optimization')->nullable();
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
        Schema::dropIfExists('assessment_walkthrough');
    }
};
