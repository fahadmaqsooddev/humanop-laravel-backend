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

            $table->string('key')->index();
            $table->enum('kind', ['recurring','one_time']);
            $table->string('product_name')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->string('context', 10)->default('b2c')->index();
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
            $table->dropColumn('product_name');
            $table->dropColumn('context');
        });
    }
};
