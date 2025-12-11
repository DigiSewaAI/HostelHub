<!DOCTYPE html>
<html>
<head>
    <title>Test Bill</title>
</head>
<body>
    <h1>Test Bill</h1>
    <p>Payment ID: {{ $payment->id }}</p>
    <p>Student: {{ $student->name ?? 'No Student' }}</p>
    <p>Amount: Rs. {{ $payment->amount }}</p>
</body>
</html>