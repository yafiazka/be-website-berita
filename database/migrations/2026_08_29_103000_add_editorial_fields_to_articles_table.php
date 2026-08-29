<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'author_source')) {
                $table->string('author_source')->nullable()->after('author_id');
            }
            if (!Schema::hasColumn('articles', 'source')) {
                $table->string('source')->nullable()->after('author_source');
            }
            if (!Schema::hasColumn('articles', 'video_url')) {
                $table->string('video_url')->nullable()->after('thumbnail');
            }
            if (!Schema::hasColumn('articles', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('articles', 'author_source')) {
                $columns[] = 'author_source';
            }
            if (Schema::hasColumn('articles', 'source')) {
                $columns[] = 'source';
            }
            if (Schema::hasColumn('articles', 'video_url')) {
                $columns[] = 'video_url';
            }
            if (Schema::hasColumn('articles', 'is_featured')) {
                $columns[] = 'is_featured';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
