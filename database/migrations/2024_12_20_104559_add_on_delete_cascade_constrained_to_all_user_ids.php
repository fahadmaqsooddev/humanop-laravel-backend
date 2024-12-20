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
        Schema::table('point_logs', function (Blueprint $table) {

            $table->dropForeign('point_logs_user_id_foreign');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('points', function (Blueprint $table) {

            $table->dropForeign('points_user_id_foreign');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('action_plans', function (Blueprint $table) {

            $table->dropForeign('action_plans_user_id_foreign');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('user_daily_tips', function (Blueprint $table) {

            $table->dropForeign('user_daily_tips_user_id_foreign');
            $table->dropForeign('user_daily_tips_assessment_id_foreign');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');

        });

        Schema::table('connections', function (Blueprint $table) {

            Schema::disableForeignKeyConstraints();

            $table->dropForeign('connections_friend_id_foreign');

            $table->foreign('friend_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('connections', function (Blueprint $table) {

            $table->dropForeign('connections_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('follows', function (Blueprint $table) {

            Schema::disableForeignKeyConstraints();

            $table->dropForeign('follows_user_id_foreign');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('follows', function (Blueprint $table) {

            Schema::disableForeignKeyConstraints();

            $table->dropForeign('follows_follow_id_foreign');

            $table->foreign('follow_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('message_threads', function (Blueprint $table) {

            Schema::disableForeignKeyConstraints();

            $table->dropForeign('message_threads_receiver_id_foreign');

            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');

        });

//        Schema::table('messages', function (Blueprint $table) {
//
//            Schema::disableForeignKeyConstraints();
//
//            $table->dropForeign('messages_message_thread_id_foreign');
//
//            $table->foreign('message_thread_id')->references('id')->on('message_threads')->onDelete('cascade');
//
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
