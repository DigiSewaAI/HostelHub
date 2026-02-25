@extends('layouts.admin')

@section('title', 'प्रोफाइल विवरण')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $profile->hostel->name }} - प्रोफाइल</h1>
        <a href="{{ route('admin.network.profiles.index') }}" class="btn btn-secondary">पछाडि</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">होस्टल जानकारी</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>होस्टल नाम</th>
                            <td>{{ $profile->hostel->name }}</td>
                        </tr>
                        <tr>
                            <th>ठेगाना</th>
                            <td>{{ $profile->hostel->address }}, {{ $profile->hostel->city }}</td>
                        </tr>
                        <tr>
                            <th>मालिक</th>
                            <td>{{ $profile->hostel->owner->name ?? 'N/A' }} ({{ $profile->hostel->owner->email ?? '' }})</td>
                        </tr>
                        <tr>
                            <th>स्थिति</th>
                            <td>
                                @if($profile->hostel->is_published)
                                    <span class="badge badge-success">प्रकाशित</span>
                                @else
                                    <span class="badge badge-secondary">अप्रकाशित</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">नेटवर्क स्थिति</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.network.profiles.trust', $profile) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>ट्रस्ट लेभल</label>
                            <select name="trust_level" class="form-control">
                                <option value="normal" {{ $profile->trust_level == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="verified" {{ $profile->trust_level == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="trusted" {{ $profile->trust_level == 'trusted' ? 'selected' : '' }}>Trusted</option>
                                <option value="suspended" {{ $profile->trust_level == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                        <div class="form-group" id="suspend_reason_group" style="{{ $profile->trust_level == 'suspended' ? '' : 'display:none;' }}">
                            <label>सस्पेन्ड गर्ने कारण (आवश्यक)</label>
                            <textarea name="suspend_reason" class="form-control" rows="2">{{ old('suspend_reason') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">अपडेट गर्नुहोस्</button>
                    </form>

                    <hr>

                    <form action="{{ route('admin.network.profiles.remove', $profile) }}" method="POST" onsubmit="return confirm('के तपाईं यो होस्टललाई नेटवर्कबाट हटाउन चाहनुहुन्छ? यो कारण पूर्ववत् गर्न सकिँदैन।')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">नेटवर्कबाट हटाउनुहोस्</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional: Show recent broadcasts/listings by this hostel -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">हालैका ब्रोडकास्ट</h6>
                </div>
                <div class="card-body">
                    @forelse($profile->hostel->broadcasts ?? [] as $broadcast)
                        <div>{{ $broadcast->subject }} ({{ $broadcast->status }})</div>
                    @empty
                        <p>कुनै ब्रोडकास्ट छैन।</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">हालैका लिस्टिङ</h6>
                </div>
                <div class="card-body">
                    @forelse($profile->hostel->listings ?? [] as $listing)
                        <div>{{ $listing->title }} ({{ $listing->status }})</div>
                    @empty
                        <p>कुनै लिस्टिङ छैन।</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelector('[name="trust_level"]').addEventListener('change', function() {
        const reasonGroup = document.getElementById('suspend_reason_group');
        if (this.value === 'suspended') {
            reasonGroup.style.display = 'block';
        } else {
            reasonGroup.style.display = 'none';
        }
    });
</script>
@endpush
@endsection