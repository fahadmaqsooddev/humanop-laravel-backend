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
        Schema::create('biometric_samples', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('metric', 64);
            $table->double('value');
            $table->timestamp('recorded_at');
            $table->string('source', 64)->default('apple_health');
            $table->string('dedupe_key', 64)->unique();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'metric', 'recorded_at'], 'bio_user_metric_time_idx');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('biometric_samples');
    }
};
