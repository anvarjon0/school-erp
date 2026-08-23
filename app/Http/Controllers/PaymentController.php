<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Grade;
use App\Models\AcademicYear;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'receiver']);

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'ilike', "%{$search}%")
                    ->orWhere('last_name', 'ilike', "%{$search}%")
                    ->orWhere('student_id', 'ilike', "%{$search}%");
            });
        }

        $payments = $query->latest()->paginate(15);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $currentYear = AcademicYear::current();
        $grades = Grade::when($currentYear, fn($q) => $q->where('academic_year_id', $currentYear->id))
            ->orderBy('level')->get();
        return view('payments.create', compact('grades', 'currentYear'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_type' => 'required|in:monthly,admission,other',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'required|integer',
            'note' => 'nullable|string',
        ]);

        $student = Student::findOrFail($validated['student_id']);
        $currentYear = AcademicYear::current();

        $discount = $validated['discount'] ?? 0;
        $status = $validated['paid_amount'] >= ($validated['amount'] - $discount) ? 'paid' : 'partial';

        $payment = Payment::create([
            'receipt_number' => Payment::generateReceiptNumber(),
            'student_id' => $validated['student_id'],
            'academic_year_id' => $currentYear?->id ?? $student->academic_year_id,
            'payment_type' => $validated['payment_type'],
            'amount' => $validated['amount'],
            'discount' => $discount,
            'paid_amount' => $validated['paid_amount'],
            'payment_method' => $validated['payment_method'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'status' => $status,
            'note' => $validated['note'] ?? null,
            'received_by' => auth()->id(),
        ]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'To\'lov muvaffaqiyatli qabul qilindi.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['student.grade', 'receiver']);
        return view('payments.show', compact('payment'));
    }

    public function receipt(Payment $payment)
    {
        $payment->load(['student.grade', 'receiver']);
        $pdf = Pdf::loadView('payments.receipt-pdf', compact('payment'));
        return $pdf->download("receipt-{$payment->receipt_number}.pdf");
    }

    public function debtors(Request $request)
    {
        $currentYear = AcademicYear::current();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $paidStudentIds = Payment::where('academic_year_id', $currentYear?->id)
            ->where('month', $month)
            ->where('year', $year)
            ->where('status', 'paid')
            ->pluck('student_id');

        $debtors = Student::with(['grade', 'section'])
            ->where('status', 'active')
            ->when($currentYear, fn($q) => $q->where('academic_year_id', $currentYear->id))
            ->whereNotIn('id', $paidStudentIds)
            ->paginate(15);

        return view('payments.debtors', compact('debtors', 'month', 'year'));
    }

    public function edit(Payment $payment)
    {
        $payment->load('student');
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer',
            'note' => 'nullable|string',
        ]);

        $discount = $validated['discount'] ?? 0;
        $status = $validated['paid_amount'] >= ($payment->amount - $discount) ? 'paid' : 'partial';

        $payment->update([
            'paid_amount' => $validated['paid_amount'],
            'discount' => $discount,
            'payment_method' => $validated['payment_method'],
            'note' => $validated['note'] ?? null,
            'status' => $status,
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'To\'lov yangilandi.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')
            ->with('success', 'To\'lov o\'chirildi.');
    }

    public function getStudentFee(Student $student)
    {
        return response()->json([
            'monthly_fee' => $student->grade->monthly_fee ?? 0,
            'student_name' => $student->full_name,
            'grade' => $student->grade->name ?? '',
        ]);
    }
}
