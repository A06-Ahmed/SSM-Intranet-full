<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->index(['is_published', 'published_at'], 'announcements_published_idx');
            $table->index('created_at', 'announcements_created_at_idx');
        });

        Schema::table('gallery', function (Blueprint $table) {
            $table->index('created_at', 'gallery_created_at_idx');
            $table->index('uploaded_by', 'gallery_uploaded_by_idx');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->index('gallery_id', 'gallery_images_gallery_id_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index('created_at', 'notifications_created_at_idx');
            $table->index('type', 'notifications_type_idx');
            $table->index('expires_at', 'notifications_expires_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex('announcements_published_idx');
            $table->dropIndex('announcements_created_at_idx');
        });

        Schema::table('gallery', function (Blueprint $table) {
            $table->dropIndex('gallery_created_at_idx');
            $table->dropIndex('gallery_uploaded_by_idx');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropIndex('gallery_images_gallery_id_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_created_at_idx');
            $table->dropIndex('notifications_type_idx');
            $table->dropIndex('notifications_expires_at_idx');
        });
    }
};
