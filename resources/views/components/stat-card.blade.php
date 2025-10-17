@props(['title', 'value', 'icon', 'color' => 'blue', 'description' => ''])

@php
    $colorClasses = [
        'blue' => ['bg' => 'from-blue-50 to-blue-100', 'border' => 'border-blue-500', 'icon' => 'bg-blue-500'],
        'green' => ['bg' => 'from-green-50 to-green-100', 'border' => 'border-green-500', 'icon' => 'bg-green-500'],
        'amber' => ['bg' => 'from-amber-50 to-amber-100', 'border' => 'border-amber-500', 'icon' => 'bg-amber-500'],
        'indigo' => ['bg' => 'from-indigo-50 to-indigo-100', 'border' => 'border-indigo-500', 'icon' => 'bg-indigo-500'],
        'purple' => ['bg' => 'from-purple-50 to-purple-100', 'border' => 'border-purple-500', 'icon' => 'bg-purple-500'],
    ][$color];
@endphp

<div class="stat-card bg-gradient-to-r {{ $colorClasses['bg'] }} border-l-4 {{ $colorClasses['border'] }} p-6 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-md">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <h3 class="text-lg font-bold text-gray-800">{{ $title }}</h3>
            <p class="text-3xl font-bold mt-2 text-gray-900">{{ $value }}</p>
            @if($description)
                <p class="text-sm text-gray-600 mt-3">{{ $description }}</p>
            @endif
        </div>
        <div class="{{ $colorClasses['icon'] }} text-white p-3 rounded-xl">
            <i class="fas fa-{{ $icon }} text-xl"></i>
        </div>
    </div>
</div>