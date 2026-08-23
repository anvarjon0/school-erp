<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->decimal('base_salary', 15, 2);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('deduction', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2);
            $table->integer('month');
            $table->integer('year');
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->date('paid_date')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->unique(['user_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
