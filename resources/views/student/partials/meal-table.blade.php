@if($menus->count() > 0)
<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead class="bg-light">
            <tr>
                <th width="20%">दिन / मिति</th>
                <th width="30%">खानाका वस्तुहरू</th>
                <th width="25%">तस्बिर</th>
                <th width="25%">विवरण</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
            <tr>
                <!-- Day/Date Column -->
                <td>
                    <div class="d-flex flex-column">
                        @php
                            $carbonDate = \Carbon\Carbon::parse($menu->date);
                            $dayMap = [
                                'Sunday' => 'आइतबार',
                                'Monday' => 'सोमबार', 
                                'Tuesday' => 'मंगलबार',
                                'Wednesday' => 'बुधबार',
                                'Thursday' => 'बिहिबार',
                                'Friday' => 'शुक्रबार',
                                'Saturday' => 'शनिबार'
                            ];
                            $nepaliDay = $dayMap[$carbonDate->format('l')] ?? $carbonDate->format('l');
                        @endphp
                        <strong class="text-primary">{{ $nepaliDay }}</strong>
                        <small class="text-muted">{{ $carbonDate->format('Y-m-d') }}</small>
                        <div class="mt-2">
                            <span class="badge bg-{{ $color }} 
                                @if($color == 'warning') text-dark @else text-white @endif 
                                p-2">
                                <i class="fas fa-{{ $icon }} me-1"></i>
                                {{ $typeNepali }}
                            </span>
                        </div>
                        <small class="text-info mt-1">
                            <i class="fas fa-clock me-1"></i>
                            @if($type == 'breakfast')
                                ७:०० - ९:०० बिहान
                            @elseif($type == 'lunch')
                                १२:०० - २:०० दिउँसो
                            @else
                                ७:०० - ९:०० बेलुका
                            @endif
                        </small>
                    </div>
                </td>
                
                <!-- Items Column -->
                <td>
                    <div class="meal-items">
                        @if(is_array($menu->items))
                            @foreach($menu->items as $item)
                            <span class="d-inline-block mb-1 me-1">
                                <span class="badge bg-light text-dark border p-2">
                                    <i class="fas fa-check text-success me-1"></i>
                                    {{ $item }}
                                </span>
                            </span>
                            @endforeach
                        @else
                            <div class="p-2 bg-light rounded">
                                <i class="fas fa-list-ul me-2 text-primary"></i>
                                {{ $menu->items }}
                            </div>
                        @endif
                    </div>
                </td>
                
                <!-- Image Column -->
                <td>
                    @if($menu->image && file_exists(storage_path('app/public/' . $menu->image)))
                    <div class="meal-image-container">
                        <img src="{{ asset('storage/' . $menu->image) }}" 
                             alt="Meal Image" 
                             class="img-fluid rounded shadow-sm" 
                             style="max-height: 100px; width: auto; cursor: pointer;"
                             onclick="openImageModal('{{ asset('storage/' . $menu->image) }}', '{{ $typeNepali }} - {{ $carbonDate->format('Y-m-d') }}')">
                        <small class="d-block text-center text-muted mt-1">तस्बिर हेर्न क्लिक गर्नुहोस्</small>
                    </div>
                    @else
                    <div class="text-center">
                        <div class="no-image-placeholder rounded bg-light d-flex align-items-center justify-content-center" 
                             style="height: 100px; width: 100%; border: 2px dashed #dee2e6;">
                            <div>
                                <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0" style="font-size: 0.8rem;">तस्बिर उपलब्ध छैन</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </td>
                
                <!-- Description Column -->
                <td>
                    @if($menu->description)
                    <div class="description-box p-3 bg-light rounded">
                        <p class="mb-0">{{ $menu->description }}</p>
                    </div>
                    @else
                    <span class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        कुनै विवरण छैन
                    </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-5">
    <div class="mb-4">
        <i class="fas fa-{{ $icon }} fa-4x text-muted opacity-25"></i>
    </div>
    <h4 class="text-muted mb-3">
        कुनै {{ $typeNepali }} को मेनु छैन
    </h4>
    <p class="text-muted">{{ $typeNepali }} को लागि अहिलेसम्म कुनै मेनु थपिएको छैन।</p>
</div>
@endif