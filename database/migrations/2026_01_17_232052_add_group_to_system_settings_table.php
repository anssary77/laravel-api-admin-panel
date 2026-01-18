<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->string('group')->default('general')->after('key');
            $table->text('description')->nullable()->after('group');
            $table->json('options')->nullable()->after('type');
            $table->boolean('is_required')->default(false)->after('options');
            
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropColumn(['group', 'description', 'options', 'is_required']);
        });
    }
};
