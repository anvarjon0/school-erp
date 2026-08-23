<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('student_id')->constrained()->onDelete('restrict');
            $table->foreignId('academic_year_id')->constrained()->onDelete('restrict');
            $table->enum('payment_type', ['monthly', 'admission', 'other'])->default('monthly');
            $table->decimal('amount', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2);
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer'])->default('cash');
            $table->integer('month')->nullable();
            $table->integer('year');
            $table->enum('status', ['paid', 'partial', 'pending'])->default('paid');
            $table->text('note')->nullable();
            $table->foreignId('received_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
