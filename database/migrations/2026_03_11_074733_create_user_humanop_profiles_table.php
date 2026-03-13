<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_humanop_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->string('trait', 64)->nullable();
            $table->string('pilot_driver', 64)->nullable();
            $table->string('copilot_driver', 64)->nullable();
            $table->string('interval', 64)->nullable();

            $table->string('energy_pool_state', 32)->default('average');

            $table->json('preferences')->nullable();
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
        Schema::dropIfExists('user_humanop_profiles');
    }
};
