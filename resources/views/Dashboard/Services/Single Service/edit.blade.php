@section('css')
    @include('Style.Style')
@endsection


<!-- Edit Service Modal - Modern Design -->
<div class="modal fade" id="edit{{ $service->id }}" tabindex="-1" role="dialog" aria-labelledby="editServiceModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0"
            style="border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header glass-header text-white py-4 position-relative">
                <div class="d-flex w-100 align-items-center">
                    <div class="icon-wrapper mr-3">
                        <div class="icon-circle bg-white-20">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0" id="editServiceModal">
                            {{ trans('AdminSb.edit_Service') }}
                        </h5>
                        <small class="text-white-80">{{ trans('AdminSb.update_service_details') }}</small>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-100" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="wave-effect"></div>
            </div>

            <form action="{{ route('admin.Service.update', 'test') }}" method="post" class="needs-validation"
                novalidate autocomplete="off">
                @csrf
                @method('patch')
                <input type="hidden" name="id" value="{{ $service->id }}">

                <div class="modal-body p-5">
                    <div class="form-group floating-label-group">
                        <input type="text" name="name" id="name" class="form-control floating-input"
                            value="{{ $service->name }}" required>
                        <label for="name" class="floating-label">
                            <i class="fas fa-tag mr-2"></i>{{ trans('AdminSb.name') }}
                        </label>
                        <div class="invalid-feedback animated fadeIn">
                            {{ trans('validation.required', ['attribute' => trans('AdminSb.name')]) }}
                        </div>
                    </div>

                    <div class="form-group floating-label-group mt-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text currency-prepend">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                            </div>
                            <input type="number" name="price" id="price" value="{{ $service->price }}"
                                class="form-control floating-input" min="0" step="0.01" required>
                            <label for="price" class="floating-label">
                                {{ trans('AdminSb.price') }}
                            </label>
                        </div>
                        <div class="invalid-feedback animated fadeIn">
                            {{ trans('validation.min.numeric', ['attribute' => trans('AdminSb.price'), 'min' => 0]) }}
                        </div>
                    </div>

                    <div class="form-group floating-label-group mt-4">
                        <label for="doctor_id_edit_{{ $service->id }}" class="floating-label active">
                            <i class="fas fa-user-md mr-2"></i>{{ trans('AdminSb.Doctor') }}
                        </label>
                        <select name="doctor_id" id="doctor_id_edit_{{ $service->id }}"
                            class="form-control custom-select floating-input">
                            <option value="">{{ trans('AdminSb.select_doctor_optional') }}</option>
                            @php $allDoctorsCollection = collect($doctors ?? []); @endphp
                            @foreach ($allDoctorsCollection as $doctor)
                                <option value="{{ $doctor->id }}"
                                    {{ $service->doctor_id == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group floating-label-group mt-5">
                        <textarea class="form-control floating-input" name="description" id="description{{ $service->id }}" rows="3"
                            placeholder="{{ trans('AdminSb.description') }}" data-maxlength="500" style="min-height: 100px;">{{ $service->description }}</textarea>
                        <label for="description{{ $service->id }}" class="floating-label">
                            <i class="fas fa-align-left mr-2"></i>{{ trans('AdminSb.description') }}
                        </label>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">
                                <span id="charCount{{ $service->id }}">0</span>/500
                            </small>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="font-weight-bold text-primary d-block">{{ trans('AdminSb.Status') }}</label>
                        <div class="custom-control custom-switch custom-switch-lg">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" class="custom-control-input" id="status{{ $service->id }}"
                                name="status" value="1" {{ $service->status == 1 ? 'checked' : '' }}>
                            <label class="custom-control-label" for="status{{ $service->id }}">
                                <span
                                    class="font-weight-bold {{ $service->status == 1 ? 'text-success' : 'text-danger' }}">
                                    {{ $service->status == 1 ? trans('AdminSb.Enabled') : trans('AdminSb.Not_enabled') }}
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
                        <i class="fas fa-save mr-2"></i>{{ trans('AdminSb.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



@section('js')
    @include('Script.Script')
@endsection
