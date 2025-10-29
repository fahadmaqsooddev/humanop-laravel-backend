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
        Schema::table('feedbacks', function (Blueprint $table) {

            $table->string('blue_ticket_id')->nullable()->index();

            $table->string('blue_status')->nullable()->index();

            $table->text('blue_last_update')->nullable();

            $table->timestamp('blue_last_synced_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedbacks', function (Blueprint $table) {

            $table->dropColumn('blue_ticket_id');
            $table->dropColumn('blue_status');
            $table->dropColumn('blue_last_update');
            $table->dropColumn('blue_last_synced_at');

        });
    }
};
