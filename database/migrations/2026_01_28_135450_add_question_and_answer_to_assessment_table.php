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
        Schema::table('assessment_details', function (Blueprint $table) {
            $table->unsignedBigInteger('question_id')->nullable()->after('assessment_id');
            $table->unsignedBigInteger('answer_id')->nullable()->after('question_id');

            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');

            $table->foreign('answer_id')
                ->references('id')
                ->on('answers')
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
        Schema::table('assessment_details', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
            $table->dropForeign(['answer_id']);
            $table->dropColumn(['question_id', 'answer_id']);
        });
    }
};
