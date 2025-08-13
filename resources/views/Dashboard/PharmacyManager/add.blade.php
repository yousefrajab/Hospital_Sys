<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addPharmacyManagerLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header"
                style="background: linear-gradient(135deg, var(--primary-color, #4A90E2), var(--secondary-color, #50E3C2)); color: white;">
                <h5 class="modal-title" id="addPharmacyManagerLabel">
                    <i class="fas fa-user-plus mr-2"></i>{{ trans('AdminSb.Add a new pharmacy manager') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.9;">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.pharmacy_manager.store') }}" method="post" enctype="multipart/form-data"
                autocomplete="off" id="addPharmacyManagerForm">
                @csrf

                <div class="modal-body py-4">
                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <div class="avatar-upload-container mx-auto"
                                style="width: 150px; height: 150px; position: relative;">
                                <img id="add_output_photo" src="{{ URL::asset('Dashboard/img/doctorr_default.png') }}"
                                    alt="{{ trans('AdminSb.image') }}"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 3px solid #eee; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                <label for="add_avatar_upload_photo" class="btn btn-sm"
                                    style="position: absolute; bottom: 5px; right: 5px; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor:pointer; background-color: var(--primary-color, #4A90E2); color:white;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input id="add_avatar_upload_photo" type="file" accept="image/*" name="photo"
                                    onchange="previewImage(event, 'add_output_photo')" style="display: none;">
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"
                                    style="color: var(--primary-color, #4A90E2);">{{ trans('AdminSb.full name') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control rounded-lg shadow-sm"  required>
                            </div>
                        </div>

                        <!-- National ID -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"
                                    style="color: var(--primary-color, #4A90E2);">{{ trans('AdminSb.national_id') }}<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="national_id" value="{{ old('national_id') }}"
                                    class="form-control rounded-lg shadow-sm" 
                                    pattern="[0-9]{9,10}" maxlength="9"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"
                                    style="color: var(--primary-color, #4A90E2);">{{ trans('AdminSb.email') }}<span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control rounded-lg shadow-sm" placeholder="example@domain.com" required>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"
                                    style="color: var(--primary-color, #4A90E2);">{{ trans('doctors.phone') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control rounded-lg shadow-sm" name="phone"
                                    value="{{ old('phone') }}" type="tel" pattern="^05\d{8}$"
                                    title="يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام" maxlength="10" required>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold"
                                    style="color: var(--primary-color, #4A90E2);">{{ trans('AdminSb.new password') }}<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control rounded-lg shadow-sm"
                                        placeholder="********" required id="add_password_field">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary toggle-password-button"
                                            type="button" data-target-input="#add_password_field"
                                            style="border-top-right-radius: .5rem !important; border-bottom-right-radius: .5rem !important; border-left:0;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>{{ trans('Dashboard/sections_trans.Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"
                        style="background: linear-gradient(135deg, var(--primary-color, #4A90E2), var(--secondary-color, #50E3C2)); border: none;">
                        <i class="fas fa-save mr-2"></i>{{ trans('Dashboard/sections_trans.submit') }}
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
                } else {

                }
            };
        }


        document.addEventListener('DOMContentLoaded', function() {
            const addModal = document.getElementById('add');
            if (addModal) {
                const toggleButtons = addModal.querySelectorAll('.toggle-password-button');
                toggleButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        const targetInputSelector = this.dataset.targetInput;
                        const input = addModal.querySelector(
                        targetInputSelector); // Scope to current modal
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
        });

    })();
</script>

<style>
    :root {
        --primary-color: #4A90E2;
        --secondary-color: #50E3C2;
    }

    .form-control:focus {
        border-color: var(--primary-color, #4A90E2);
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }
</style>
