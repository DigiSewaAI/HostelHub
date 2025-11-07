@extends('layouts.owner')

@section('title', 'खानाको योजना')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-utensils me-2"></i> खानाको योजना</h3>
                <a href="{{ route('owner.meal-menus.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> नयाँ योजना
                </a>
            </div>

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

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>दिन</th>
                                    <th>खानाको प्रकार</th>
                                    <th>खानाका वस्तुहरू</th>
                                    <th>तस्बिर</th>
                                    <th>अवस्था</th>
                                    <th>कार्य</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mealMenus as $menu)
                                <tr>
                                    <td>
                                        @if($menu->day_of_week == 'sunday')
                                            आइतबार
                                        @elseif($menu->day_of_week == 'monday')
                                            सोमबार
                                        @elseif($menu->day_of_week == 'tuesday')
                                            मंगलबार
                                        @elseif($menu->day_of_week == 'wednesday')
                                            बुधबार
                                        @elseif($menu->day_of_week == 'thursday')
                                            बिहिबार
                                        @elseif($menu->day_of_week == 'friday')
                                            शुक्रबार
                                        @else
                                            शनिबार
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            @if($menu->meal_type == 'breakfast')
                                                नास्ता
                                            @elseif($menu->meal_type == 'lunch')
                                                दिउँसो
                                            @else
                                                रात्रि
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if(is_array($menu->items) && count($menu->items) > 0)
                                            <ul class="mb-0 ps-3">
                                                @foreach($menu->items as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">कुनै वस्तुहरू छैनन्</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($menu->image)
                                            <img src="{{ asset('storage/'.$menu->image) }}" alt="Meal Image" class="img-thumbnail" width="100">
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($menu->is_active)
                                            <span class="badge bg-success">सक्रिय</span>
                                        @else
                                            <span class="badge bg-danger">निष्क्रिय</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('owner.meal-menus.edit', $menu) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('owner.meal-menus.destroy', $menu) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('खानाको योजना हटाउन निश्चित हुनुहुन्छ?')">
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
                                        कुनै खानाको योजना भेटिएन
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection