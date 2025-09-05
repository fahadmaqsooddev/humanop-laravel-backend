<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('humanop_shop_resources', function (Blueprint $table) {
            $table->longText('description')->after('heading')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('humanop_shop_resources', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
