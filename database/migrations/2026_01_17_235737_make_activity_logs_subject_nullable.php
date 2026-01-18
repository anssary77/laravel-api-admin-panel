<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('activity_logs')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->string('subject_id', 36)->nullable()->change();
                $table->string('subject_type', 255)->nullable()->change();
            });
            return;
        }

        DB::statement('ALTER TABLE activity_logs MODIFY subject_id VARCHAR(36) NULL');
        DB::statement('ALTER TABLE activity_logs MODIFY subject_type VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('activity_logs')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->string('subject_id', 36)->nullable(false)->change();
                $table->string('subject_type', 255)->nullable(false)->change();
            });
            return;
        }

        DB::statement('ALTER TABLE activity_logs MODIFY subject_id VARCHAR(36) NOT NULL');
        DB::statement('ALTER TABLE activity_logs MODIFY subject_type VARCHAR(255) NOT NULL');
    }
};
