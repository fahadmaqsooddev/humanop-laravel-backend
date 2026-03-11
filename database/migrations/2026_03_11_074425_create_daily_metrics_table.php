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
        Schema::create('daily_metrics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->double('hrv_baseline')->nullable();
            $table->double('resting_hr')->nullable();
            $table->unsignedInteger('sleep_minutes')->nullable();
            $table->string('energy_pool_state', 32);
            $table->unsignedInteger('capacity_points');
            $table->timestamps();

            $table->unique(['user_id', 'date']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_metrics');
    }
};
