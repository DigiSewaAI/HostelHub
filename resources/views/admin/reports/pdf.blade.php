<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>होस्टेल प्रबन्धन प्रणाली - प्रतिवेदन</title>
    <style>
        body { 
            font-family: 'Kalimati', 'Preeti', sans-serif; 
            font-size: 14px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
        }
        .header h2 {
            margin: 5px 0;
            color: #7f8c8d;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .summary-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 23%;
            margin-bottom: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #7f8c8d;
        }
        .summary-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        @page {
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>होस्टेल प्रबन्धन प्रणाली</h1>
        <h2>{{ $reportTitle ?? 'सामान्य प्रतिवेदन' }}</h2>
        <p>मिति: {{ $startDate ?? now()->subMonth()->format('Y-m-d') }} देखि {{ $endDate ?? now()->format('Y-m-d') }} सम्म</p>
        <p>तयार गरिएको मिति: {{ $generatedAt ?? now()->format('Y-m-d H:i:s') }}</p>
    </div>

    @if(isset($data['student_registrations']))
    <!-- Summary Report View -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>कुल विद्यार्थी</h3>
            <div class="value">{{ $data['student_registrations'] }}</div>
        </div>
        <div class="summary-card">
            <h3>अधिभृत कोठाहरू</h3>
            <div class="value">{{ $data['room_occupancy'] }}</div>
            <small>कुल कोठाको {{ round(($data['room_occupancy']/$data['total_rooms'])*100, 2) }}%</small>
        </div>
        <div class="summary-card">
            <h3>कुल आय</h3>
            <div class="value">रु. {{ number_format($data['revenue'], 2) }}</div>
            <small>यस महिना: रु. {{ number_format($data['monthly_revenue'] ?? 0, 2) }}</small>
        </div>
        <div class="summary-card">
            <h3>उपलब्ध कोठाहरू</h3>
            <div class="value">{{ $data['available_rooms'] ?? 0 }}</div>
            <small>कुल कोठाको {{ round(($data['available_rooms']/$data['total_rooms'])*100, 2) }}%</small>
        </div>
    </div>

    <h3>भर्खरका भुक्तानीहरू</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>विद्यार्थी</th>
                <th>मिति</th>
                <th>रकम</th>
                <th>विधि</th>
                <th>स्थिति</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['recent_payments'] ?? [] as $payment)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $payment['student_name'] }}</td>
                <td>{{ $payment['date'] }}</td>
                <td>रु. {{ number_format($payment['amount'], 2) }}</td>
                <td>{{ $payment['method'] }}</td>
                <td><span class="badge badge-success">{{ $payment['status'] }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <!-- Financial Report View -->
    <table>
        <thead>
            <tr>
                <th>मिति</th>
                <th>कुल आम्दानी</th>
                <th>कुल खर्च</th>
                <th>शुद्ध आम्दानी</th>
                <th>विवरण</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $data)
            <tr>
                <td>{{ $data->date }}</td>
                <td>रु. {{ number_format($data->total_income) }}</td>
                <td>रु. {{ number_format($data->total_expenses) }}</td>
                <td>रु. {{ number_format($data->net_income) }}</td>
                <td>{{ $data->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>यो प्रतिवेदन होस्टेल प्रबन्धन प्रणालीबाट स्वचालित रूपमा तयार गरिएको हो।</p>
        <p>© {{ date('Y') }} होस्टेल प्रबन्धन प्रणाली। सर्वाधिकार सुरक्षित।</p>
    </div>
</body>
</html>