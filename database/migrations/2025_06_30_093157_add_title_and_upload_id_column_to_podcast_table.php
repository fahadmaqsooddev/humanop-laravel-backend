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
        Schema::table('podcast', function (Blueprint $table) {

            $table->dropColumn('embedded_url');
            $table->dropConstrainedForeignId('user_id');

            $table->string('title')->nullable();
            $table->unsignedBigInteger('audio_id')->nullable();

            $table->foreign('audio_id')->references('id')->on('uploads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('podcast', function (Blueprint $table) {

            $table->text('embedded_url')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();

            $table->dropColumn('title');
            $table->dropConstrainedForeignId('audio_id');
        });
    }
};
