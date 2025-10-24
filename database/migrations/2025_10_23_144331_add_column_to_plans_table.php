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
        Schema::table('plans', function (Blueprint $table) {

            $table->string('key')->nullable();
            $table->string('kind')->nullable();
            $table->tinyInteger('active')->default(1)->comment('1 => active, 2 => inactive');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {

            $table->dropColumn('key');
            $table->dropColumn('kind');
            $table->dropColumn('active');
        });
    }
};
