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
        Schema::table('business_candidates', function (Blueprint $table) {
            //
            $table->tinyInteger('future_consideration')->nullable()->default(0)->comment('1: future consider, 0: not future consider');
            $table->tinyInteger('is_permanently_deleted')->nullable()->default(0)->comment('1: permanently deleted, 0: not permanently deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_candidates', function (Blueprint $table) {
            //

            $table->dropColumn(['future_consideration', 'is_permanently_deleted']);

        });
    }
};
