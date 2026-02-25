@extends('layouts.admin')

@section('title', 'होस्टल नेटवर्क कन्ट्रोल')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">होस्टल नेटवर्क कन्ट्रोल</h1>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
    <a href="{{ route('admin.network.broadcasts.index', ['status' => 'pending']) }}" class="text-decoration-none">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">पेन्डिङ ब्रोडकास्ट</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_broadcasts'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">पेन्डिङ ब्रोडकास्ट</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_broadcasts'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">पेन्डिङ लिस्टिङ</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_listings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">पेन्डिङ रिपोर्ट</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_reports'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Pending Items -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">हालैका पेन्डिङ ब्रोडकास्ट</h6>
                </div>
                <div class="card-body">
                    @forelse($recentBroadcasts as $broadcast)
                        <div class="mb-3">
                            <a href="{{ route('admin.network.broadcasts.show', $broadcast) }}" class="text-decoration-none">
                                <strong>{{ $broadcast->subject }}</strong>
                            </a>
                            <p class="small text-muted mb-0">{{ $broadcast->sender->name }} • {{ $broadcast->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-muted">कुनै पेन्डिङ ब्रोडकास्ट छैन।</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">हालैका पेन्डिङ लिस्टिङ</h6>
                </div>
                <div class="card-body">
                    @forelse($recentListings as $listing)
                        <div class="mb-3">
                            <a href="{{ route('admin.network.marketplace.show', $listing) }}" class="text-decoration-none">
                                <strong>{{ $listing->title }}</strong>
                            </a>
                            <p class="small text-muted mb-0">{{ $listing->owner->name }} • {{ $listing->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-muted">कुनै पेन्डिङ लिस्टिङ छैन।</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">हालैका रिपोर्टहरू</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>रिपोर्टर</th>
                                    <th>प्रकार</th>
                                    <th>कारण</th>
                                    <th>मिति</th>
                                    <th>कार्य</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReports as $report)
                                <tr>
                                    <td>{{ $report->reporter->name }}</td>
                                    <td>{{ class_basename($report->reportable_type) }}</td>
                                    <td>{{ $report->reason }}</td>
                                    <td>{{ $report->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('admin.network.reports.show', $report) }}" class="btn btn-sm btn-primary">हेर्नुहोस्</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">कुनै रिपोर्ट छैन।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection