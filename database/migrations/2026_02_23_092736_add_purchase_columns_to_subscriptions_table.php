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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('purchase_id')
                ->nullable()
                ->unique()
                ->after('stripe_price');

            $table->string('purchase_name')
                ->nullable()
                ->after('purchase_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropUnique(['purchase_id']);
            $table->dropColumn(['purchase_id', 'purchase_name']);
        });
    }
};
