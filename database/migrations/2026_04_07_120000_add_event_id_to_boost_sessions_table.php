<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boost_sessions', function (Blueprint $table) {

            if (!Schema::hasColumn('boost_sessions', 'event_id')) {

                $table->foreignId('event_id')
                    ->after('user_id')
                    ->constrained('events')
                    ->cascadeOnDelete();
            }

            $indexExists = collect(
                DB::select("SHOW INDEX FROM boost_sessions WHERE Key_name = 'boost_event_id_idx'")
            )->isNotEmpty();

            if (!$indexExists) {
                $table->index('event_id', 'boost_event_id_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('boost_sessions', function (Blueprint $table) {
            $table->dropForeign('event_id');
            $table->dropIndex('boost_event_id_idx');
            $table->dropColumn('event_id');
        });
    }
};
