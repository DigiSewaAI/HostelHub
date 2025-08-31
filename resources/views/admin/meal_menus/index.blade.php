@extends('admin.layouts.app')

@section('title', 'खानाको योजना')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>खानाको योजना</h3>
        <a href="{{ route('admin.meal-menus.create') }}" class="btn btn-primary">थप्नुहोस्</a>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>होस्टल</th>
                    <th>खानाको प्रकार</th>
                    <th>दिन</th>
                    <th>वस्तुहरू</th>
                    <th>तस्बिर</th>
                    <th>स्थिति</th>
                    <th>कार्य</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mealMenus as $menu)
                <tr>
                    <td>{{ $menu->hostel->name }}</td>
                    <td>{{ ucfirst($menu->meal_type) }}</td>
                    <td>{{ ucfirst($menu->day_of_week) }}</td>
                    <td>{{ implode(', ', $menu->items) }}</td>
                    <td>
                        @if($menu->image)
                            <img src="{{ Storage::url($menu->image) }}" width="50" alt="Meal">
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $menu->is_active ? 'success' : 'danger' }}">
                            {{ $menu->is_active ? 'सक्रिय' : 'निष्क्रिय' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.meal-menus.edit', $menu) }}" class="btn btn-sm btn-warning">सम्पादन</a>
                        <form action="{{ route('admin.meal-menus.destroy', $menu) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('हटाउन निश्चित हुनुहुन्छ?')">हटाउनुहोस्</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection