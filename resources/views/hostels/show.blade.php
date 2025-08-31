<div class="tab-pane fade" id="meals" role="tabpanel">
    <h4 class="mt-4">खानाको योजना</h4>
    @if($hostel->mealMenus->where('is_active', true)->isEmpty())
        <p>खानाको योजना उपलब्ध छैन।</p>
    @else
        @foreach(['sunday','monday','tuesday','wednesday','thursday','friday','saturday'] as $day)
            @php
                $menus = $hostel->mealMenus->where('day_of_week', $day)->where('is_active', true);
            @endphp
            @if($menus->isNotEmpty())
                <h5>{{ ucfirst($day) }}</h5>
                @foreach($menus as $menu)
                    <p><strong>{{ ucfirst($menu->meal_type) }}:</strong> {{ implode(', ', $menu->items) }}</p>
                    @if($menu->image)
                        <img src="{{ Storage::url($menu->image) }}" width="80" alt="Meal">
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
</div>