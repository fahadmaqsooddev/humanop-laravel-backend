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

            $table->string('shared_title')->nullable()->after('title_for_freemium');
            $table->longText('shared_description')->nullable()->after('description_for_freemium');

            $table->string('freemium_url')->nullable()->after('shared_description');
            $table->string('beta_breaker_url')->nullable()->after('freemium_url');

            $table->boolean('visible_on_mobile')->default(false)->after('beta_breaker_url');
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
                'shared_title',
                'shared_description',
                'freemium_url',
                'beta_breaker_url',
                'visible_on_mobile',
                'visible_on_web',
            ]);
        });
    }
};
