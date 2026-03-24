<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            if (Schema::hasColumn('stocks', 'product_name')) {
                $table->string('product_name')->nullable()->change();
            }
            if (Schema::hasColumn('stocks', 'alert_quantity')) {
                $table->integer('alert_quantity')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        // On ne peut pas facilement revenir en arrière sur nullable
    }
};
