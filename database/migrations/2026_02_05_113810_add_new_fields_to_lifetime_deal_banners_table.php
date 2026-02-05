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
        Schema::table('lifetime_deal_banners', function (Blueprint $table) {

            $table->string('title')->nullable()->after('title_for_freemium');
            $table->longText('description')->nullable()->after('description_for_freemium');

            $table->string('payment_url')->nullable()->after('description');

            $table->boolean('visible_on_mobile')->default(false)->after('payment_url');
            $table->boolean('visible_on_web')->default(false)->after('visible_on_mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lifetime_deal_banners', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'description',
                'payment_url',
                'visible_on_mobile',
                'visible_on_web',
            ]);
        });
    }
};
