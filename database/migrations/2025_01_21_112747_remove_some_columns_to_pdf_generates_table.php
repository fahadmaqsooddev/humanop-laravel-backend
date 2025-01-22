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
        Schema::table('pdf_generates', function (Blueprint $table) {

            $table->dropColumn(['public_name', 'text']);
            $table->integer('code_number')->nullable();

            $table->unsignedBigInteger('code_detail_id')->nullable();

            $table->foreign('code_detail_id')->references('id')->on('code_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pdf_generates', function (Blueprint $table) {


            $table->string('public_name')->nullable();
            $table->longText('text')->nullable();

            $table->dropColumn('code_number');
            $table->dropForeign(['code_detail_id']);
            $table->dropColumn('code_detail_id');
        });
    }
};
