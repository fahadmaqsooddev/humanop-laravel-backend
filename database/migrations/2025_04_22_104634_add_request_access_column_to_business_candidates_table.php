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
            $table->string('request_access')->comment('0:not send request 1:request send')->default(0);
            $table->integer('future_consideration_share_date')->comment('0:not share 1:share')->default(0);
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
            $table->dropColumn(['request_access','future_consideration_share_date']);
            
        });
    }
};
