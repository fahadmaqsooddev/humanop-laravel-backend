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
        Schema::table('assessment_result_videos', function (Blueprint $table) {
           $table->string('slug_name')->nullable()->after('public_name')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_result_videos', function (Blueprint $table) {
            $table->dropUnique(['slug_name']); 
            $table->dropColumn('slug_name');
        });
    }
};
