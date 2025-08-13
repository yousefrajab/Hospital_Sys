<!-- Add Service Modal - Modern Design -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addServiceModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0"
            style="border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header glass-header text-white py-4 position-relative">
                <div class="d-flex w-100 align-items-center">
                    <div class="icon-wrapper mr-3">
                        <div class="icon-circle bg-white-20">
                            <i class="fas fa-plus-circle text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0" id="addServiceModal">
                            {{ trans('AdminSb.add_Service') }}
                        </h5>
                        <small class="text-white-80">{{ trans('AdminSb.add_new_service') }}</small>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-100" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <div class="wave-effect"></div>
            </div>

            <form action="{{ route('admin.Service.store') }}" method="post" class="needs-validation" novalidate
                autocomplete="off">
                @csrf

                <div class="modal-body p-5">
                    <div class="form-group floating-label-group">
                        <input type="text" name="name" id="name_add" class="form-control floating-input"
                            placeholder=" " required>
                        <label for="name_add" class="floating-label">
                            <i class="fas fa-tag mr-2"></i>{{ trans('AdminSb.name') }}
                        </label>
                        <div class="invalid-feedback animated fadeIn">
                            {{ trans('validation.required', ['attribute' => trans('AdminSb.name')]) }}
                        </div>
                    </div>

                    <div class="form-group floating-label-group mt-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text currency-prepend">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                            </div>
                            <input type="number" name="price" id="price_add" class="form-control floating-input"
                                placeholder=" " min="0" step="0.01" required>
                            <label for="price_add" class="floating-label">
                                {{ trans('AdminSb.price') }}
                            </label>
                        </div>
                        <div class="invalid-feedback animated fadeIn">
                            {{ trans('validation.min.numeric', ['attribute' => trans('AdminSb.price'), 'min' => 0]) }}
                        </div>
                    </div>

                    <div class="form-group floating-label-group mt-4">
                        <label for="doctor_id_add" class="floating-label active">
                            <i class="fas fa-user-md mr-2"></i>{{ trans('AdminSb.Doctor') }}
                        </label>
                        <select name="doctor_id" id="doctor_id_add" class="form-control custom-select floating-input">
                            <option value="">{{ trans('AdminSb.select_doctor_optional') }}</option>
                            @php $allDoctorsCollection = collect($doctors ?? []); @endphp
                            @foreach ($allDoctorsCollection as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group floating-label-group mt-4">
                        <textarea class="form-control floating-input" name="description" id="description_add" rows="3" placeholder=" "
                            style="min-height: 100px;" data-maxlength="500"></textarea>
                        <label for="description_add" class="floating-label">
                            <i class="fas fa-align-left mr-2"></i>{{ trans('AdminSb.description') }}
                        </label>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">
                                <span id="charCount_add">0</span>/500
                            </small>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="font-weight-bold text-primary d-block">{{ trans('AdminSb.Status') }}</label>
                        <div class="custom-control custom-switch custom-switch-lg">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" class="custom-control-input" id="status_add" name="status"
                                value="1" checked>
                            <label class="custom-control-label" for="status_add">
                                <span class="font-weight-bold status-text-js text-success">
                                    {{ trans('AdminSb.Enabled') }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light-5">
                    <button type="button" class="btn btn-outline-secondary btn-rounded px-4 btn-hover-transform"
                        data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>{{ trans('AdminSb.close') }}
                    </button>
                    <button type="submit"
                        class="btn btn-primary-gradient btn-rounded px-4 btn-hover-transform shadow-sm">
                        <i class="fas fa-plus-circle mr-2"></i>{{ trans('AdminSb.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script for character count and status text (remains the same)
    (function() {
        const textareaAdd = document.getElementById('description_add');
        const charCountAdd = document.getElementById('charCount_add');
        if (textareaAdd && charCountAdd) {
            const maxLengthAdd = textareaAdd.getAttribute('data-maxlength');
            charCountAdd.textContent = textareaAdd.value.length;
            textareaAdd.addEventListener('input', function() {
                charCountAdd.textContent = this.value.length;
            });
        }

        const statusCheckboxAdd = document.getElementById('status_add');
        if (statusCheckboxAdd) {
            const statusLabelTextAdd = statusCheckboxAdd.closest('.custom-switch').querySelector('.status-text-js');
            statusCheckboxAdd.addEventListener('change', function() {
                statusLabelTextAdd.textContent = this.checked ? "{{ trans('doctors.Enabled') }}" :
                    "{{ trans('doctors.Not_enabled') }}";
                statusLabelTextAdd.className = 'font-weight-bold status-text-js ' + (this.checked ?
                    'text-success' : 'text-danger');
            });
        }
    })();
</script>
