@props(['title', 'icon', 'color' => 'blue', 'route' => '#'])

@php
    $colorClasses = [
        'blue' => ['bg' => 'bg-blue-50', 'hover' => 'hover:bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'text-blue-600'],
        'green' => ['bg' => 'bg-green-50', 'hover' => 'hover:bg-green-100', 'text' => 'text-green-800', 'icon' => 'text-green-600'],
        'indigo' => ['bg' => 'bg-indigo-50', 'hover' => 'hover:bg-indigo-100', 'text' => 'text-indigo-800', 'icon' => 'text-indigo-600'],
        'teal' => ['bg' => 'bg-teal-50', 'hover' => 'hover:bg-teal-100', 'text' => 'text-teal-800', 'icon' => 'text-teal-600'],
        'amber' => ['bg' => 'bg-amber-50', 'hover' => 'hover:bg-amber-100', 'text' => 'text-amber-800', 'icon' => 'text-amber-600'],
        'purple' => ['bg' => 'bg-purple-50', 'hover' => 'hover:bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'text-purple-600'],
    ][$color];
@endphp

<a href="{{ $route }}" class="quick-action block p-4 {{ $colorClasses['bg'] }} {{ $colorClasses['hover'] }} rounded-2xl text-center transition-all duration-300 group border border-gray-200">
    <div class="{{ $colorClasses['icon'] }} text-2xl mb-2 transition-transform duration-300 group-hover:scale-110">
        <i class="fas fa-{{ $icon }}"></i>
    </div>
    <div class="font-medium {{ $colorClasses['text'] }} text-sm">{{ $title }}</div>
</a>