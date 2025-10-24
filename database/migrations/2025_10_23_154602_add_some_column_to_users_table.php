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

            $table->string('plan')->default("Freemium")->comment("Freemium, Premium, Premium Lifetime");
            $table->boolean('is_lifetime')->default(false);
            $table->boolean('has_bb_onetime')->default(false);

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

            $table->dropColumn('plan');
            $table->dropColumn('is_lifetime');
            $table->dropColumn('has_bb_onetime');

        });
    }
};
