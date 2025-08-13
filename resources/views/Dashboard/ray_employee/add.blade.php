<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addRayEmployeeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header"
                style="background: linear-gradient(135deg, var(--primary-color, #007bff), var(--accent-color, #17a2b8)); color: white;">
                <h5 class="modal-title" id="addRayEmployeeModalLabel">
                    <i class="fas fa-user-plus mr-2"></i>{{ trans('AdminSb.add_new_ray_employee') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.9;">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.ray_employee.store') }}" method="post" enctype="multipart/form-data"
                autocomplete="off" id="addRayEmployeeForm">
                @csrf

                <div class="modal-body py-4">
                    <div class="row">
                        <!-- Image Upload Section -->
                        <div class="col-md-12 text-center mb-4">
                            <div class="avatar-upload-container mx-auto"
                                style="width: 150px; height: 150px; position: relative;">
                                <img id="add_ray_output_photo"
                                    src="{{ URL::asset('Dashboard/img/default_avatar.png') }}" {{-- Default image for new employee --}}
                                    alt="{{ trans('AdminSb.employee_image') }}"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 3px solid #eee; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                <label for="add_ray_avatar_upload_photo" class="btn btn-sm"
                                    style="position: absolute; bottom: 5px; right: 5px; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor:pointer; background-color: var(--primary-color, #007bff); color:white;"
                                    title="{{ trans('AdminSb.select_image') }}">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input id="add_ray_avatar_upload_photo" type="file" accept="image/*" name="photo"
                                    onchange="previewImage(event, 'add_ray_output_photo')" style="display: none;">
                                <small
                                    class="form-text text-muted d-block mt-2">{{ trans('AdminSb.optional_square_image_preferred') }}</small>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"
                                    style="color: var(--primary-color, #007bff);">{{ trans('AdminSb.full name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control rounded-lg shadow-sm"
                                    placeholder="{{ trans('AdminSb.enter_full_name') }}" required>
                            </div>
                        </div>

                        <!-- National ID -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"
                                    style="color: var(--primary-color, #007bff);">{{ trans('AdminSb.national_id') }}
                                    <span class="text-danger">*</span></label>
                                <input type="text" name="national_id" value="{{ old('national_id') }}"
                                    class="form-control rounded-lg shadow-sm"
                                    placeholder="{{ trans('AdminSb.enter_national_id') }}" pattern="[0-9]{9,10}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="9" required>
                                <small
                                    class="form-text text-muted">{{ trans('AdminSb.must_be_9_or_10_digits') }}</small>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"
                                    style="color: var(--primary-color, #007bff);">{{ trans('AdminSb.email') }} <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control rounded-lg shadow-sm" placeholder="example@domain.com" required>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"
                                    style="color: var(--primary-color, #007bff);">{{ trans('AdminSb.phone') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control rounded-lg shadow-sm" name="phone"
                                    value="{{ old('phone') }}" type="tel" pattern="^05\d{8}$"
                                    title="{{ trans('AdminSb.phone_validation_rule') }}" maxlength="10" required>
                                <small class="form-text text-muted">{{ trans('AdminSb.phone_example') }}</small>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add_ray_status_employee" class="font-weight-bold"
                                    style="color: var(--primary-color, #007bff);">{{ trans('AdminSb.status') }} <span
                                        class="text-danger">*</span></label>
                                <select name="status" id="add_ray_status_employee"
                                    class="form-control rounded-lg shadow-sm custom-select" required>
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                        {{ trans('AdminSb.active') }}</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                                        {{ trans('AdminSb.inactive') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"
                                    style="color: var(--primary-color, #007bff);">{{ trans('AdminSb.password') }}
                                    <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control rounded-lg shadow-sm"
                                        placeholder="********" required id="add_ray_password_field"
                                        autocomplete="new-password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary toggle-password-button"
                                            type="button" data-target-input="#add_ray_password_field"
                                            style="border-top-right-radius: .5rem !important; border-bottom-right-radius: .5rem !important; border-left:0;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small
                                    class="form-text text-muted">{{ trans('AdminSb.password_min_8_chars') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>{{ trans('AdminSb.close') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"
                        style="background: linear-gradient(135deg, var(--primary-color, #007bff), var(--accent-color, #17a2b8)); border: none;">
                        <i class="fas fa-save mr-2"></i>{{ trans('AdminSb.add_the_employee') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        if (typeof window.previewImage !== 'function') {
            window.previewImage = function(event, outputId) {
                const output = document.getElementById(outputId);
                if (event.target.files && event.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        output.src = e.target.result;
                    }
                    reader.readAsDataURL(event.target.files[0]);
                }
            };
        }


        const addRayModal = document.getElementById('addRayEmployeeModal');
        if (addRayModal) {
            const toggleButtonsInAddModal = addRayModal.querySelectorAll('.toggle-password-button');
            toggleButtonsInAddModal.forEach(function(button) {
                button.addEventListener('click', function() {
                    const targetInputSelector = this.dataset.targetInput;
                    // Query within the current modal context
                    const input = addRayModal.querySelector(targetInputSelector);
                    if (input) {
                        const icon = this.querySelector('i');
                        if (input.getAttribute('type') === 'password') {
                            input.setAttribute('type', 'text');
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            input.setAttribute('type', 'password');
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    }
                });
            });
        }
    })();
</script>
<style>
    :root {
        --primary-color: #007bff;
        --accent-color: #17a2b8;
    }

    .form-control:focus {
        border-color: var(--primary-color, #007bff);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
