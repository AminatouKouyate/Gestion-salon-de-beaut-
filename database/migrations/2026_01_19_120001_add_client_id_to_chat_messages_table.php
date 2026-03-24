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
        if (!Schema::hasTable('chat_messages')) {
            return;
        }

        if (!Schema::hasColumn('chat_messages', 'client_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->foreignId('client_id')->nullable()->constrained()->onDelete('cascade')->after('id');
                $table->index(['client_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('chat_messages')) {
            return;
        }

        if (Schema::hasColumn('chat_messages', 'client_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                // Try to drop foreign key if exists (some DB drivers may require different names)
                try {
                    $table->dropForeign(['client_id']);
                } catch (\Exception $e) {
                    // ignore
                }
                $table->dropIndex(['chat_messages_client_id_created_at_index']);
                $table->dropColumn('client_id');
            });
        }
    }
};
