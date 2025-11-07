@extends('layouts.owner')

@section('title', 'भोजन ट्र्याकिंग')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-clipboard-check me-2"></i> भोजन ट्र्याकिंग</h3>
                <a href="{{ route('owner.meals.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> नयाँ भोजन रेकर्ड
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>विद्यार्थी</th>
                                    <th>खानाको प्रकार</th>
                                    <th>मिति</th>
                                    <th>अवस्था</th>
                                    <th>टिप्पणी</th>
                                    <th>कार्य</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($meals as $meal)
                                <tr>
                                    <td>{{ $meal->student->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            @if($meal->meal_type == 'breakfast')
                                                नास्ता
                                            @elseif($meal->meal_type == 'lunch')
                                                दिउँसोको खाना
                                            @else
                                                रात्रिको खाना
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $meal->meal_date->format('Y-m-d') }}</td>
                                    <td>
                                        @if($meal->status == 'served')
                                            <span class="badge bg-success">सर्व गरियो</span>
                                        @elseif($meal->status == 'pending')
                                            <span class="badge bg-warning">पेन्डिङ</span>
                                        @else
                                            <span class="badge bg-danger">छुट्यो</span>
                                        @endif
                                    </td>
                                    <td>{{ $meal->remarks ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('owner.meals.edit', $meal) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('owner.meals.destroy', $meal) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('भोजन अभिलेख हटाउन निश्चित हुनुहुन्छ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-utensils fa-2x mb-3"></i><br>
                                        कुनै भोजन अभिलेख भेटिएन
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($meals->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $meals->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection