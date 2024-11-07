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
        Schema::table('daily_tips', function (Blueprint $table) {
            // Drop the foreign key constraint (ensure you provide the correct constraint name)
            $table->dropForeign(['user_id']);

            // Drop the columns
            $table->dropColumn('user_id');
            $table->dropColumn('is_read');
            $table->dropColumn('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_tips', function (Blueprint $table) {
            // Re-add the foreign key column
            $table->foreignId('user_id')->nullable()->constrained();

            // Re-add other columns
            $table->tinyInteger('is_read')->default(0)->nullable();
            $table->longText('text')->nullable();
        });
    }
};
