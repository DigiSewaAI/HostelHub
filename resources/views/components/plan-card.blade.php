@props([
  'title' => 'Plan',
  'price' => 'रु. 0',
  'period' => '/महिना',
  'features' => [],
  'cta' => '#',
  'popular' => false,
  'limit' => null,
  'badgeText' => 'लोकप्रिय',
])

<div class="relative bg-white rounded-2xl shadow-md p-6 border hover:shadow-lg overflow-visible">
  @if($popular)
    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-rose-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
      {{ $badgeText }}
    </span>
  @endif

  <h3 class="text-xl font-bold text-gray-900 text-center">{{ $title }}</h3>

  <div class="mt-2 text-center">
    <div class="text-3xl font-extrabold">{{ $price }}</div>
    <div class="text-gray-500">{{ $period }}</div>
    @if($limit)
      <div class="mt-1 text-sm text-gray-600">{{ $limit }}</div>
    @endif
  </div>

  <ul class="mt-5 space-y-2 text-sm text-gray-700">
    @foreach($features as $f)
      <li class="flex items-start gap-2">
        <span class="mt-1">✔</span>
        <span>{{ $f }}</span>
      </li>
    @endforeach
  </ul>

  <a href="{{ $cta }}"
     class="mt-6 inline-flex justify-center w-full rounded-xl bg-gray-900 text-white py-2.5 font-medium hover:bg-black">
     योजना छान्नुहोस्
  </a>
</div>