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
        Schema::create('version_control_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('version_id')->nullable();
            $table->text('description')->nullable();
            $table->text('platform')->nullable();
            $table->foreign('version_id')->references('id')->on('version_control')->onDelete('cascade');
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
        Schema::dropIfExists('version_control_descriptions');
    }
};
