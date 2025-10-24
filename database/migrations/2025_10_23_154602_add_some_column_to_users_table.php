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

            $table->string('plan')->nullable()->index()
                ->comment('freemium|premium_monthly|premium_yearly|premium_lifetime|bb_lifetime');
            $table->boolean('is_lifetime')->default(false)->index();
            $table->boolean('has_bb_onetime')->default(false)->index();
            $table->string('billing_context', 10)->nullable()->index()
                ->comment('b2c|b2b');
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
            $table->dropColumn('billing_context');

        });
    }
};
