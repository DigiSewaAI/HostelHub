@extends('layouts.owner')

@section('title', 'मेरो नेटवर्क प्रोफाइल')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>{{ $hostel->name }} – नेटवर्क प्रोफाइल</h4>
                @if($profile->verified_at)
                    <span class="badge bg-success">प्रमाणित</span>
                @else
                    <span class="badge bg-warning">प्रमाणित छैन</span>
                @endif
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    <i class="fas fa-info-circle"></i> यो प्रोफाइल तपाईंको होस्टलको विवरणबाट स्वतः सिंक हुन्छ।
                    <a href="{{ route('owner.hostels.edit', $hostel->id) }}">होस्टल प्रोफाइल सम्पादन गर्नुहोस्</a>
                </p>

                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        @if($hostel->logo_path)
                            <img src="{{ media_url($hostel->logo_path) }}" class="img-fluid rounded" style="max-height: 150px;">
                        @else
                            <div class="bg-light p-4 rounded">लोगो छैन</div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-sm">
                            <tr><th>शहर</th><td>{{ $hostel->city }}</td></tr>
                            <tr><th>ठेगाना</th><td>{{ $hostel->address }}</td></tr>
                            <tr><th>सम्पर्क फोन</th><td>{{ $hostel->contact_phone_formatted }}</td></tr>
                            <tr><th>इमेल</th><td>{{ $hostel->contact_email_formatted }}</td></tr>
                            <tr><th>जम्मा कोठा</th><td>{{ $hostel->total_rooms }}</td></tr>
                            <tr><th>उपलब्ध कोठा</th><td>{{ $hostel->available_rooms }}</td></tr>
                            <tr><th>अकुपेन्सी</th><td>{{ $hostel->occupancy_rate }}%</td></tr>
                            <tr>
    <th>सुविधाहरू</th>
    <td>
        @php
            $facilities = $hostel->facilities;
            // If it's a string, try to decode it once (or twice)
            if (is_string($facilities)) {
                $decoded = json_decode($facilities, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $facilities = $decoded;
                } else {
                    // Try double decode (if it's a stringified JSON string)
                    $decoded2 = json_decode($decoded ?? '', true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded2)) {
                        $facilities = $decoded2;
                    } else {
                        $facilities = [];
                    }
                }
            }
            $facilities = is_array($facilities) ? $facilities : [];
        @endphp

        @if(count($facilities))
            @foreach($facilities as $facility)
                <span class="badge bg-info">{{ $facility }}</span>
            @endforeach
        @else
            छैन
        @endif
    </td>
</tr>
                            <tr><th>मूल्य दायरा</th><td>{{ $hostel->formatted_price_range }}</td></tr>
                        </table>
                    </div>
                </div>

                <hr>
                <h5>विवरण</h5>
                <p>{{ $hostel->description ?? 'कुनै विवरण छैन' }}</p>

                @if(!$profile->verified_at)
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-shield-alt"></i> तपाईंको प्रोफाइल अझै प्रमाणित भएको छैन। प्रमाणीकरणको लागि प्रशासकलाई सम्पर्क गर्नुहोस्।
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection