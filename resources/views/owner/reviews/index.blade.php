@extends('layouts.owner')

@section('title', 'समीक्षाहरू')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">समीक्षाहरू</h1>
        <!-- ✅ REMOVED: Create button since owners don't create reviews, they reply to them -->
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
                            <th>विद्यार्थी</th>
                            <th>मूल्यांकन</th>
                            <th>समीक्षा</th>
                            <th>जवाफ</th>
                            <th>मिति</th>
                            <th>कार्यहरू</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($review->student)
                                    {{ $review->student->name }}
                                @else
                                    <span class="text-muted">विद्यार्थी उपलब्ध छैन</span>
                                @endif
                            </td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <small class="text-muted">({{ $review->rating }}/5)</small>
                            </td>
                            <td>{{ Str::limit($review->comment, 50) }}</td>
                            <td>
                                @if($review->reply)
                                    <span class="badge bg-success">जवाफ दिइयो</span>
                                @else
                                    <span class="badge bg-warning">जवाफ बाकी</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('owner.reviews.show', $review) }}" class="btn btn-sm btn-info me-1" title="हेर्नुहोस्">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if(!$review->reply)
                                <a href="{{ route('owner.reviews.reply', $review) }}" class="btn btn-sm btn-primary me-1" title="जवाफ दिनुहोस्">
                                    <i class="fas fa-reply"></i>
                                </a>
                                @endif
                                
                                <form action="{{ route('owner.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('के तपाईं यो समीक्षा हटाउन चाहनुहुन्छ?')" title="हटाउनुहोस्">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">कुनै समीक्षा फेला परेन</td>
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