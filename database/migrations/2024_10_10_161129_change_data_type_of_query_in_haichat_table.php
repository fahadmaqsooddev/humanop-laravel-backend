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
        Schema::table('haichat', function (Blueprint $table) {

            $table->longText('query')->change();
            $table->longText('answer')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('haichat', function (Blueprint $table) {

            $table->text('query')->change();
            $table->text('answer')->change();

        });
    }
};
