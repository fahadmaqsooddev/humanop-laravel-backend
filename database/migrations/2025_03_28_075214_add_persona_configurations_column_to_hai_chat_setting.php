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
        Schema::table('hai_chat_setting', function (Blueprint $table) {

            $table->string('persona_name')->nullable();
            $table->tinyInteger('human_op_app')->default(0)->nullable();
            $table->tinyInteger('maestro_app')->default(0)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hai_chat_setting', function (Blueprint $table) {

            $table->dropColumn(['persona_name','human_op_app','maestro_app']);

        });
    }
};
