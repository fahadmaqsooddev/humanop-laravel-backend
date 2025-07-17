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
        Schema::table('notifications', function (Blueprint $table) {
            //
            $table->integer('notification_priority')->default(null)->comment('1: daily tip, 2: reset assessment, 3: training & resource, 4: Network Connection 9: message sent, 10: new message ')->change();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            //
            $table->integer('notification_priority')->default(null)->comment('1: daily tip, 2: reset assessment, 3: training & resource, 4: connection request, 5: connection accept, 6: connection cancel, 7: follow request, 8: un-follow request, 9: message sent, 10: new message ')->change();
            $table->dropForeign('sender_id');
            $table->dropColumn('sender_id');
        });
    }
};
