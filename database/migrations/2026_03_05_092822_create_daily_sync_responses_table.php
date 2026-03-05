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
        Schema::create('daily_sync_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('daily_sync_sessions')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('daily_sync_questions')->cascadeOnDelete();
            $table->text('question_text');
            $table->text('response_text')->nullable();
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
        Schema::dropIfExists('daily_sync_responses');
    }
};
