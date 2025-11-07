@extends('layouts.owner')

@section('title', 'भोजन विवरण')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-eye me-2"></i> भोजन विवरण</h3>
                        <a href="{{ route('owner.meals.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> पछाडि जानुहोस्
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">विद्यार्थी:</th>
                                    <td>{{ $meal->student->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>खानाको प्रकार:</th>
                                    <td>
                                        @if($meal->meal_type == 'breakfast')
                                            <span class="badge bg-primary">नास्ता</span>
                                        @elseif($meal->meal_type == 'lunch')
                                            <span class="badge bg-info">दिउँसोको खाना</span>
                                        @else
                                            <span class="badge bg-dark">रात्रिको खाना</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>मिति:</th>
                                    <td>{{ $meal->meal_date->format('Y-m-d') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">अवस्था:</th>
                                    <td>
                                        @if($meal->status == 'served')
                                            <span class="badge bg-success">सर्व गरियो</span>
                                        @elseif($meal->status == 'pending')
                                            <span class="badge bg-warning">पेन्डिङ</span>
                                        @else
                                            <span class="badge bg-danger">छुट्यो</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>होस्टल:</th>
                                    <td>{{ $meal->hostel->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>थपिएको मिति:</th>
                                    <td>{{ $meal->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($meal->remarks)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>टिप्पणी:</h5>
                            <div class="card">
                                <div class="card-body">
                                    {{ $meal->remarks }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('owner.meals.edit', $meal) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i> सम्पादन गर्नुहोस्
                                </a>
                                <form action="{{ route('owner.meals.destroy', $meal) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" onclick="return confirm('भोजन अभिलेख हटाउन निश्चित हुनुहुन्छ?')">
                                        <i class="fas fa-trash me-1"></i> हटाउनुहोस्
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection