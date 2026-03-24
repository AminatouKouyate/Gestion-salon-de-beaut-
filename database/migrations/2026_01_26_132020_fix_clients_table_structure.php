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
        Schema::table('clients', function (Blueprint $table) {
            // Ajouter les colonnes manquantes si elles n'existent pas
            if (!Schema::hasColumn('clients', 'name')) {
                $table->string('name')->after('id');
            }
            
            if (!Schema::hasColumn('clients', 'email')) {
                $table->string('email')->unique()->after('name');
            }
            
            if (!Schema::hasColumn('clients', 'password')) {
                $table->string('password')->after('email');
            }
            
            if (!Schema::hasColumn('clients', 'phone')) {
                $table->string('phone')->nullable()->after('password');
            }
            
            if (!Schema::hasColumn('clients', 'address')) {
                $table->string('address')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('clients', 'active')) {
                $table->boolean('active')->default(true)->after('address');
            }
            
            if (!Schema::hasColumn('clients', 'loyalty_points')) {
                $table->integer('loyalty_points')->default(0)->after('active');
            }
            
            if (!Schema::hasColumn('clients', 'total_appointments')) {
                $table->integer('total_appointments')->default(0)->after('loyalty_points');
            }
            
            if (!Schema::hasColumn('clients', 'remember_token')) {
                $table->string('remember_token', 100)->nullable()->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $columns = ['name', 'email', 'password', 'phone', 'address', 'active', 'loyalty_points', 'total_appointments', 'remember_token'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('clients', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
