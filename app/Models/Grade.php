<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level', 'monthly_fee', 'academic_year_id'];

    protected function casts(): array
    {
        return [
            'monthly_fee' => 'decimal:2',
        ];
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
