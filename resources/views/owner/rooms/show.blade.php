@extends('layouts.owner')

@section('title', 'कोठा विवरण')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">कोठा विवरण</h3>
                </div>

                <div class="card-body">
                    {{-- Room Image Display --}}
                    @if($room->has_image)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>कोठाको फोटो:</h5>
                            <div class="text-center">
                                <img src="{{ $room->image_url }}" 
                                     alt="Room Image" 
                                     class="img-fluid rounded"
                                     style="max-height: 400px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    @endif

                    @php
                        // 🔥 CRITICAL FIX: Calculate real-time occupancy from students table
                        $currentOccupancy = $room->students()
                            ->whereIn('status', ['active', 'approved'])
                            ->count();
                        $availableBeds = $room->capacity - $currentOccupancy;
                        
                        // 🔥 CRITICAL FIX: Determine status based on real data
                        if ($currentOccupancy == 0) {
                            $status = 'available';
                            $displayStatus = 'उपलब्ध';
                            $badgeClass = 'bg-success text-white';
                        } elseif ($currentOccupancy == $room->capacity) {
                            $status = 'occupied';
                            $displayStatus = 'व्यस्त';
                            $badgeClass = 'bg-danger text-white';
                        } else {
                            $status = 'partially_available';
                            $displayStatus = 'आंशिक उपलब्ध (' . $availableBeds . ' बेड खाली)';
                            $badgeClass = 'bg-warning text-dark';
                        }
                    @endphp

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%;">कोठा नम्बर:</th>
                                    <td>{{ $room->room_number }}</td>
                                </tr>
                                <tr>
                                    <th>होस्टल:</th>
                                    <td>{{ $room->hostel->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>प्रकार:</th>
                                    <td>
                                        {{-- ✅ FIXED: Updated room type display --}}
                                        @if($room->type == '1 seater')
                                            एक सिटर कोठा
                                        @elseif($room->type == '2 seater')
                                            दुई सिटर कोठा
                                        @elseif($room->type == '3 seater')
                                            तीन सिटर कोठा
                                        @elseif($room->type == '4 seater')
                                            चार सिटर कोठा
                                        @elseif($room->type == 'साझा कोठा')
                                            साझा कोठा
                                        @else
                                            {{ $room->type }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>क्षमता:</th>
                                    <td>{{ $room->capacity }} जना</td>
                                </tr>
                                <tr>
                                    <th>हालको अधिभोग:</th>
                                    <td>
                                        <strong>{{ $currentOccupancy }} जना</strong>
                                        @if($currentOccupancy > 0)
                                            <small class="text-muted d-block">
                                                (वास्तविक डाटा: {{ $currentOccupancy }} विद्यार्थी)
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>खाली ठाउँ:</th>
                                    <td>
                                        <strong>{{ $availableBeds }} जना</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>मूल्य:</th>
                                    <td>रु. {{ number_format($room->price, 2) }}</td>
                                </tr>
                                <tr>
                                    {{-- ✅ FIXED: Gallery Category Display --}}
                                    <th>ग्यालरी श्रेणी:</th>
                                    <td>
                                        @php
                                            $galleryCategories = [
                                                '1 seater' => '१ सिटर कोठा',
                                                '1_seater' => '१ सिटर कोठा',
                                                '2 seater' => '२ सिटर कोठा', 
                                                '2_seater' => '२ सिटर कोठा',
                                                '3 seater' => '३ सिटर कोठा',
                                                '3_seater' => '३ सिटर कोठा',
                                                '4 seater' => '४ सिटर कोठा',
                                                '4_seater' => '४ सिटर कोठा',
                                                'साझा कोठा' => 'साझा कोठा',
                                                'living_room' => 'लिभिङ रूम',
                                                'bathroom' => 'बाथरूम',
                                                'kitchen' => 'भान्सा',
                                                'study_room' => 'अध्ययन कोठा',
                                                'events' => 'कार्यक्रम',
                                                'video_tour' => 'भिडियो टुर'
                                            ];
                                        @endphp
                                        {{ $galleryCategories[$room->gallery_category] ?? $room->gallery_category }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>स्थिति:</th>
                                    <td>
                                        <span class="badge {{ $badgeClass }} p-2">
                                            {{ $displayStatus }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            (वास्तविक डाटा अनुसार)
                                        </small>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>विवरण:</h5>
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-0">{{ $room->description ?? 'कुनै विवरण उपलब्ध छैन' }}</p>
                            </div>

                            {{-- Current Students List --}}
                                @if($currentOccupancy > 0)
                                <div class="mt-4">
                                    <h5>यस कोठामा रहेका विद्यार्थीहरू ({{ $currentOccupancy }} जना):</h5>
                                    <div class="border rounded p-3">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>नाम</th>
                                                        <th>स्थिति</th>
                                                        <th>भुक्तानी</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($room->students()->whereIn('status', ['active', 'approved'])->get() as $student)
                                                    <tr>
                                                        <td>{{ $student->name }}</td>
                                                        <td>
                                                            @if($student->status == 'active')
                                                                <span class="badge bg-success text-white">सक्रिय</span>
                                                            @else
                                                                <span class="badge bg-info text-white">स्वीकृत</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($student->payment_status == 'paid')
                                                                <span class="badge bg-success text-white">भुक्तानी भएको</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">बाकी</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="mt-4">
                                    <h5>विद्यार्थीहरू:</h5>
                                    <div class="border rounded p-3 text-center">
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-info-circle"></i> 
                                            यस कोठामा कुनै विद्यार्थी छैनन्
                                        </p>
                                    </div>
                                </div>
                                @endif

                            {{-- Additional Information --}}
                            <div class="mt-4">
                                <h5>अतिरिक्त जानकारी:</h5>
                                <div class="border rounded p-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">सिर्जना गरिएको:</small><br>
                                            <strong>{{ $room->created_at->format('Y-m-d') }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">अन्तिम अपडेट:</small><br>
                                            <strong>{{ $room->updated_at->format('Y-m-d') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('owner.rooms.edit', $room) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                    </a>
                    <a href="{{ route('owner.rooms.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> कोठा सूचीमा फर्कनुहोस्
                    </a>
                    
                    {{-- 🔥 SYNC BUTTON: Force sync this room --}}
                    <form action="{{ route('owner.rooms.sync-single', $room) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-info" title="यो कोठाको डाटा सिंक गर्नुहोस्">
                            <i class="fas fa-sync-alt"></i> डाटा सिंक गर्नुहोस्
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection