<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'section_id', 'date', 'status', 'marked_by'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
