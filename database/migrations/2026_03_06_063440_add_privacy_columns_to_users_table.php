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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('bio_privacy')->default(true)->after('bio')->comment('1: share 0: not share');
            $table->tinyInteger('personal_quote_connection_privacy')->default(1)->after('bio_privacy')->comment('1: share 0: not share');
            $table->tinyInteger('personal_quote_public_privacy')->default(0)->after('personal_quote_connection_privacy')->comment('1: share 0: not share');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio_privacy', 'personal_quote_connection_privacy', 'personal_quote_public_privacy']);
        });
    }
};
