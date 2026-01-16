<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('employees', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            if (!Schema::hasColumn('employees', 'specialties')) {
                $table->string('specialties')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('employees', 'work_start_time')) {
                $table->time('work_start_time')->default('09:00')->after('specialties');
            }
            if (!Schema::hasColumn('employees', 'work_end_time')) {
                $table->time('work_end_time')->default('18:00')->after('work_start_time');
            }
            if (!Schema::hasColumn('employees', 'work_days')) {
                $table->json('work_days')->nullable()->after('work_end_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['phone', 'is_active', 'specialties', 'work_start_time', 'work_end_time', 'work_days']);
        });
    }
};
