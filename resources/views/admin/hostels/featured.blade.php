@extends('layouts.admin')

@section('title', 'Featured Hostels Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star"></i>
                        Featured Hostels Management
                    </h3>
                    <p class="text-muted mb-0">
                        Manage which hostels appear in the homepage slider. Maximum 10 hostels will be displayed.
                    </p>
                </div>

                <form action="{{ route('admin.hostels.featured.update') }}" method="POST">
                    @csrf
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="50">Featured</th>
                                        <th width="80">Order</th>
                                        <th>Hostel Name</th>
                                        <th>City</th>
                                        <th width="120">Commission Rate %</th>
                                        <th width="120">Extra Commission</th>
                                        <th width="100">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hostels as $hostel)
                                    <tr>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input type="checkbox" name="featured[]" value="{{ $hostel->id }}" 
                                                       class="form-check-input featured-checkbox"
                                                       {{ $hostel->is_featured ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" name="featured_order[{{ $hostel->id }}]" 
                                                   value="{{ $hostel->featured_order }}" 
                                                   min="0" max="100" 
                                                   class="form-control form-control-sm featured-order"
                                                   {{ $hostel->is_featured ? '' : 'disabled' }}>
                                        </td>
                                        <td>
                                            <strong>{{ $hostel->name }}</strong>
                                            @if($hostel->is_featured)
                                                <span class="badge badge-success ml-2">Featured</span>
                                            @endif
                                        </td>
                                        <td>{{ $hostel->city }}</td>
                                        <td>
                                            <input type="number" name="commission_rate[{{ $hostel->id }}]" 
                                                   value="{{ $hostel->commission_rate }}" 
                                                   step="0.1" min="0" max="50" 
                                                   class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            <input type="number" name="extra_commission[{{ $hostel->id }}]" 
                                                   value="{{ $hostel->extra_commission }}" 
                                                   min="0" step="100"
                                                   class="form-control form-control-sm">
                                        </td>
                                        <td>
                                            @if($hostel->is_published)
                                                <span class="badge badge-success">Published</span>
                                            @else
                                                <span class="badge badge-secondary">Draft</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No hostels found. 
                                            <a href="{{ route('admin.hostels.create') }}">Create a hostel first</a>.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($hostels->count() > 0)
                        <div class="alert alert-info mt-3">
                            <h6><i class="fas fa-info-circle"></i> Instructions:</h6>
                            <ul class="mb-0">
                                <li>Check the "Featured" box to include hostel in homepage slider</li>
                                <li>Set "Order" to control display sequence (1 = first position)</li>
                                <li>Only published hostels with cover images will be displayed</li>
                                <li>Maximum 10 hostels will be shown in the slider</li>
                            </ul>
                        </div>
                        @endif
                    </div>

                    @if($hostels->count() > 0)
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Featured Hostels
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                        
                        <span class="ml-3 text-muted">
                            <strong id="featured-count">0</strong> hostels selected for featuring
                        </span>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update featured count
    function updateFeaturedCount() {
        var count = $('.featured-checkbox:checked').length;
        $('#featured-count').text(count);
        
        // Show warning if more than 10 selected
        if (count > 10) {
            $('#featured-count').addClass('text-danger');
        } else {
            $('#featured-count').removeClass('text-danger');
        }
    }

    // Enable/disable order input based on featured checkbox
    $('.featured-checkbox').change(function() {
        var orderInput = $(this).closest('tr').find('.featured-order');
        if ($(this).is(':checked')) {
            orderInput.prop('disabled', false);
        } else {
            orderInput.prop('disabled', true);
        }
        updateFeaturedCount();
    });

    // Initialize
    $('.featured-checkbox').each(function() {
        if (!$(this).is(':checked')) {
            $(this).closest('tr').find('.featured-order').prop('disabled', true);
        }
    });
    updateFeaturedCount();
});
</script>
@endpush