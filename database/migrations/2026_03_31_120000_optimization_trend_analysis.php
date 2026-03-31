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
        Schema::create('optimization_trend_analysis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('current_assessment_id')->index();
            $table->unsignedBigInteger('previous_assessment_id')->index();
            $table->text('context')->nullable();
            $table->longText('ai_response')->nullable();
            $table->timestamps();
//            $table->unique(['user_id', 'current_assessment_id', 'previous_assessment_id'], 'optimization_trend_analysis_unique');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('current_assessment_id')
                ->references('id')
                ->on('assessments')
                ->onDelete('cascade');

            $table->foreign('previous_assessment_id')
                ->references('id')
                ->on('assessments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('optimization_trend_analysis');
    }
};
