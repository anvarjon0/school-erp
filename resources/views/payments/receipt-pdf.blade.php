<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kvitansiya #{{ $payment->receipt_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; width: 100%; border-collapse: collapse; }
        .details td { padding: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .total { float: right; width: 300px; }
        .total table { width: 100%; }
        .total th, .total td { padding: 5px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>School ERP</h2>
        <p>Toshkent shahar | Tel: +998 90 123 45 67</p>
        <h3>Kvitansiya #{{ $payment->receipt_number }}</h3>
    </div>
    
    <table class="details">
        <tr>
            <td><strong>O'quvchi:</strong> {{ $payment->student->full_name }}</td>
            <td><strong>Sana:</strong> {{ $payment->created_at->format('d.m.Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Sinf:</strong> {{ $payment->student->grade->name ?? '-' }} ({{ $payment->student->section->name ?? '-' }})</td>
            <td><strong>To'lov usuli:</strong> {{ ucfirst($payment->payment_method) }}</td>
        </tr>
    </table>
    
    <table class="table">
        <thead>
            <tr>
                <th>Tavsif</th>
                <th>Oy/Yil</th>
                <th>Summa</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>O'quv to'lovi ({{ $payment->payment_type == 'monthly' ? 'Oylik' : 'Boshqa' }})</td>
                <td>{{ $payment->month_name }} {{ $payment->year }}</td>
                <td>{{ number_format($payment->amount) }} so'm</td>
            </tr>
        </tbody>
    </table>
    
    <div class="total">
        <table>
            <tr><th>Jami:</th><td>{{ number_format($payment->amount) }} so'm</td></tr>
            <tr><th>Chegirma:</th><td>{{ number_format($payment->discount_amount) }} so'm</td></tr>
            <tr><th>To'langan:</th><td>{{ number_format($payment->paid_amount) }} so'm</td></tr>
            <tr><th>Qarz:</th><td>{{ number_format($payment->amount - $payment->discount_amount - $payment->paid_amount) }} so'm</td></tr>
        </table>
    </div>
</body>
</html>
