@extends('layouts.admin')

@section('title', 'ब्रोडकास्ट मध्यस्थता')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">ब्रोडकास्ट सन्देशहरू</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">सबै ब्रोडकास्ट</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>विषय</th>
                            <th>पठाउने</th>
                            <th>होस्टल</th>
                            <th>स्थिति</th>
                            <th>मिति</th>
                            <th>कार्य</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($broadcasts as $broadcast)
                        <tr>
                            <td>{{ $broadcast->subject }}</td>
                            <td>{{ $broadcast->sender->name }}</td>
                            <td>{{ $broadcast->sender->hostel->name ?? 'N/A' }}</td>
                            <td>
                                @if($broadcast->status == 'pending')
                                    <span class="badge badge-warning">पेन्डिङ</span>
                                @elseif($broadcast->status == 'approved')
                                    <span class="badge badge-success">स्वीकृत</span>
                                @elseif($broadcast->status == 'rejected')
                                    <span class="badge badge-danger">अस्वीकृत</span>
                                @endif
                            </td>
                            <td>{{ $broadcast->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.network.broadcasts.show', $broadcast) }}" class="btn btn-sm btn-info">हेर्नुहोस्</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">कुनै ब्रोडकास्ट छैन।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $broadcasts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection