@if(isset($hostel->images) && count($hostel->images) > 0)
<section class="gallery-section mt-8">
    <h2 class="text-2xl font-bold text-gray-900 nepali mb-6">ग्यालरी</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($hostel->images as $image)
        <div class="aspect-square rounded-lg overflow-hidden border border-gray-200">
            <img src="{{ asset('storage/' . $image) }}" 
                 alt="{{ $hostel->name }} - Image {{ $loop->iteration }}"
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
        </div>
        @endforeach
    </div>
</section>
@endif