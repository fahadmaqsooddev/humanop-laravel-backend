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
        Schema::table('email_templates', function (Blueprint $table) {

            $table->string('tag')->nullable();
            $table->longText('body')->nullable();
            $table->integer('type')->nullable();
            $table->unsignedBigInteger('logo_upload_id')->nullable();
            $table->foreign('logo_upload_id')->references('id')->on('uploads')->onDelete('cascade');
            $table->string('subject')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {


            $table->dropForeign(['logo_upload_id']);
            $table->dropColumn(['tag','body','type','logo_upload_id','subject']);

        });
    }
};
