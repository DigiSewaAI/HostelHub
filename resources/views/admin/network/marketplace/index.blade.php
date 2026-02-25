@extends('layouts.admin')

@section('title', 'मार्केटप्लेस लिस्टिङ मध्यस्थता')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">मार्केटप्लेस लिस्टिङहरू</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">सबै लिस्टिङ</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>शीर्षक</th>
                            <th>मालिक</th>
                            <th>प्रकार</th>
                            <th>मूल्य</th>
                            <th>स्थिति</th>
                            <th>मिति</th>
                            <th>कार्य</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listings as $listing)
                        <tr>
                            <td>{{ $listing->title }}</td>
                            <td>{{ $listing->owner->name }}</td>
                            <td>{{ ucfirst($listing->type) }}</td>
                            <td>रु {{ number_format($listing->price) }}</td>
                            <td>
                                @if($listing->status == 'pending')
                                    <span class="badge badge-warning">पेन्डिङ</span>
                                @elseif($listing->status == 'approved')
                                    <span class="badge badge-success">स्वीकृत</span>
                                @elseif($listing->status == 'rejected')
                                    <span class="badge badge-danger">अस्वीकृत</span>
                                @endif
                            </td>
                            <td>{{ $listing->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.network.marketplace.show', $listing) }}" class="btn btn-sm btn-info">हेर्नुहोस्</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">कुनै लिस्टिङ छैन।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $listings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection