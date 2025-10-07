@props(['status'])

@php
    $statusConfig = [
        'pending' => ['class' => 'warning', 'text' => 'पेन्डिङ'],
        'approved' => ['class' => 'success', 'text' => 'स्वीकृत'],
        'rejected' => ['class' => 'danger', 'text' => 'अस्वीकृत'],
        'cancelled' => ['class' => 'secondary', 'text' => 'रद्द भएको'],
        'completed' => ['class' => 'info', 'text' => 'पूर्ण भएको']
    ];
    
    $config = $statusConfig[$status] ?? ['class' => 'secondary', 'text' => $status];
@endphp

<span class="badge badge-{{ $config['class'] }}">{{ $config['text'] }}</span>