<div class="modal fade" id="roomDetailsModal" tabindex="-1" aria-labelledby="roomDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomDetailsModalLabel">कोठा विवरण</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>कोठा नम्बर:</strong> <span id="modalRoomNumber">-</span></p>
                        <p><strong>कोठा प्रकार:</strong> <span id="modalRoomType">-</span></p>
                        <p><strong>मासिक भाडा:</strong> <span id="modalRoomRent">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>स्थिति:</strong> <span id="modalRoomStatus">-</span></p>
                        <p><strong>सुविधाहरू:</strong> <span id="modalRoomFacilities">-</span></p>
                        <p><strong>उपलब्धता:</strong> <span id="modalRoomAvailability">-</span></p>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>विवरण:</h6>
                    <p id="modalRoomDescription" class="text-muted">-</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">बन्द गर्नुहोस्</button>
                <button type="button" class="btn btn-primary" id="bookRoomBtn">कोठा बुक गर्नुहोस्</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomDetailsModal = document.getElementById('roomDetailsModal');
    if (roomDetailsModal) {
        roomDetailsModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const roomId = button.getAttribute('data-room-id');
            const roomNumber = button.getAttribute('data-room-number');
            const roomType = button.getAttribute('data-room-type');
            const roomRent = button.getAttribute('data-room-rent');
            const roomStatus = button.getAttribute('data-room-status');
            const roomFacilities = button.getAttribute('data-room-facilities');
            const roomDescription = button.getAttribute('data-room-description');

            document.getElementById('modalRoomNumber').textContent = roomNumber || '-';
            document.getElementById('modalRoomType').textContent = roomType || '-';
            document.getElementById('modalRoomRent').textContent = roomRent ? 'रु ' + roomRent : '-';
            document.getElementById('modalRoomStatus').textContent = roomStatus || '-';
            document.getElementById('modalRoomFacilities').textContent = roomFacilities || '-';
            document.getElementById('modalRoomDescription').textContent = roomDescription || '-';
            
            const availability = roomStatus === 'available' ? 'उपलब्ध' : 'उपलब्ध छैन';
            document.getElementById('modalRoomAvailability').textContent = availability;

            const bookBtn = document.getElementById('bookRoomBtn');
            if (roomStatus === 'available') {
                bookBtn.style.display = 'block';
                bookBtn.onclick = function() {
                    window.location.href = "{{ route('student.bookings.create') }}?room_id=" + roomId;
                };
            } else {
                bookBtn.style.display = 'none';
            }
        });
    }
});
</script>