@extends('layouts.owner')

@section('title', 'सदस्यता सीमाहरू')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">सदस्यता सीमाहरू</h1>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                हालको सदस्यता
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $subscription->plan_name ?? 'मूल योजना' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-crown fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                होस्टेल सीमा
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $currentHostels }}/{{ $subscription->hostel_limit ?? 1 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hotel fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                सक्रिय बुकिंग
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $activeBookings }}/{{ $subscription->booking_limit ?? 50 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($currentHostels >= ($subscription->hostel_limit ?? 1))
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>सीमा पुगिसक्यो!</strong> तपाईंले थप होस्टेल थप्नको लागि सदस्यता उन्नत गर्नुपर्ने हुन सक्छ।
        <a href="{{ route('owner.subscription.extra-hostel') }}" class="alert-link">अतिरिक्त होस्टेल किन्नुहोस्</a>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">सदस्यता विवरण</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>सुविधा</th>
                                    <th>हालको</th>
                                    <th>सीमा</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>होस्टेलहरू</td>
                                    <td>{{ $currentHostels }}</td>
                                    <td>{{ $subscription->hostel_limit ?? 1 }}</td>
                                </tr>
                                <tr>
                                    <td>सक्रिय बुकिंगहरू</td>
                                    <td>{{ $activeBookings }}</td>
                                    <td>{{ $subscription->booking_limit ?? 50 }}</td>
                                </tr>
                                <tr>
                                    <td>कोठा प्रबन्धन</td>
                                    <td><i class="fas fa-check text-success"></i></td>
                                    <td>असीमित</td>
                                </tr>
                                <tr>
                                    <td>रिपोर्टहरू</td>
                                    <td><i class="fas fa-check text-success"></i></td>
                                    <td>मूल</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection