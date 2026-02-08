@extends('layouts.owner')

@section('title', $hostel->name . ' - भुक्तानी विधिहरू')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 nepali">{{ $hostel->name }} - भुक्तानी विधिहरू</h4>
                        <small class="nepali">कुल विधिहरू: {{ $paymentMethods->count() }} (सक्रिय: {{ $paymentMethods->where('is_active', true)->count() }})</small>
                    </div>
                    <div>
                        <a href="{{ route('owner.payment-methods.create.for-hostel', $hostel->id) }}" class="btn btn-light btn-sm nepali">
                            <i class="fas fa-plus"></i> यस होस्टेलमा थप्नुहोस्
                        </a>
                        <a href="{{ route('owner.payment-methods.index') }}" class="btn btn-outline-light btn-sm nepali">
                            <i class="fas fa-list"></i> सबै विधिहरू
                        </a>
                    </div>
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
                                            <h4 class="mb-0">{{ $paymentMethods->count() }}</h4>
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
                                            <h4 class="mb-0">{{ $paymentMethods->where('is_active', true)->count() }}</h4>
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
                                            <h4 class="mb-0">{{ $paymentMethods->where('is_default', true)->count() }}</h4>
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
                                            <h6 class="mb-0 nepali">अन्तिम अपडेट</h6>
                                            <h6 class="mb-0">{{ $paymentMethods->max('updated_at') ? $paymentMethods->max('updated_at')->format('Y-m-d') : 'नभएको' }}</h6>
                                        </div>
                                        <div class="bg-white bg-opacity-25 rounded p-2">
                                            <i class="fas fa-history fa-2x"></i>
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
                            <p class="text-muted nepali">यस होस्टेलका लागि भुक्तानी विधिहरू थप्नुहोस्।</p>
                            <a href="{{ route('owner.payment-methods.create.for-hostel', $hostel->id) }}" class="btn btn-info nepali">
                                <i class="fas fa-plus"></i> पहिलो भुक्तानी विधि थप्नुहोस्
                            </a>
                        </div>
                    @else
                        <!-- Sortable Payment Methods -->
                        <div id="sortable-payment-methods">
                            @foreach($paymentMethods as $index => $method)
                                <div class="card mb-3 border" data-id="{{ $method->id }}">
                                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                        <div class="d-flex align-items-center">
                                            <span class="handle me-2 text-muted" style="cursor: move;">
                                                <i class="fas fa-bars"></i>
                                            </span>
                                            <h6 class="mb-0 nepali">{{ $method->title }}</h6>
                                            @if($method->is_default)
                                                <span class="badge bg-warning ms-2 nepali"><i class="fas fa-star"></i> मुख्य</span>
                                            @endif
                                            @if($method->is_active)
                                                <span class="badge bg-success ms-1 nepali"><i class="fas fa-check-circle"></i> सक्रिय</span>
                                            @else
                                                <span class="badge bg-danger ms-1 nepali"><i class="fas fa-times-circle"></i> निष्क्रिय</span>
                                            @endif
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('owner.payment-methods.edit', $method->id) }}" 
                                               class="btn btn-outline-primary" title="सम्पादन">
                                                <i class="fas fa-edit"></i> सम्पादन
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
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-1">
                                                            <span class="text-muted nepali">प्रकार:</span>
                                                            <span class="badge bg-info">{{ $method->type_text }}</span>
                                                        </p>
                                                        <p class="mb-1">
                                                            <span class="text-muted nepali">स्थिति:</span>
                                                            <span class="badge {{ $method->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                                {{ $method->is_active ? 'सक्रिय' : 'निष्क्रिय' }}
                                                            </span>
                                                        </p>
                                                        <p class="mb-1">
                                                            <span class="text-muted nepali">क्रम:</span>
                                                            <span class="fw-bold">{{ $method->order }}</span>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @if($method->type == 'bank')
                                                            <p class="mb-1">
                                                                <span class="text-muted nepali">बैंक:</span>
                                                                {{ $method->bank_name }}
                                                            </p>
                                                            <p class="mb-1">
                                                                <span class="text-muted nepali">खाता नम्बर:</span>
                                                                <span class="font-monospace">{{ $method->masked_account_number }}</span>
                                                            </p>
                                                            <p class="mb-1">
                                                                <span class="text-muted nepali">खाता धनी:</span>
                                                                {{ $method->account_name }}
                                                            </p>
                                                            @if($method->branch_name)
                                                            <p class="mb-1">
                                                                <span class="text-muted nepali">शाखा:</span>
                                                                {{ $method->branch_name }}
                                                            </p>
                                                            @endif
                                                        @elseif(in_array($method->type, ['esewa', 'khalti', 'fonepay', 'imepay', 'connectips']))
                                                            <p class="mb-1">
                                                                <span class="text-muted nepali">मोबाइल:</span>
                                                                {{ $method->mobile_number }}
                                                            </p>
                                                            @if($method->wallet_id)
                                                            <p class="mb-1">
                                                                <span class="text-muted nepali">आईडी:</span>
                                                                {{ $method->wallet_id }}
                                                            </p>
                                                            @endif
                                                            @if($method->account_name)
                                                            <p class="mb-1">
                                                                <span class="text-muted nepali">नाम:</span>
                                                                {{ $method->account_name }}
                                                            </p>
                                                            @endif
                                                        @elseif($method->type == 'cash')
                                                            <p class="mb-1">
                                                                <span class="text-muted nepali">विधि:</span>
                                                                नगद भुक्तानी
                                                            </p>
                                                        @else
                                                            <p class="mb-1">
                                                                <span class="text-muted nepali">विवरण:</span>
                                                                {{ $method->additional_info['description'] ?? 'अन्य भुक्तानी विधि' }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                @if($method->qr_code_url)
                                                    <p class="small text-muted nepali mb-2">QR कोड:</p>
                                                    <img src="{{ $method->qr_code_url }}" 
                                                         class="img-fluid rounded border" 
                                                         style="max-width: 100px;">
                                                @else
                                                    <p class="text-muted nepali mb-0">
                                                        <i class="fas fa-qrcode fa-2x"></i><br>
                                                        <small>QR कोड छैन</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if(!empty($method->instructions))
                                            <div class="mt-3 pt-3 border-top">
                                                <p class="small text-muted nepali mb-1"><strong>निर्देशनहरू:</strong></p>
                                                <ul class="small nepali mb-0" style="padding-left: 20px;">
                                                    @foreach($method->instructions as $instruction)
                                                        <li>{{ $instruction }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Last Updated Info -->
                                        <div class="mt-2 pt-2 border-top">
                                            <small class="text-muted nepali">
                                                <i class="fas fa-history"></i> अन्तिम अपडेट: {{ $method->formatted_updated_at }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- REMOVED THE NOTE DIV FROM HERE -->
                        
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>
@endsection

@push('styles')
<style>
.handle {
    cursor: move;
    user-select: none;
}
#sortable-payment-methods .card {
    transition: transform 0.2s;
}
#sortable-payment-methods .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Initialize sortable
    const sortableContainer = document.getElementById('sortable-payment-methods');
    if (sortableContainer) {
        const sortable = new Sortable(sortableContainer, {
            animation: 150,
            handle: '.handle',
            ghostClass: 'bg-light',
            onEnd: function(evt) {
                updatePaymentMethodOrder();
            }
        });
    }

    // Function to update order
    function updatePaymentMethodOrder() {
        const order = [];
        $('#sortable-payment-methods .card').each(function(index) {
            const methodId = $(this).data('id');
            order.push({
                id: methodId,
                position: index + 1
            });
        });

        $.ajax({
            url: "{{ route('owner.payment-methods.update-order') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                order: order
            },
            success: function(response) {
                if (response.success) {
                    showToast('क्रम सफलतापूर्वक अद्यावधिक गरियो!', 'success');
                }
            },
            error: function(xhr) {
                showToast(xhr.responseJSON?.message || 'असफल भयो। पुनः प्रयास गर्नुहोस्।', 'error');
            }
        });
    }

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