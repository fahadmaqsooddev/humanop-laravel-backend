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
        Schema::table('suggested_items', function (Blueprint $table) {
            $table->string('module_type')->nullable()->comment('for user to visit specific module related to suggested item');  // for user to visit specific module related to suggested item
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suggested_items', function (Blueprint $table) {
            $table->dropColumn('module_type');
        });
    }
};
