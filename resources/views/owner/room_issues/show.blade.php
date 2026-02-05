@extends('layouts.owner')

@section('title', 'कोठा समस्या विवरण - होस्टलहब')

@section('content')
<div class="container-fluid px-4">
    <!-- पृष्ठ शीर्षक र क्रियाहरू -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">कोठा समस्याको विवरण</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('owner.dashboard') }}">ड्याशबोर्ड</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('owner.room-issues.index') }}">कोठा समस्याहरू</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">विवरण</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('owner.room-issues.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> पछि जानुहोस्
            </a>
        </div>
    </div>

    <div class="row">
        <!-- मुख्य जानकारी -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> समस्याको विस्तृत विवरण
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">समस्याको प्रकार:</label>
                            <div class="form-control-plaintext">
                                <strong class="fs-5">{{ $roomIssue->issue_type }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">प्राथमिकता:</label>
                            <div>
                                @if($roomIssue->priority == 'urgent')
                                    <span class="badge bg-danger fs-6">जरुरी</span>
                                @elseif($roomIssue->priority == 'high')
                                    <span class="badge bg-warning text-dark fs-6">उच्च</span>
                                @else
                                    <span class="badge bg-info fs-6">सामान्य</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">समस्याको विवरण:</label>
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-0">{{ $roomIssue->description }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted">स्थिति:</label>
                            <div>
                                @if($roomIssue->status == 'pending')
                                    <span class="badge bg-warning text-dark fs-6">बाँकी</span>
                                @elseif($roomIssue->status == 'resolved')
                                    <span class="badge bg-success fs-6">समाधान भएको</span>
                                @else
                                    <span class="badge bg-secondary fs-6">{{ $roomIssue->status }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted">रिपोर्ट मिति:</label>
                            <div class="form-control-plaintext">
                                {{ \Carbon\Carbon::parse($roomIssue->reported_at)->format('Y-m-d H:i') }}
                            </div>
                        </div>
                        @if($roomIssue->status == 'resolved')
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted">समाधान मिति:</label>
                                <div class="form-control-plaintext">
                                    {{ \Carbon\Carbon::parse($roomIssue->resolved_at)->format('Y-m-d H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- टिप्पणी (यदि छ भने) -->
                    @if($roomIssue->comments)
                        <div class="mb-3">
                            <label class="form-label text-muted">अतिरिक्त टिप्पणी:</label>
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-0">{{ $roomIssue->comments }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- साइडबार जानकारी -->
        <div class="col-lg-4">
            <!-- विद्यार्थी जानकारी -->
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate"></i> विद्यार्थी जानकारी
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar avatar-xl bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                             style="width: 60px; height: 60px;">
                            <span class="text-white fs-4">
                                {{ substr($roomIssue->student_name ?? 'N', 0, 1) }}
                            </span>
                        </div>
                        <h5 class="mb-1">{{ $roomIssue->student_name ?? 'N/A' }}</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <i class="fas fa-door-open text-primary me-2"></i>
                            <strong>कोठा नं.:</strong> {{ $roomIssue->room_number ?? 'N/A' }}
                        </div>
                        <div class="col-12 mb-2">
                            <i class="fas fa-hotel text-primary me-2"></i>
                            <strong>होस्टल:</strong> {{ $roomIssue->hostel_name ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- क्रियाहरू -->
            <div class="card shadow">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-tools"></i> क्रियाहरू
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($roomIssue->status == 'pending')
                            <form action="{{ route('owner.room-issues.resolve', $roomIssue->id) }}" 
                                  method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100 mb-2">
                                    <i class="fas fa-check-circle"></i> समाधान गर्नुहोस्
                                </button>
                            </form>
                            <a href="#" class="btn btn-primary btn-lg w-100 mb-2" data-bs-toggle="modal" data-bs-target="#contactModal">
                                <i class="fas fa-phone"></i> विद्यार्थीलाई सम्पर्क गर्नुहोस्
                            </a>
                        @else
                            <form action="{{ route('owner.room-issues.reopen', $roomIssue->id) }}" 
                                  method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg w-100 mb-2">
                                    <i class="fas fa-redo"></i> फेरि खोल्नुहोस्
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('owner.room-issues.index') }}" class="btn btn-secondary btn-lg w-100">
                            <i class="fas fa-list"></i> सबै समस्याहरू हेर्नुहोस्
                        </a>
                    </div>
                </div>
            </div>

            <!-- जरुरी नोट -->
            <div class="alert alert-warning mt-3" role="alert">
                <h6 class="alert-heading">
                    <i class="fas fa-exclamation-triangle"></i> महत्वपूर्ण नोट
                </h6>
                <ul class="mb-0 ps-3">
                    <li>जरुरी समस्याहरू २४ घण्टाभित्र समाधान गर्नुपर्छ</li>
                    <li>समस्या समाधान भएपछि विद्यार्थीलाई जानकारी गराउनुहोस्</li>
                    <li>समाधान भएको रेकर्ड राख्नुहोस्</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- सम्पर्क मोडल -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="contactModalLabel">
                    <i class="fas fa-phone"></i> विद्यार्थीलाई सम्पर्क गर्नुहोस्
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>यो समस्याको बारेमा विद्यार्थीलाई सम्पर्क गर्न तयार हुनुहुन्छ?</p>
                <div class="alert alert-info">
                    <strong>नोट:</strong> सम्पर्क गरेपछि विद्यार्थीलाई ईमेल वा SMS मार्फत जानकारी गराइनेछ।
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">रद्द गर्नुहोस्</button>
                <button type="button" class="btn btn-primary" onclick="notifyStudent()">
                    <i class="fas fa-paper-plane"></i> सम्पर्क गर्नुहोस्
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function notifyStudent() {
        // यहाँ एजाक्स रिक्वेस्ट गर्न सकिन्छ
        $.ajax({
            url: '/owner/notify-student',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                issue_id: {{ $roomIssue->id }}
            },
            success: function(response) {
                alert('विद्यार्थीलाई सफलतापूर्वक सूचना पठाइयो!');
                $('#contactModal').modal('hide');
            },
            error: function() {
                alert('सूचना पठाउँदा त्रुटि भयो। पुनः प्रयास गर्नुहोस्।');
            }
        });
    }
    
    // स्वतः अपडेट (प्रत्येक १ मिनेटमा)
    setInterval(function() {
        location.reload();
    }, 60000);
</script>
@endsection 
