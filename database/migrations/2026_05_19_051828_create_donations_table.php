<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('donations');
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('donor_name');
            $table->string('donor_phone')->nullable();
            $table->enum('type', ['money', 'goods'])->default('money');
            $table->decimal('amount', 15, 2)->nullable()->comment('Số tiền nếu type=money');
            $table->string('goods_description')->nullable()->comment('Mô tả hiện vật nếu type=goods');
            $table->integer('goods_quantity')->nullable();
            $table->enum('payment_method', ['cash', 'transfer', 'other'])->default('cash');
            $table->date('donated_at');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};