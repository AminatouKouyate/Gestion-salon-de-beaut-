<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'promotion_price')) {
                $table->decimal('promotion_price', 10, 2)->nullable()->after('price');
                $table->date('promotion_start')->nullable();
                $table->date('promotion_end')->nullable();
                $table->string('promotion_label')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['promotion_price', 'promotion_start', 'promotion_end', 'promotion_label']);
        });
    }
};
