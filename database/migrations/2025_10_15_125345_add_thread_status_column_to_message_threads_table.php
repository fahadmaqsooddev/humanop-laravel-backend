<?php

use App\Models\Client\MessageThread\MessageThread;
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
        Schema::table('message_threads', function (Blueprint $table) {

            $table->integer('thread_privacy')->default(0)->comment('0: public, 1: private');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_threads', function (Blueprint $table) {

            $table->dropColumn('thread_privacy');

        });
    }
};
