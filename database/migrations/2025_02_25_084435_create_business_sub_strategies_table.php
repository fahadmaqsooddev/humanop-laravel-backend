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
        Schema::create('business_sub_strategies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_strategy_id')->nullable();
            $table->string('name');

            $table->foreign('business_strategy_id')->references('id')->on('business_strategies')->onDelete('cascade');
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
        Schema::dropIfExists('business_sub_strategies');
    }
};
