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
                                    <td>{{ $room->current_occupancy ?? 0 }} जना</td>
                                </tr>
                                <tr>
                                    <th>खाली ठाउँ:</th>
                                    <td>{{ ($room->capacity - ($room->current_occupancy ?? 0)) }} जना</td>
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
                                        {{-- ✅ FIXED: Updated status display with new statuses --}}
                                        @php
                                            $status = $room->status;
                                            $available_beds = $room->capacity - ($room->current_occupancy ?? 0);
                                            
                                            if ($status === 'maintenance') {
                                                $displayStatus = 'मर्मत सम्भार';
                                                $badgeClass = 'bg-secondary text-white';
                                            } elseif ($status === 'occupied') {
                                                $displayStatus = 'व्यस्त';
                                                $badgeClass = 'bg-danger text-white';
                                            } elseif ($status === 'partially_available') {
                                                $displayStatus = 'आंशिक उपलब्ध (' . $available_beds . ' बेड खाली)';
                                                $badgeClass = 'bg-warning text-dark';
                                            } else {
                                                $displayStatus = 'उपलब्ध';
                                                $badgeClass = 'bg-success text-white';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} p-2">
                                            {{ $displayStatus }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>विवरण:</h5>
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-0">{{ $room->description ?? 'कुनै विवरण उपलब्ध छैन' }}</p>
                            </div>

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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection