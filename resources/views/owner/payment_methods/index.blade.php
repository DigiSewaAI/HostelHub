@extends('layouts.owner')

@section('title', 'भुक्तानी विधिहरू')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 nepali">भुक्तानी विधिहरू</h4>
                    <a href="{{ route('owner.payment-methods.create') }}" class="btn btn-light btn-sm nepali">
                        <i class="fas fa-plus"></i> नयाँ भुक्तानी विधि
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success nepali">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger nepali">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 nepali">कुल विधिहरू</h6>
                                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                        </div>
                                        <div class="bg-white bg-opacity-25 rounded p-2">
                                            <i class="fas fa-credit-card fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 nepali">सक्रिय विधि</h6>
                                            <h4 class="mb-0">{{ $stats['active'] }}</h4>
                                        </div>
                                        <div class="bg-white bg-opacity-25 rounded p-2">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 nepali">मुख्य विधि</h6>
                                            <h4 class="mb-0">{{ $stats['default'] }}</h4>
                                        </div>
                                        <div class="bg-white bg-opacity-25 rounded p-2">
                                            <i class="fas fa-star fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 nepali">डिजिटल विधि</h6>
                                            <h4 class="mb-0">{{ $stats['digital'] }}</h4>
                                        </div>
                                        <div class="bg-white bg-opacity-25 rounded p-2">
                                            <i class="fas fa-mobile-alt fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($paymentMethods->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <h5 class="nepali">कुनै भुक्तानी विधि फेला परेन</h5>
                            <p class="text-muted nepali">तपाईंको होस्टेलका लागि भुक्तानी विधिहरू थप्नुहोस्।</p>
                            <a href="{{ route('owner.payment-methods.create') }}" class="btn btn-primary nepali">
                                <i class="fas fa-plus"></i> पहिलो भुक्तानी विधि थप्नुहोस्
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="nepali">क्र॰</th>
                                        <th class="nepali">होस्टेल</th>
                                        <th class="nepali">विधि</th>
                                        <th class="nepali">विवरण</th>
                                        <th class="nepali">स्थिति</th>
                                        <th class="nepali">मुख्य</th>
                                        <th class="nepali">अपडेट</th>
                                        <th class="nepali">कार्यहरू</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentMethods as $index => $method)
                                        <tr>
                                            <td>{{ $index + 1 + (($paymentMethods->currentPage() - 1) * $paymentMethods->perPage()) }}</td>
                                            <td>
                                                <a href="{{ route('owner.payment-methods.hostel', $method->hostel_id) }}" class="text-decoration-none">
                                                    {{ $method->hostel->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info nepali">{{ $method->type_text }}</span>
                                                <div class="small text-muted nepali">{{ $method->title }}</div>
                                            </td>
                                            <td>
                                                <div class="small nepali">
                                                    @if($method->type == 'bank')
                                                        <div class="font-weight-bold">{{ $method->bank_name }}</div>
                                                        <div class="font-monospace">{{ $method->masked_account_number }}</div>
                                                        <div class="text-primary">खाता धनी: {{ $method->account_name }}</div>
                                                    @elseif(in_array($method->type, ['esewa', 'khalti', 'fonepay', 'imepay', 'connectips']))
                                                        <div class="font-weight-bold">{{ $method->type_text }}</div>
                                                        <div>मोबाइल: {{ $method->mobile_number }}</div>
                                                        @if($method->wallet_id)
                                                            <div>आईडी: {{ $method->wallet_id }}</div>
                                                        @endif
                                                        @if($method->account_name)
                                                            <div>नाम: {{ $method->account_name }}</div>
                                                        @endif
                                                    @elseif($method->type == 'cash')
                                                        <div class="font-weight-bold">नगद भुक्तानी</div>
                                                        <div>स्थान: {{ $method->additional_info['location'] ?? 'होस्टेल कार्यालय' }}</div>
                                                    @else
                                                        <div class="font-weight-bold">अन्य विधि</div>
                                                        @if(isset($method->additional_info['description']))
                                                            <div>{{ $method->additional_info['description'] }}</div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($method->is_active)
                                                    <span class="badge bg-success nepali"><i class="fas fa-check-circle"></i> सक्रिय</span>
                                                @else
                                                    <span class="badge bg-danger nepali"><i class="fas fa-times-circle"></i> निष्क्रिय</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($method->is_default)
                                                    <span class="badge bg-warning nepali"><i class="fas fa-star"></i> मुख्य</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $method->formatted_updated_at }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('owner.payment-methods.edit', $method->id) }}" 
                                                       class="btn btn-outline-primary" title="सम्पादन">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-secondary toggle-status" 
                                                            data-id="{{ $method->id }}" 
                                                            title="{{ $method->is_active ? 'निष्क्रिय गर्नुहोस्' : 'सक्रिय गर्नुहोस्' }}">
                                                        <i class="fas fa-power-off"></i>
                                                    </button>
                                                    @if(!$method->is_default)
                                                        <button type="button" class="btn btn-outline-warning set-default" 
                                                                data-id="{{ $method->id }}" title="मुख्य बनाउनुहोस्">
                                                            <i class="fas fa-star"></i>
                                                        </button>
                                                    @endif
                                                    @if($method->can_be_deleted)
                                                        <button type="button" class="btn btn-outline-danger delete-method" 
                                                                data-id="{{ $method->id }}" 
                                                                data-title="{{ $method->title }}"
                                                                title="हटाउनुहोस्">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-outline-secondary" 
                                                                title="भुक्तानी रेकर्ड भएकोले हटाउन सकिँदैन" disabled>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $paymentMethods->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Toggle active status
    $('.toggle-status').click(function() {
        const methodId = $(this).data('id');
        const button = $(this);
        
        $.ajax({
            url: "{{ url('owner/payment-methods') }}/" + methodId + "/toggle-status",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    showToast(xhr.responseJSON.message, 'error');
                } else {
                    showToast('असफल भयो। पुनः प्रयास गर्नुहोस्।', 'error');
                }
            }
        });
    });

    // Set as default
    $('.set-default').click(function() {
        const methodId = $(this).data('id');
        const button = $(this);
        
        $.ajax({
            url: "{{ url('owner/payment-methods') }}/" + methodId + "/set-default",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    showToast(xhr.responseJSON.message, 'error');
                } else {
                    showToast('असफल भयो। पुनः प्रयास गर्नुहोस्।', 'error');
                }
            }
        });
    });

    // Delete method with confirmation
    $('.delete-method').click(function() {
        const methodId = $(this).data('id');
        const methodTitle = $(this).data('title');
        
        Swal.fire({
            title: 'पक्का हुनुहुन्छ?',
            html: `<div class="nepali">तपाईं <strong>"${methodTitle}"</strong> भुक्तानी विधि हटाउन चाहनुहुन्छ?<br>यो कार्य फिर्ता गर्न सकिँदैन।</div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'हो, हटाउनुहोस्',
            cancelButtonText: 'रद्द गर्नुहोस्',
            customClass: {
                confirmButton: 'nepali',
                cancelButton: 'nepali'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('owner/payment-methods') }}/" + methodId,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'हटाइयो!',
                            text: 'भुक्तानी विधि सफलतापूर्वक हटाइयो।',
                            icon: 'success',
                            confirmButtonText: 'ठिक छ'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'त्रुटि!',
                            text: xhr.responseJSON?.message || 'हटाउन असफल। पुनः प्रयास गर्नुहोस्।',
                            icon: 'error',
                            confirmButtonText: 'ठिक छ'
                        });
                    }
                });
            }
        });
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" 
                 role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body nepali">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);
        
        $('#toast-container').append(toast);
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
        
        // Remove toast after it hides
        toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }
});
</script>
@endpush