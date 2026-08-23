<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentInfo extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'father_name', 'father_phone', 'mother_name', 'mother_phone',
        'address', 'student_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
