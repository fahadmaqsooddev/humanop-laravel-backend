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
        Schema::table('assessments', function (Blueprint $table) {
            // Make columns nullable
            $table->integer('page')->nullable()->default(null)->change();
            $table->integer('web_page')->nullable()->default(null)->change();
            $table->integer('app_page')->nullable()->default(null)->change();

            $table->index('page', 'idx_assessments_page');
            $table->index('web_page', 'idx_assessments_web_page');
            $table->index('app_page', 'idx_assessments_app_page');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('assessments')->whereNull('page')->update(['page' => 0]);
        DB::table('assessments')->whereNull('web_page')->update(['web_page' => 0]);
        DB::table('assessments')->whereNull('app_page')->update(['app_page' => 0]);

        Schema::table('assessments', function (Blueprint $table) {

            $table->integer('page')->nullable(false)->change();
            $table->integer('web_page')->nullable(false)->change();
            $table->integer('app_page')->nullable(false)->change();


            $table->dropIndex('idx_assessments_page');
            $table->dropIndex('idx_assessments_web_page');
            $table->dropIndex('idx_assessments_app_page');
        });
    }
};
