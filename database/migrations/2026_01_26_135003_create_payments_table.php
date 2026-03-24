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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
        $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
        $table->decimal('amount', 10, 2);
        $table->string('status'); // pending, completed, etc.
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
