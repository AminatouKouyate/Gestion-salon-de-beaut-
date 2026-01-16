<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'active')) {
                $table->boolean('active')->default(true)->after('phone');
            }
            if (!Schema::hasColumn('clients', 'address')) {
                $table->string('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('clients', 'loyalty_points')) {
                $table->integer('loyalty_points')->default(0)->after('active');
            }
            if (!Schema::hasColumn('clients', 'total_appointments')) {
                $table->integer('total_appointments')->default(0)->after('loyalty_points');
            }
        });

        // Set active = true for all existing clients
        \DB::table('clients')->whereNull('active')->update(['active' => true]);
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['active', 'address', 'loyalty_points', 'total_appointments']);
        });
    }
};
