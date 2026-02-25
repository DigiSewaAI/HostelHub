@extends('layouts.admin')

@section('title', 'रिपोर्ट व्यवस्थापन')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">रिपोर्टहरू</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">सबै रिपोर्ट</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>रिपोर्टर</th>
                            <th>प्रकार</th>
                            <th>कारण</th>
                            <th>स्थिति</th>
                            <th>मिति</th>
                            <th>कार्य</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->reporter->name }}</td>
                            <td>{{ class_basename($report->reportable_type) }}</td>
                            <td>{{ $report->reason }}</td>
                            <td>
                                @if($report->status == 'pending')
                                    <span class="badge badge-warning">पेन्डिङ</span>
                                @elseif($report->status == 'reviewed')
                                    <span class="badge badge-success">समीक्षा गरिएको</span>
                                @elseif($report->status == 'dismissed')
                                    <span class="badge badge-secondary">खारेज</span>
                                @endif
                            </td>
                            <td>{{ $report->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.network.reports.show', $report) }}" class="btn btn-sm btn-info">हेर्नुहोस्</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">कुनै रिपोर्ट छैन।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
@endsection