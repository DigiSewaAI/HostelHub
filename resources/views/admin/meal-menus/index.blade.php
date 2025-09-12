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
                                    <th>कार्य</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mealMenus as $menu)
                                <tr>
                                    <td>{{ $menu->day }}</td>
                                    <td><span class="badge bg-primary">{{ ucfirst($menu->meal_type) }}</span></td>
                                    <td>{{ $menu->items }}</td>
                                    <td>
                                        @if($menu->image)
                                            <img src="{{ asset('storage/'.$menu->image) }}" alt="Meal Image" class="img-thumbnail" width="100">
                                        @else
                                            <span class="text-muted">N/A</span>
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