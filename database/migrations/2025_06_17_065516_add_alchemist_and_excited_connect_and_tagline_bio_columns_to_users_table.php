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
        Schema::table('users', function (Blueprint $table) {

           $table->string('life_alchemist')->nullable();
           $table->string('excited_connect')->nullable();
           $table->text('note')->nullable();
           $table->string('profile_privacy')->default(1)->comment('1: everyone, 2: my connection, 3: only me');
           $table->string('hai_privacy')->default(1)->comment('1: everyone, 2: my connection, 3: only me');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('life_alchemist');
            $table->dropColumn('excited_connect');
            $table->dropColumn('note');

        });
    }
};
