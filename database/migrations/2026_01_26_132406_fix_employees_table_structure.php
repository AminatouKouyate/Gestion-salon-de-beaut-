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
        Schema::table('employees', function (Blueprint $table) {
            // Ajouter les colonnes manquantes si elles n'existent pas
            if (!Schema::hasColumn('employees', 'name')) {
                $table->string('name')->after('id');
            }
            
            if (!Schema::hasColumn('employees', 'email')) {
                $table->string('email')->unique()->after('name');
            }
            
            if (!Schema::hasColumn('employees', 'password')) {
                $table->string('password')->after('email');
            }
            
            if (!Schema::hasColumn('employees', 'role')) {
                $table->string('role')->default('employee')->after('password');
            }
            
            if (!Schema::hasColumn('employees', 'remember_token')) {
                $table->string('remember_token', 100)->nullable()->after('password');
            }
            
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $columns = [
                'name', 'email', 'password', 'role', 'remember_token',
                'phone', 'is_active', 'specialties', 'work_start_time', 
                'work_end_time', 'work_days'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('employees', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
