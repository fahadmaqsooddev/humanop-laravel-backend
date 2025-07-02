<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('humanop_shop_resources', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['upload_id']);
        });

        Schema::table('humanop_shop_resources', function (Blueprint $table) {
            // Drop the columns after dropping foreign key
            $table->dropColumn([
                'description',
                'content',
                'source_id',
                'source_url',
                'embed_link',
                'upload_id',
            ]);
        });

        Schema::table('humanop_shop_resources', function (Blueprint $table) {
            // Add new columns and foreign keys
            $table->unsignedBigInteger('video_id')->nullable();
            $table->unsignedBigInteger('audio_id')->nullable();
            $table->unsignedBigInteger('document_id')->nullable();
            $table->string('point_price')->nullable();

            $table->foreign('video_id')->references('id')->on('uploads')->onDelete('cascade');
            $table->foreign('audio_id')->references('id')->on('uploads')->onDelete('cascade');
            $table->foreign('document_id')->references('id')->on('uploads')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('humanop_shop_resources', function (Blueprint $table) {
            // Drop the new foreign keys
            $table->dropForeign(['video_id']);
            $table->dropForeign(['audio_id']);
            $table->dropForeign(['document_id']);

            // Drop the new columns
            $table->dropColumn([
                'video_id',
                'audio_id',
                'document_id',
                'point_price',
            ]);

            // Re-add the old columns (optional, for rollback)
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('source_id')->nullable();
            $table->string('source_url')->nullable();
            $table->string('embed_link')->nullable();
            $table->unsignedBigInteger('upload_id')->nullable();

            $table->foreign('upload_id')->references('id')->on('uploads')->onDelete('cascade');
        });
    }
};
