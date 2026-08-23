<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'first_name', 'last_name', 'date_of_birth', 'gender',
        'address', 'photo', 'grade_id', 'section_id', 'academic_year_id',
        'status', 'admission_date',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'admission_date' => 'date',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function parentInfo()
    {
        return $this->hasOne(ParentInfo::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public static function generateStudentId(): string
    {
        $year = date('Y');
        $last = static::where('student_id', 'like', "STD-{$year}-%")
            ->orderByDesc('student_id')
            ->first();

        if ($last) {
            $number = (int) substr($last->student_id, -4) + 1;
        } else {
            $number = 1;
        }

        return sprintf('STD-%s-%04d', $year, $number);
    }
}
