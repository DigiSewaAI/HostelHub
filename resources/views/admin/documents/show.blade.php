@extends('layouts.admin')

@section('title', 'कागजात विवरण')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>कागजात विवरण (एडमिन)
                        </h5>
                        <div>
                            <a href="{{ route('admin.documents.download', $document) }}" 
                               class="btn btn-light btn-sm">
                                <i class="fas fa-download me-1"></i>डाउनलोड
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Document Information -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">कागजात जानकारी</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">कागजातको नाम:</th>
                                    <td>{{ $document->original_name }}</td>
                                </tr>
                                <tr>
                                    <th>संस्था:</th>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $document->organization->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>प्रकार:</th>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $document->getDocumentTypeNepaliAttribute() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>फाइल साइज:</th>
                                    <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                                </tr>
                                <tr>
                                    <th>फाइल प्रकार:</th>
                                    <td>{{ $document->mime_type }}</td>
                                </tr>
                                <tr>
                                    <th>अपलोड मिति:</th>
                                    <td>{{ $document->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>म्याद मिति:</th>
                                    <td>
                                        @if($document->expiry_date)
                                            @if($document->is_expired)
                                                <span class="badge bg-danger">{{ $document->expiry_date->format('Y-m-d') }} (म्याद नाघेको)</span>
                                            @else
                                                <span class="badge bg-warning">{{ $document->expiry_date->format('Y-m-d') }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">म्याद नभएको</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <!-- Student Information -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">विद्यार्थी जानकारी</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">नाम:</th>
                                    <td>{{ $document->student->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>होस्टल:</th>
                                    <td>{{ $document->hostel->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>ठेगाना:</th>
                                    <td>{{ $document->student->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>सम्पर्क:</th>
                                    <td>{{ $document->student->user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>अपलोड गर्ने:</th>
                                    <td>{{ $document->uploader->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    @if($document->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">विवरण</h6>
                            <p class="mb-0">{{ $document->description }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- File Preview -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">फाइल पूर्वावलोकन</h6>
                            <div class="text-center">
                                @if(str_starts_with($document->mime_type, 'image/'))
                                    <img src="{{ Storage::disk('public')->url($document->stored_path) }}" 
                                         class="img-fluid rounded shadow" style="max-height: 500px;" 
                                         alt="{{ $document->original_name }}">
                                @elseif($document->mime_type == 'application/pdf')
                                    <div class="p-4 border rounded bg-light">
                                        <i class="fas fa-file-pdf text-danger fa-5x mb-3"></i>
                                        <br>
                                        <span class="text-muted">PDF फाइल - डाउनलोड गरेर हेर्नुहोस्</span>
                                    </div>
                                @else
                                    <div class="p-4 border rounded bg-light">
                                        <i class="fas fa-file text-secondary fa-5x mb-3"></i>
                                        <br>
                                        <span class="text-muted">{{ $document->original_name }} - डाउनलोड गरेर हेर्नुहोस्</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>सबै कागजातहरूमा फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection