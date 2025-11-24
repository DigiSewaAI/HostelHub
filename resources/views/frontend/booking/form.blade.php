@extends('layouts.frontend')

@section('page-title', 'बुकिंग फारम - ' . $hostel->name)
@section('page-header', $hostel->name . ' को लागि बुकिंग')
@section('page-description', 'तलको फारम भरेर कोठा बुक गर्नुहोस्')

@section('content')
<div class="booking-container">
    <div class="booking-form-wrapper">
        <div class="booking-header">
            <h1 class="booking-title">{{ $hostel->name }} कोठा बुकिंग</h1>
            <p class="booking-subtitle">तपाईंको बुकिंग अनुरोध पेश गर्नुहोस्</p>
            
            @if($datesLocked)
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>मितिहरू खोजीबाट तोकिएका छन्:</strong> 
                चेक-इन: {{ $checkIn }}, चेक-आउट: {{ $checkOut }} |
                <a href="{{ route('search', request()->query()) }}" class="alert-link">खोजी पृष्ठमा फर्कनुहोस्</a>
            </div>
            @endif
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('hostel.book.store', $hostel->slug) }}" class="booking-form" id="bookingForm">
            @csrf
            
            <!-- Hidden fields for dates when locked -->
            @if($datesLocked)
                <input type="hidden" name="check_in_date" value="{{ $checkIn }}">
                <input type="hidden" name="check_out_date" value="{{ $checkOut }}">
            @endif
            
            <div class="form-section">
                <h3 class="section-title">व्यक्तिगत जानकारी</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">पूरा नाम *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="तपाईंको पूरा नाम" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">फोन नम्बर *</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               placeholder="९८XXXXXXXX" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">इमेल ठेगाना</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="form-control @error('email') is-invalid @enderror" 
                           placeholder="तपाईंको इमेल ठेगाना">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">बुकिंग जानकारी</h3>
                
                <!-- Dynamic Room Selection -->
                <div class="form-group">
                    <label for="room_id" class="form-label">कोठा छनौट गर्नुहोस् *</label>
                    <select id="room_id" name="room_id" 
                            class="form-control @error('room_id') is-invalid @enderror" required>
                        <option value="">कोठा छान्नुहोस्</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room['id'] }}" 
                                data-price="{{ $room['price'] }}"
                                data-available="{{ $room['available_beds'] }}"
                                {{ old('room_id') == $room['id'] ? 'selected' : '' }}>
                                {{ $room['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted" id="roomHelp">
                        उपलब्ध कोठाहरू देखाइएको छ
                    </small>
                </div>
                
                <!-- Date Selection -->
                <div class="form-row" id="dateSelection">
                    @if($datesLocked)
                        <!-- Show read-only dates when locked -->
                        <div class="form-group">
                            <label class="form-label">चेक-इन मिति *</label>
                            <input type="text" class="form-control" value="{{ $checkIn }}" readonly>
                            <small class="form-text text-info">
                                <i class="fas fa-lock"></i> खोजीबाट तोकिएको मिति
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">चेक-आउट मिति *</label>
                            <input type="text" class="form-control" value="{{ $checkOut }}" readonly>
                            <small class="form-text text-info">
                                <i class="fas fa-lock"></i> खोजीबाट तोकिएको मिति
                            </small>
                        </div>
                    @else
                        <!-- Show editable date inputs -->
                        <div class="form-group">
                            <label for="check_in_date" class="form-label">चेक-इन मिति *</label>
                            <input type="date" id="check_in_date" name="check_in_date" 
                                   value="{{ old('check_in_date') }}" 
                                   class="form-control @error('check_in_date') is-invalid @enderror" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            @error('check_in_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="check_out_date" class="form-label">चेक-आउट मिति *</label>
                            <input type="date" id="check_out_date" name="check_out_date" 
                                   value="{{ old('check_out_date') }}" 
                                   class="form-control @error('check_out_date') is-invalid @enderror" 
                                   min="{{ date('Y-m-d', strtotime('+2 days')) }}" required>
                            @error('check_out_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>
                
                <!-- Room Details Display -->
                <div class="room-details-card" id="roomDetails" style="display: none;">
                    <h4 class="room-details-title">कोठा विवरण</h4>
                    <div class="room-details-content">
                        <div class="detail-item">
                            <span class="detail-label">कोठा नम्बर:</span>
                            <span class="detail-value" id="detailRoomNumber">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">प्रकार:</span>
                            <span class="detail-value" id="detailRoomType">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">क्षमता:</span>
                            <span class="detail-value" id="detailCapacity">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">उपलब्ध ओठ:</span>
                            <span class="detail-value" id="detailAvailableBeds">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">मूल्य:</span>
                            <span class="detail-value" id="detailPrice">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label for="message" class="form-label">अतिरिक्त सन्देश (वैकल्पिक)</label>
                    <textarea id="message" name="message" rows="4" 
                              class="form-control @error('message') is-invalid @enderror" 
                              placeholder="तपाईंको अतिरिक्त आवश्यकता वा सन्देश...">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-submit">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> बुकिंग अनुरोध पेश गर्नुहोस्
                </button>
                
                <a href="{{ route('hostels.show', $hostel->slug) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> पछाडि जानुहोस्
                </a>
            </div>
        </form>
    </div>

    <div class="booking-sidebar">
        <div class="hostel-summary">
            <h3 class="sidebar-title">होस्टल विवरण</h3>
            <div class="hostel-info">
                <img src="{{ $hostel->logo_url ?? asset('images/default-hostel.png') }}" 
                     alt="{{ $hostel->name }}" class="hostel-image">
                <h4 class="hostel-name">{{ $hostel->name }}</h4>
                <p class="hostel-address">
                    <i class="fas fa-map-marker-alt"></i> {{ $hostel->address }}, {{ $hostel->city }}
                </p>
                <p class="hostel-contact">
                    <i class="fas fa-phone"></i> {{ $hostel->contact_phone }}
                </p>
                <div class="hostel-stats">
                    <div class="stat">
                        <span class="stat-number">{{ $hostel->available_rooms ?? 0 }}</span>
                        <span class="stat-label">उपलब्ध कोठा</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="booking-info">
            <h3 class="sidebar-title">बुकिंग प्रक्रिया</h3>
            <ul class="process-steps">
                <li class="step">
                    <div class="step-number">१</div>
                    <div class="step-content">
                        <strong>कोठा छनौट गर्नुहोस्</strong>
                        <p>उपलब्ध कोठाहरू मध्येबाट छनौट गर्नुहोस्</p>
                    </div>
                </li>
                <li class="step">
                    <div class="step-number">२</div>
                    <div class="step-content">
                        <strong>मिति तोक्नुहोस्</strong>
                        <p>चेक-इन र चेक-आउट मिति चयन गर्नुहोस्</p>
                    </div>
                </li>
                <li class="step">
                    <div class="step-number">३</div>
                    <div class="step-content">
                        <strong>अनुरोध पेश गर्नुहोस्</strong>
                        <p>तपाईंको अनुरोध होस्टल प्रबन्धकसम्म पुग्नेछ</p>
                    </div>
                </li>
            </ul>
        </div>
        
        <!-- Loading Indicator -->
        <div class="loading-indicator" id="loadingIndicator" style="display: none;">
            <div class="spinner"></div>
            <p>कोठा डाटा लोड हुँदैछ...</p>
        </div>
    </div>
</div>

<style>
.booking-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
}

.booking-form-wrapper {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.booking-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f1f1f1;
}

.booking-title {
    color: #001F5B;
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
}

.booking-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
}

.form-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: #f8f9fa;
}

.section-title {
    color: #001F5B;
    font-size: 1.2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #495057;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #001F5B;
    box-shadow: 0 0 0 3px rgba(0, 31, 91, 0.1);
}

.form-submit {
    text-align: center;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

.btn-primary {
    background: #001F5B;
    color: white;
}

.btn-primary:hover {
    background: #001338;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
    margin-left: 1rem;
}

.btn-secondary:hover {
    background: #5a6268;
}

.booking-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.hostel-summary, .booking-info {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.sidebar-title {
    color: #001F5B;
    font-size: 1.2rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #001F5B;
}

.hostel-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.hostel-name {
    color: #001F5B;
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.hostel-address, .hostel-contact {
    color: #6c757d;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.hostel-stats {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.stat {
    text-align: center;
    flex: 1;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    color: #001F5B;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.process-steps {
    list-style: none;
    padding: 0;
}

.step {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

.step:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.step-number {
    background: #001F5B;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
}

.step-content strong {
    color: #001F5B;
    display: block;
    margin-bottom: 0.25rem;
}

.step-content p {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
}

.alert {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1.5rem;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.alert-link {
    color: #062c33;
    text-decoration: underline;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.is-invalid {
    border-color: #dc3545;
}

/* Room Details Card */
.room-details-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.room-details-title {
    color: #001F5B;
    font-size: 1.1rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.room-details-content {
    display: grid;
    gap: 0.5rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.25rem 0;
}

.detail-label {
    font-weight: 600;
    color: #495057;
}

.detail-value {
    color: #001F5B;
    font-weight: 500;
}

/* Loading Indicator */
.loading-indicator {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #001F5B;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media (max-width: 968px) {
    .booking-container {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const roomDetails = document.getElementById('roomDetails');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const roomHelp = document.getElementById('roomHelp');
    const submitBtn = document.getElementById('submitBtn');
    const hostelSlug = '{{ $hostel->slug }}';
    const datesLocked = {{ $datesLocked ? 'true' : 'false' }};

    // Room data cache
    let roomData = {};

    // Initialize room data from initial options
    Array.from(roomSelect.options).forEach(option => {
        if (option.value) {
            roomData[option.value] = {
                room_number: option.text.match(/कोठा (\w+)/)?.[1] || '-',
                type: option.getAttribute('data-type') || '-',
                available_beds: option.getAttribute('data-available') || '0',
                price: option.getAttribute('data-price') || '0'
            };
        }
    });

    // Room selection handler
    roomSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const roomId = this.value;
        
        if (roomId && roomData[roomId]) {
            const room = roomData[roomId];
            document.getElementById('detailRoomNumber').textContent = room.room_number;
            document.getElementById('detailRoomType').textContent = room.type;
            document.getElementById('detailCapacity').textContent = room.capacity || '-';
            document.getElementById('detailAvailableBeds').textContent = room.available_beds;
            document.getElementById('detailPrice').textContent = 'रु ' + parseInt(room.price).toLocaleString('ne-NP');
            roomDetails.style.display = 'block';
        } else {
            roomDetails.style.display = 'none';
        }
    });

    // Date change handler (only if dates are not locked)
    if (!datesLocked) {
        const dateInputs = [checkInDate, checkOutDate];
        dateInputs.forEach(input => {
            if (input) {
                input.addEventListener('change', function() {
                    // Wait for both dates to be filled
                    const checkIn = checkInDate?.value;
                    const checkOut = checkOutDate?.value;
                    
                    if (checkIn && checkOut) {
                        loadRoomsForDates(checkIn, checkOut);
                    }
                });
            }
        });
    }

    // Load rooms for specific dates
    function loadRoomsForDates(checkIn, checkOut) {
        showLoading();
        
        fetch(`/api/hostel/${hostelSlug}/rooms?check_in=${checkIn}&check_out=${checkOut}`)
            .then(response => response.json())
            .then(data => {
                hideLoading();
                
                if (data.success) {
                    updateRoomOptions(data.rooms);
                    roomHelp.textContent = `${data.rooms.length} वटा कोठा उपलब्ध छन्`;
                } else {
                    roomHelp.textContent = 'कोठा डाटा लोड गर्न असफल';
                    showError('कोठा डाटा लोड गर्न असफल');
                }
            })
            .catch(error => {
                hideLoading();
                roomHelp.textContent = 'कोठा डाटा लोड गर्न असफल';
                console.error('Error loading rooms:', error);
            });
    }

    // Update room options in select
    function updateRoomOptions(rooms) {
        // Clear existing options except the first one
        while (roomSelect.options.length > 1) {
            roomSelect.remove(1);
        }
        
        // Clear room data cache
        roomData = {};
        
        if (rooms.length === 0) {
            roomHelp.textContent = 'कुनै पनि कोठा उपलब्ध छैन';
            roomDetails.style.display = 'none';
            return;
        }
        
        // Add new room options
        rooms.forEach(room => {
            const option = document.createElement('option');
            option.value = room.id;
            option.textContent = `${room.nepali_type} - कोठा ${room.room_number} (उपलब्ध: ${room.available_beds}, रु ${room.price})`;
            option.setAttribute('data-price', room.price);
            option.setAttribute('data-available', room.available_beds);
            option.setAttribute('data-type', room.nepali_type);
            roomSelect.appendChild(option);
            
            // Store room data
            roomData[room.id] = {
                room_number: room.room_number,
                type: room.nepali_type,
                capacity: room.capacity,
                available_beds: room.available_beds,
                price: room.price
            };
        });
        
        roomHelp.textContent = `${rooms.length} वटा कोठा उपलब्ध छन्`;
    }

    // Loading functions
    function showLoading() {
        loadingIndicator.style.display = 'block';
        roomSelect.disabled = true;
        submitBtn.disabled = true;
    }

    function hideLoading() {
        loadingIndicator.style.display = 'none';
        roomSelect.disabled = false;
        submitBtn.disabled = false;
    }

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        document.querySelector('.booking-header').appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    // Form validation
    const form = document.getElementById('bookingForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^[0-9+\-\s()]{10,15}$/;
            
            if (!phoneRegex.test(phone)) {
                e.preventDefault();
                alert('कृपया मान्य फोन नम्बर प्रविष्ट गर्नुहोस्');
                return false;
            }
            
            // Validate room selection
            if (!roomSelect.value) {
                e.preventDefault();
                alert('कृपया कोठा छान्नुहोस्');
                return false;
            }
        });
    }

    // Set minimum dates
    if (checkInDate) {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        checkInDate.min = tomorrow.toISOString().split('T')[0];
    }
    
    if (checkOutDate) {
        const today = new Date();
        const dayAfterTomorrow = new Date(today);
        dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 2);
        checkOutDate.min = dayAfterTomorrow.toISOString().split('T')[0];
    }
});
</script>
@endsection