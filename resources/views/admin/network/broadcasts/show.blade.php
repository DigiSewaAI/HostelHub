@extends('layouts.admin')

@section('title', 'ब्रोडकास्ट विवरण')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">ब्रोडकास्ट: {{ $broadcast->subject }}</h1>
        <a href="{{ route('admin.network.broadcasts.index') }}" class="btn btn-secondary">पछाडि</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">सन्देश विवरण</h6>
                </div>
                <div class="card-body">
                    <p><strong>पठाउने:</strong> {{ $broadcast->sender->name }} ({{ $broadcast->sender->email }})</p>
                    <p><strong>होस्टल:</strong> {{ $broadcast->sender->hostel->name ?? 'N/A' }}</p>
                    <p><strong>मिति:</strong> {{ $broadcast->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>स्थिति:</strong> 
                        @if($broadcast->status == 'pending')
                            <span class="badge badge-warning">पेन्डिङ</span>
                        @elseif($broadcast->status == 'approved')
                            <span class="badge badge-success">स्वीकृत</span>
                        @elseif($broadcast->status == 'rejected')
                            <span class="badge badge-danger">अस्वीकृत</span>
                        @endif
                    </p>
                    <hr>
                    <h5>सन्देश:</h5>
                    <div class="border p-3 bg-light">
                        {!! nl2br(e($broadcast->body)) !!}
                    </div>

                    @if($broadcast->rejected_reason)
                        <hr>
                        <h5>अस्वीकृतिको कारण:</h5>
                        <div class="alert alert-danger">
                            {{ $broadcast->rejected_reason }}
                        </div>
                    @endif

                    @if($broadcast->approved_at)
                        <hr>
                        <p><strong>स्वीकृत मिति:</strong> {{ $broadcast->approved_at->format('Y-m-d H:i') }}</p>
                        <p><strong>स्वीकृतकर्ता:</strong> {{ $broadcast->approvedBy->name ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>
        </div>

        @if($broadcast->status == 'pending')
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">कार्यहरू</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.network.broadcasts.approve', $broadcast) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block mb-2">स्वीकृत गर्नुहोस्</button>
                    </form>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">अस्वीकृत गर्नुहोस्</button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.network.broadcasts.reject', $broadcast) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ब्रोडकास्ट अस्वीकृत गर्नुहोस्</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>अस्वीकृतिको कारण *</label>
                        <textarea name="rejected_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">रद्द गर्नुहोस्</button>
                    <button type="submit" class="btn btn-danger">अस्वीकृत गर्नुहोस्</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection