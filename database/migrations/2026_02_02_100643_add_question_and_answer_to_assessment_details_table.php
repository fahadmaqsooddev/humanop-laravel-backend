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

            $table->unique(
                ['user_id', 'assessment_id', 'question_id', 'answer_id'],
                'assessment_details_unique_answer'
            );

            $table->index('question_id');
            $table->index('answer_id');
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
            // Drop foreign keys
            $table->dropForeign(['question_id']);
            $table->dropForeign(['answer_id']);

            // Drop unique constraint (CUSTOM NAME)
            $table->dropUnique('assessment_details_unique_answer');

            // Drop indexes (AUTO NAMES)
            $table->dropIndex('assessment_details_question_id_index');
            $table->dropIndex('assessment_details_answer_id_index');

            // Drop columns
            $table->dropColumn(['question_id', 'answer_id']);
        });
    }
};
