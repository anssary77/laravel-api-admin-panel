<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('activity_logs')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            Schema::table('activity_logs', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('causer_id', 36)->nullable()->change();
                $table->string('causer_type', 255)->nullable()->change();
            });
            return;
        }

        DB::statement('ALTER TABLE activity_logs MODIFY causer_id VARCHAR(36) NULL');
        DB::statement('ALTER TABLE activity_logs MODIFY causer_type VARCHAR(255) NULL');
    }

    public function down(): void
    {
        if (!Schema::hasTable('activity_logs')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            Schema::table('activity_logs', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('causer_id', 36)->nullable(false)->change();
                $table->string('causer_type', 255)->nullable(false)->change();
            });
            return;
        }

        DB::statement('ALTER TABLE activity_logs MODIFY causer_id VARCHAR(36) NOT NULL');
        DB::statement('ALTER TABLE activity_logs MODIFY causer_type VARCHAR(255) NOT NULL');
    }
};
