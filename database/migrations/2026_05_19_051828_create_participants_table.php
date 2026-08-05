<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('participants');
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            // Thông tin cá nhân
            $table->string('full_name');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->string('address')->nullable();

            // Thông tin tình nguyện
            $table->date('joined_at');
            $table->date('ended_at')->nullable();
            $table->integer('hours_contributed')->default(0)->comment('Số giờ tình nguyện');
            $table->enum('role', ['volunteer', 'team_lead', 'coordinator'])->default('volunteer');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');

            // Ghi chú
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};