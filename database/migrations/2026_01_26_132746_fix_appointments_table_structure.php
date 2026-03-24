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
        Schema::table('appointments', function (Blueprint $table) {
            // Ajouter service_id si service_id n'existe pas
            if (!Schema::hasColumn('appointments', 'service_id')) {
                // Si la colonne 'service' existe (string), on va la remplacer
                if (Schema::hasColumn('appointments', 'service')) {
                    // Ajouter service_id comme nullable d'abord
                    $table->foreignId('service_id')->nullable()->after('employee_id');
                } else {
                    $table->foreignId('service_id')->nullable()->after('employee_id');
                }
            }
            
            // Ajouter date si elle n'existe pas
            if (!Schema::hasColumn('appointments', 'date')) {
                $table->date('date')->nullable()->after('service_id');
            }
            
            // Ajouter time si elle n'existe pas
            if (!Schema::hasColumn('appointments', 'time')) {
                $table->time('time')->nullable()->after('date');
            }
            
            // Ajouter notes si elle n'existe pas
            if (!Schema::hasColumn('appointments', 'notes')) {
                $table->text('notes')->nullable()->after('time');
            }
            
            // Ajouter reminder_sent si elle n'existe pas
            if (!Schema::hasColumn('appointments', 'reminder_sent')) {
                $table->boolean('reminder_sent')->default(false)->after('status');
            }
            
            // Ajouter reminder_sent_at si elle n'existe pas
            if (!Schema::hasColumn('appointments', 'reminder_sent_at')) {
                $table->timestamp('reminder_sent_at')->nullable()->after('reminder_sent');
            }
        });
        
        // Migrer les données de scheduled_at vers date et time si scheduled_at existe
        if (Schema::hasColumn('appointments', 'scheduled_at')) {
            DB::statement("
                UPDATE appointments 
                SET date = scheduled_at::date,
                    time = scheduled_at::time
                WHERE scheduled_at IS NOT NULL 
                AND date IS NULL
            ");
        }
        
        // Migrer les données de service (string) vers service_id si nécessaire
        if (Schema::hasColumn('appointments', 'service') && Schema::hasColumn('appointments', 'service_id')) {
            // Cette migration nécessiterait de faire correspondre les noms de services
            // Pour l'instant, on laisse service_id nullable
        }
        
        // Rendre service_id non nullable après migration (si on veut)
        // Schema::table('appointments', function (Blueprint $table) {
        //     $table->foreignId('service_id')->nullable(false)->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $columns = ['service_id', 'date', 'time', 'notes', 'reminder_sent', 'reminder_sent_at'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('appointments', $column)) {
                    // Supprimer la clé étrangère si elle existe
                    if ($column === 'service_id') {
                        try {
                            $table->dropForeign(['service_id']);
                        } catch (\Exception $e) {
                            // Ignorer si la clé étrangère n'existe pas
                        }
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
