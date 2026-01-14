<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTables extends Migration
{
    public function up()
    {
        // Index for filtering connections quickly
        Schema::table('connections', function (Blueprint $table) {
            $table->index('status', 'connection_status');
        });

        // Index for user filtering
        Schema::table('users', function (Blueprint $table) {
            $table->index(['profile_privacy', 'b2b_deleted_at', 'is_admin'], 'idx_users_privacy_deleted_admin');
        });

        // Full-text index for name search
        Schema::table('users', function (Blueprint $table) {
            $table->fullText(['first_name', 'last_name'], 'idx_users_name');
        });
    }

    public function down()
    {

        Schema::table('connection_requests', function (Blueprint $table) {
            $table->dropIndex('connection_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_privacy_deleted_admin');
            $table->dropFullText('idx_users_name');
        });
    }
}
