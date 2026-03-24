<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            // Ajouter name si elle n'existe pas
            if (!Schema::hasColumn('stocks', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            
            // Ajouter category si elle n'existe pas
            if (!Schema::hasColumn('stocks', 'category')) {
                $table->string('category')->nullable()->after('name');
            }
            
            // Ajouter alert_threshold si elle n'existe pas
            if (!Schema::hasColumn('stocks', 'alert_threshold')) {
                $table->integer('alert_threshold')->default(0)->after('quantity');
            }
        });
        
        // Migrer les données de product_name vers name si product_name existe
        if (Schema::hasColumn('stocks', 'product_name')) {
            DB::statement("
                UPDATE stocks 
                SET name = product_name
                WHERE name IS NULL AND product_name IS NOT NULL
            ");
        }
        
        // Migrer les données de alert_quantity vers alert_threshold si alert_quantity existe
        if (Schema::hasColumn('stocks', 'alert_quantity')) {
            DB::statement("
                UPDATE stocks 
                SET alert_threshold = alert_quantity
                WHERE alert_threshold = 0 AND alert_quantity IS NOT NULL
            ");
        }
        
        // Rendre name non nullable après migration
        Schema::table('stocks', function (Blueprint $table) {
            if (Schema::hasColumn('stocks', 'name')) {
                $table->string('name')->nullable(false)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $columns = ['name', 'category', 'alert_threshold'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('stocks', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
