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
        Schema::create('user_share_assessment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('interval_of_life')->default(1)->comment('1: share, 2: not share');
            $table->integer('traits')->default(1)->comment('1: share, 2: not share');
            $table->integer('motivational_driver')->default(1)->comment('1: share, 2: not share');
            $table->integer('alchemic_boundaries')->default(1)->comment('1: share, 2: not share');
            $table->integer('communication_style')->default(1)->comment('1: share, 2: not share');
            $table->integer('perception_of_life')->default(1)->comment('1: share, 2: not share');
            $table->integer('energy_pool')->default(1)->comment('1: share, 2: not share');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('user_share_assessment');
    }
};
