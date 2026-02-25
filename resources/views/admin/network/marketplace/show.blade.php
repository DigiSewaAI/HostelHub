@extends('layouts.admin')

@section('title', 'लिस्टिङ विवरण')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">लिस्टिङ: {{ $listing->title }}</h1>
        <a href="{{ route('admin.network.marketplace.index') }}" class="btn btn-secondary">पछाडि</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">लिस्टिङ विवरण</h6>
                </div>
                <div class="card-body">
                    <p><strong>मालिक:</strong> {{ $listing->owner->name }} ({{ $listing->owner->email }})</p>
                    <p><strong>प्रकार:</strong> {{ ucfirst($listing->type) }}</p>
                    <p><strong>मूल्य:</strong> रु {{ number_format($listing->price) }}</p>
                    <p><strong>स्थान:</strong> {{ $listing->location }}</p>
                    <p><strong>स्थिति:</strong> 
                        @if($listing->status == 'pending')
                            <span class="badge badge-warning">पेन्डिङ</span>
                        @elseif($listing->status == 'approved')
                            <span class="badge badge-success">स्वीकृत</span>
                        @elseif($listing->status == 'rejected')
                            <span class="badge badge-danger">अस्वीकृत</span>
                        @endif
                    </p>
                    <p><strong>मिति:</strong> {{ $listing->created_at->format('Y-m-d H:i') }}</p>
                    <hr>
                    <h5>विवरण:</h5>
                    <div class="border p-3 bg-light">
                        {!! nl2br(e($listing->description)) !!}
                    </div>

                    @if($listing->rejected_reason)
                        <hr>
                        <h5>अस्वीकृतिको कारण:</h5>
                        <div class="alert alert-danger">
                            {{ $listing->rejected_reason }}
                        </div>
                    @endif

                    @if($listing->approved_at)
                        <hr>
                        <p><strong>स्वीकृत मिति:</strong> {{ $listing->approved_at->format('Y-m-d H:i') }}</p>
                        <p><strong>स्वीकृतकर्ता:</strong> {{ $listing->approvedBy->name ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>

            @if($listing->media->count())
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">मिडिया</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($listing->media as $media)
                        <div class="col-md-4 mb-3">
                            <img src="{{ asset('storage/'.$media->file_path) }}" class="img-fluid rounded" alt="Media">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if($listing->status == 'pending')
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">कार्यहरू</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.network.marketplace.approve', $listing) }}" method="POST">
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
        <form action="{{ route('admin.network.marketplace.reject', $listing) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">लिस्टिङ अस्वीकृत गर्नुहोस्</h5>
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