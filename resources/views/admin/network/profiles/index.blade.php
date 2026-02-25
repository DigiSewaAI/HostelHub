@extends('layouts.admin')

@section('title', 'नेटवर्क प्रोफाइल व्यवस्थापन')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">नेटवर्क प्रोफाइलहरू</h1>
    </div>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">फिल्टर</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.network.profiles.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ट्रस्ट लेभल</label>
                            <select name="trust" class="form-control">
                                <option value="">सबै</option>
                                <option value="normal" {{ request('trust')=='normal' ? 'selected' : '' }}>Normal</option>
                                <option value="verified" {{ request('trust')=='verified' ? 'selected' : '' }}>Verified</option>
                                <option value="trusted" {{ request('trust')=='trusted' ? 'selected' : '' }}>Trusted</option>
                                <option value="suspended" {{ request('trust')=='suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>खोजी (होस्टल नाम वा शहर)</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="होस्टल नाम वा शहर">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block">फिल्टर गर्नुहोस्</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Profiles Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>होस्टल</th>
                            <th>मालिक</th>
                            <th>शहर</th>
                            <th>ट्रस्ट लेभल</th>
                            <th>प्रोफाइल मिति</th>
                            <th>कार्य</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profiles as $profile)
                        <tr>
                            <td>
                                <a href="{{ route('admin.network.profiles.show', $profile) }}">
                                    {{ $profile->hostel->name }}
                                </a>
                            </td>
                            <td>{{ $profile->hostel->owner->name ?? 'N/A' }}</td>
                            <td>{{ $profile->hostel->city }}</td>
                            <td>
                                @if($profile->trust_level == 'suspended')
                                    <span class="badge badge-danger">Suspended</span>
                                @elseif($profile->trust_level == 'verified')
                                    <span class="badge badge-success">Verified</span>
                                @elseif($profile->trust_level == 'trusted')
                                    <span class="badge badge-primary">Trusted</span>
                                @else
                                    <span class="badge badge-secondary">Normal</span>
                                @endif
                            </td>
                            <td>{{ $profile->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.network.profiles.show', $profile) }}" class="btn btn-sm btn-info">हेर्नुहोस्</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">कुनै प्रोफाइल फेला परेन।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $profiles->links() }}
            </div>
        </div>
    </div>
</div>
@endsection