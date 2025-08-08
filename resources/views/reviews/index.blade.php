@extends('layouts.admin')

@section('title', 'समीक्षाहरू')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">समीक्षाहरू</h1>
        <a href="{{ route('reviews.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> नयाँ समीक्षा
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>क्र.स.</th>
                            <th>नाम</th>
                            <th>पद</th>
                            <th>प्रकार</th>
                            <th>स्थिति</th>
                            <th>कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $review->name }}</td>
                            <td>{{ $review->position }}</td>
                            <td>
                                @if($review->type == 'testimonial')
                                    <span class="badge bg-success">प्रशंसापत्र</span>
                                @elseif($review->type == 'review')
                                    <span class="badge bg-primary">समीक्षा</span>
                                @else
                                    <span class="badge bg-info">प्रतिक्रिया</span>
                                @endif
                            </td>
                            <td>
                                @if($review->status == 'active')
                                    <span class="badge bg-success">सक्रिय</span>
                                @else
                                    <span class="badge bg-secondary">निष्क्रिय</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('reviews.show', $review) }}" class="btn btn-sm btn-info me-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('reviews.edit', $review) }}" class="btn btn-sm btn-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('के तपाईं यो समीक्षा हटाउन चाहनुहुन्छ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">कुनै समीक्षा फेला परेन</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
