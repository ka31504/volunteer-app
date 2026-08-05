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
        // Biểu đồ đóng góp theo tháng query trên 3 cột này
        Schema::table('donations', function (Blueprint $table) {
            $table->index(['type', 'donated_at']);
        });

        // Dashboard và statistics đều filter status
        Schema::table('projects', function (Blueprint $table) {
            $table->index(['status', 'current_amount']);
        });

        // Filter active participants
        Schema::table('participants', function (Blueprint $table) {
            $table->index(['status', 'project_id']);
        });
    }

    public function down(): void
    {
        Schema::table('donations',    fn($t) => $t->dropIndex(['type', 'donated_at']));
        Schema::table('projects',     fn($t) => $t->dropIndex(['status', 'current_amount']));
        Schema::table('participants', fn($t) => $t->dropIndex(['status', 'project_id']));
    }
};
