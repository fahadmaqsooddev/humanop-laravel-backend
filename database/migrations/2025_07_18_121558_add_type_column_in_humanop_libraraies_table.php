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
        Schema::table('humanop_libraries', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('library_resource_id')->nullable();
            $table->foreign('library_resource_id')->references('id')->on('library_resources')->onDelete('cascade');
            $table->integer('type')->comment('1:shop 2:library-resource')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('humanop_libraries', function (Blueprint $table) {
            //
            $table->dropForeign('library_resource_id');
            $table->dropColumn('type');
            $table->dropColumn('library_resource_id');
        });
    }
};
