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
        Schema::table('activity_log', function (Blueprint $table) {
            $table->string('action_title')->nullable();
            $table->text('action_description')->nullable();
            $table->string('url')->nullable()->after('action_description');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {

            $table->dropColumn('action_title');
            $table->dropColumn('action_description');
            $table->dropColumn('url');
        });
    }
};
