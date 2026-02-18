@extends('layouts.admin')

@section('title', 'सबै सूचनाहरू')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">सबै सूचनाहरू</h1>
        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-check-double mr-2"></i>सबै पढिसकेको चिन्ह लगाउनुहोस्
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">सूचना</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">मिति</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">स्थिति</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">कार्य</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                @endphp
                <tr class="{{ $isUnread ? 'bg-blue-50' : '' }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 mr-3">
                                <div class="bg-indigo-100 rounded-full p-2">
                                    <i class="fas fa-star text-indigo-600"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">{{ $data['message'] ?? 'नयाँ सूचना' }}</p>
                                <p class="text-xs text-gray-500">{{ $data['review_id'] ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $notification->created_at->format('Y-m-d H:i') }}<br>
                        <span class="text-xs">{{ $notification->created_at->diffForHumans() }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($isUnread)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">नपढिएको</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">पढिसकेको</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <a href="{{ $data['url'] ?? '#' }}" class="text-indigo-600 hover:text-indigo-900 mr-3"
                           onclick="event.preventDefault(); markNotificationAsRead('{{ $notification->id }}', '{{ $data['url'] ?? '#' }}');">
                            <i class="fas fa-eye"></i> हेर्नुहोस्
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        कुनै सूचना छैन।
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function markNotificationAsRead(notificationId, url) {
        fetch('/notifications/' + notificationId + '/mark-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        }).then(response => {
            if (response.ok) {
                window.location.href = url;
            } else {
                window.location.href = url;
            }
        }).catch(error => {
            console.error('Error:', error);
            window.location.href = url;
        });
    }
</script>
@endpush