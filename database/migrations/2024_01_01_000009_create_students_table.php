<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('grade_id')->constrained()->onDelete('restrict');
            $table->foreignId('section_id')->constrained()->onDelete('restrict');
            $table->foreignId('academic_year_id')->constrained()->onDelete('restrict');
            $table->enum('status', ['active', 'graduated', 'expelled', 'transferred'])->default('active');
            $table->date('admission_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
