<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', [
                'planning', 
                'ongoing', 
                'completed', 
                'closed'
            ])->default('planning')->change();
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['active', 'done', 'upcoming'])->default('active')->change();
        });
    }
};