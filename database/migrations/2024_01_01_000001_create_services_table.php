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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable(); // From 2025_12_10_100000
            $table->decimal('price', 10, 2); // From 2025_12_10_100000
            $table->integer('duration')->comment('Duration in minutes'); // From 2025_12_10_100000
            $table->string('category')->nullable(); // From 2025_12_10_100000
            $table->boolean('active')->default(true); // From 2025_12_10_100000
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
