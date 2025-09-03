@extends('layouts.owner')

@section('title', 'खानाको ट्र्याकिंग')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-clipboard-check me-2"></i> खानाको ट्र्याकिंग</h3>
                <a href="{{ route('admin.meals.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> थप्नुहोस्
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>विद्यार्थी</th>
                                    <th>होस्टल</th>
                                    <th>खानाको प्रकार</th>
                                    <th>मिति</th>
                                    <th>अवस्था</th>
                                    <th>कार्य</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($meals as $meal)
                                <tr>
                                    <td>{{ $meal->student->name ?? 'N/A' }}</td>
                                    <td>{{ $meal->hostel->name ?? 'N/A' }}</td>
                                    <td><span class="badge bg-primary">{{ ucfirst($meal->meal_type) }}</span></td>
                                    <td>{{ $meal->date }}</td>
                                    <td>
                                        <span class="badge bg-{{ $meal->status == 'present' ? 'success' : 'danger' }}">
                                            {{ $meal->status == 'present' ? 'उपस्थित' : 'अनुपस्थित' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.meals.edit', $meal) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.meals.destroy', $meal) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('हटाउन निश्चित हुनुहुन्छ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection