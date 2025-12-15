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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            // On utilise la table 'users' pour les clients et les employés
            $table->foreignId('client_id')->comment('FK to users table with client role')->constrained('users')->onDelete('cascade');
            $table->foreignId('employee_id')->comment('FK to users table with employee role')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('price', 8, 2)->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled_by_client', 'cancelled_by_employee', 'no_show'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
