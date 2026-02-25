@extends('layouts.admin')

@section('title', 'रिपोर्ट विवरण')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">रिपोर्ट #{{ $report->id }}</h1>
        <a href="{{ route('admin.network.reports.index') }}" class="btn btn-secondary">पछाडि</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">रिपोर्ट विवरण</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>रिपोर्टर</th>
                            <td>{{ $report->reporter->name }} ({{ $report->reporter->email }})</td>
                        </tr>
                        <tr>
                            <th>रिपोर्ट गरिएको सामग्री प्रकार</th>
                            <td>{{ class_basename($report->reportable_type) }}</td>
                        </tr>
                        <tr>
                            <th>रिपोर्ट गरिएको ID</th>
                            <td>{{ $report->reportable_id }}</td>
                        </tr>
                        <tr>
                            <th>कारण</th>
                            <td>{{ $report->reason }}</td>
                        </tr>
                        @if($report->description)
                        <tr>
                            <th>विवरण</th>
                            <td>{{ $report->description }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>स्थिति</th>
                            <td>
                                @if($report->status == 'pending')
                                    <span class="badge badge-warning">पेन्डिङ</span>
                                @elseif($report->status == 'reviewed')
                                    <span class="badge badge-success">समीक्षा गरिएको</span>
                                @elseif($report->status == 'dismissed')
                                    <span class="badge badge-secondary">खारेज</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>मिति</th>
                            <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @if($report->reviewed_at)
                        <tr>
                            <th>समीक्षा मिति</th>
                            <td>{{ $report->reviewed_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>समीक्षाकर्ता</th>
                            <td>{{ $report->reviewer->name ?? 'N/A' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">रिपोर्ट गरिएको सामग्री</h6>
                </div>
                <div class="card-body">
                    @php $reportable = $report->reportable; @endphp
                    @if($reportable)
                        @if($reportable instanceof \App\Models\BroadcastMessage)
                            <p><strong>विषय:</strong> {{ $reportable->subject }}</p>
                            <p><strong>सन्देश:</strong> {{ Str::limit($reportable->body, 200) }}</p>
                            <a href="{{ route('admin.network.broadcasts.show', $reportable) }}" target="_blank" class="btn btn-sm btn-primary">ब्रोडकास्ट हेर्नुहोस्</a>
                        @elseif($reportable instanceof \App\Models\MarketplaceListing)
                            <p><strong>शीर्षक:</strong> {{ $reportable->title }}</p>
                            <p><strong>विवरण:</strong> {{ Str::limit($reportable->description, 200) }}</p>
                            <a href="{{ route('admin.network.marketplace.show', $reportable) }}" target="_blank" class="btn btn-sm btn-primary">लिस्टिङ हेर्नुहोस्</a>
                        @elseif($reportable instanceof \App\Models\Message)
                            <p><strong>सन्देश:</strong> {{ Str::limit($reportable->body, 200) }}</p>
                            <a href="{{ route('admin.network.messages.show', $reportable->thread_id) }}" target="_blank" class="btn btn-sm btn-primary">थ्रेड हेर्नुहोस्</a>
                        @elseif($reportable instanceof \App\Models\Hostel)
                            <p><strong>होस्टल:</strong> {{ $reportable->name }}</p>
                            <a href="{{ route('admin.hostels.show', $reportable) }}" target="_blank" class="btn btn-sm btn-primary">होस्टल हेर्नुहोस्</a>
                        @elseif($reportable instanceof \App\Models\User)
                            <p><strong>प्रयोगकर्ता:</strong> {{ $reportable->name }} ({{ $reportable->email }})</p>
                        @endif
                    @else
                        <p>सामग्री मेटाइएको छ वा उपलब्ध छैन।</p>
                    @endif
                </div>
            </div>
        </div>

        @if($report->status == 'pending')
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">कार्यहरू</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.network.reports.review', $report) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block mb-2">समीक्षा गरियो</button>
                    </form>
                    <form action="{{ route('admin.network.reports.dismiss', $report) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-block">खारेज गर्नुहोस्</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection