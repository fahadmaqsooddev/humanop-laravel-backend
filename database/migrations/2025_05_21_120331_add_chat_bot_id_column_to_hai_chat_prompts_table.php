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
        Schema::table('hai_chat_prompts', function (Blueprint $table) {

            $table->foreignId('chat_bot_id')->nullable()->constrained();

            $table->string('persona_name')->nullable();

            $table->tinyInteger('human_op_app')->nullable();

            $table->tinyInteger('maestro_app')->nullable();

            $table->tinyInteger('is_training')->nullable();

            $table->unsignedBigInteger('maestro_app_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hai_chat_prompts', function (Blueprint $table) {

            $table->dropConstrainedForeignId('chat_bot_id');

            $table->dropColumn(['persona_name','maestro_app','human_op_app','is_training','maestro_app_id']);

        });
    }
};
