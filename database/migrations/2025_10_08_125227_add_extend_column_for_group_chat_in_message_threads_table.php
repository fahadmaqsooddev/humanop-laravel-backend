<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_threads', function (Blueprint $table) {

            if (!Schema::hasColumn('message_threads', 'type')) {
                $table->tinyInteger('type')->default(0)->index()->comment('0 => Direct, 1 => Group');
            }
            if (!Schema::hasColumn('message_threads', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('message_threads', 'group_icon_id')) {
                $table->unsignedBigInteger('group_icon_id')->nullable()->index();
                $table->foreign('group_icon_id')->references('id')->on('uploads')->nullOnDelete();
            }
            if (!Schema::hasColumn('message_threads', 'owner_id')) {
                $table->unsignedBigInteger('owner_id')->nullable()->index();
                $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('message_threads', 'direct_key')) {
                $table->string('direct_key')->nullable()->unique()->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_threads', function (Blueprint $table) {
            if (Schema::hasColumn('message_threads', 'direct_key')) $table->dropColumn('direct_key');
            if (Schema::hasColumn('message_threads', 'owner_id')) {
                $table->dropConstrainedForeignId('owner_id');
            }
            if (Schema::hasColumn('message_threads', 'group_icon_id')) {
                $table->dropConstrainedForeignId('group_icon_id');
            }
            if (Schema::hasColumn('message_threads', 'name')) $table->dropColumn('name');
            if (Schema::hasColumn('message_threads', 'type')) $table->dropColumn('type');
        });
    }
};
