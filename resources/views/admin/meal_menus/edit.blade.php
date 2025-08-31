@extends('admin.layouts.app')

@section('title', 'खाना सम्पादन गर्नुहोस्')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>खानाको योजना सम्पादन गर्नुहोस्</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.meal-menus.update', $mealMenu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>होस्टल</label>
                <select name="hostel_id" class="form-control" required>
                    @foreach($hostels as $hostel)
                        <option value="{{ $hostel->id }}" {{ $hostel->id == $mealMenu->hostel_id ? 'selected' : '' }}>
                            {{ $hostel->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>खानाको प्रकार</label>
                <select name="meal_type" class="form-control" required>
                    @foreach($mealTypes as $type)
                        <option {{ $type == $mealMenu->meal_type ? 'selected' : '' }} value="{{ $type }}">
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>दिन</label>
                <select name="day_of_week" class="form-control" required>
                    @foreach($days as $day)
                        <option {{ $day == $mealMenu->day_of_week ? 'selected' : '' }} value="{{ $day }}">
                            {{ ucfirst($day) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>खानाका वस्तुहरू</label>
                <div id="items">
                    @foreach($mealMenu->items as $item)
                        <input type="text" name="items[]" class="form-control mb-2" value="{{ $item }}" required>
                    @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-secondary" onclick="addItem()">+ थप्नुहोस्</button>
            </div>

            <div class="form-group">
                <label>खानाको तस्बिर</label>
                @if($mealMenu->image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($mealMenu->image) }}" width="100" alt="Current meal">
                    </div>
                @endif
                <input type="file" name="image" class="form-control">
                <small>खाली छोड्नुभयो भने तस्बिर परिवर्तन हुँदैन</small>
            </div>

            <div class="form-group">
                <label>वर्णन</label>
                <textarea name="description" class="form-control" rows="3">{{ $mealMenu->description }}</textarea>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ $mealMenu->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">सक्रिय</label>
            </div>

            <button type="submit" class="btn btn-primary">अपडेट गर्नुहोस्</button>
        </form>
    </div>
</div>

<script>
function addItem() {
    const container = document.getElementById('items');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'items[]';
    input.className = 'form-control mb-2';
    input.placeholder = 'उदाहरण: Tarkari';
    input.required = true;
    container.appendChild(input);
}
</script>
@endsection