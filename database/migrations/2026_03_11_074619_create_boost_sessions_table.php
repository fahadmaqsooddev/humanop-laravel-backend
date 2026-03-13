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
        Schema::create('boost_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('protocol_type', 64);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();

            $table->double('hr_before')->nullable();
            $table->double('hr_after')->nullable();

            $table->double('hrv_before')->nullable();
            $table->double('hrv_after')->nullable();

            $table->double('q_physio')->nullable();
            $table->unsignedInteger('energy_points_added')->nullable();
            $table->double('replenishment_percent')->nullable();

            $table->string('trait_modifier_key', 64)->nullable();
            $table->string('driver_modifier_key', 64)->nullable();
            $table->double('trait_modifier_value')->nullable();
            $table->double('driver_modifier_value')->nullable();

            $table->boolean('coherence_achieved')->default(false);

            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'protocol_type', 'started_at'], 'boost_user_protocol_time_idx');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boost_sessions');
    }
};
