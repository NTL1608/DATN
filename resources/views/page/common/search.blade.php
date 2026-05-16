<div class="container">
    <div class="event-filter-warp">
        <div class="row">
            <div class="col-xl-12">
                <form class="event-filter-form" action="{{ route('page.search') }}">
                    <div class="ef-item">
                        <input type="text" name="keyword" placeholder="Tìm kiếm với tên bác sĩ">
                        <i class="material-icons">search</i>
                    </div>
                    <div class="ef-item">
                        <select name="clinic" id="clinic-select">
                            <option value="">Khoa khám bệnh</option>
                            @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ef-item">
                        <select name="specialtie" id="specialty-select">
                            <option value="">Dịch vụ</option>
                            @foreach($specialties as $specialtie)
                            <option value="{{ $specialtie->id }}">{{ $specialtie->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="site-btn sb-gradient">Tìm kiếm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clinicSelect = document.getElementById('clinic-select');
        const specialtySelect = document.getElementById('specialty-select');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const loadSpecialtyUrl = "{{ route('page.ajax.load.specialty') }}";

        // Store original specialties for reset
        const originalSpecialties = [];
        @if(isset($specialties))
        @foreach($specialties as $specialtie)
        originalSpecialties.push({
            id: "{{ $specialtie->id }}",
            name: "{{ $specialtie->name }}"
        });
        @endforeach
        @endif

        // Function to populate specialty select
        function populateSpecialties(specialties) {
            specialtySelect.innerHTML = '<option value="">Dịch vụ</option>';
            specialties.forEach(function(specialty) {
                const option = document.createElement('option');
                option.value = specialty.id;
                option.textContent = specialty.name;
                specialtySelect.appendChild(option);
            });
        }

        if (clinicSelect && specialtySelect) {
            clinicSelect.addEventListener('change', function() {
                const clinicId = this.value;

                if (clinicId) {
                    // Show loading state
                    specialtySelect.disabled = true;
                    specialtySelect.innerHTML = '<option value="">Đang tải...</option>';

                    // Make AJAX request
                    fetch(loadSpecialtyUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                clinic_id: clinicId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            specialtySelect.disabled = false;

                            if (data.code === 200 && data.specialties && data.specialties.length > 0) {
                                // Add specialties from the selected clinic
                                populateSpecialties(data.specialties);
                            } else {
                                // No specialties found for this clinic
                                specialtySelect.innerHTML = '<option value="">Không có dịch vụ</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            specialtySelect.disabled = false;
                            specialtySelect.innerHTML = '<option value="">Dịch vụ</option>';
                        });
                } else {
                    // If no clinic selected, restore all specialties
                    specialtySelect.disabled = false;
                    populateSpecialties(originalSpecialties);
                }
            });
        }
    });
</script>