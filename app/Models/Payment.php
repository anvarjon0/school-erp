<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number', 'student_id', 'academic_year_id', 'payment_type',
        'amount', 'discount', 'paid_amount', 'payment_method', 'month',
        'year', 'status', 'note', 'received_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'discount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public static function generateReceiptNumber(): string
    {
        $prefix = 'RCP-' . date('Ymd');
        $last = static::where('receipt_number', 'like', "{$prefix}-%")
            ->orderByDesc('receipt_number')
            ->first();

        if ($last) {
            $number = (int) substr($last->receipt_number, -4) + 1;
        } else {
            $number = 1;
        }

        return sprintf('%s-%04d', $prefix, $number);
    }

    public function getMonthNameAttribute(): string
    {
        if (!$this->month) return '-';
        $months = [
            1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
            5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
            9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr',
        ];
        return $months[$this->month] ?? '-';
    }
}
