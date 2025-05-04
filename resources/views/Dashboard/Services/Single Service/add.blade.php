@section('css')
    @include('Style.Style')
@endsection



<!-- Add Service Modal - Modern Design -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addServiceModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0"
            style="border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <!-- Modal Header with Glass Morphism Effect -->
            <div class="modal-header glass-header text-white py-4 position-relative">
                <div class="d-flex w-100 align-items-center">
                    <div class="icon-wrapper mr-3">
                        <div class="icon-circle bg-white-20">
                            <i class="fas fa-plus-circle text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0" id="addServiceModal">
                            {{ trans('Services.add_Service') }}
                        </h5>
                        <small class="text-white-80">{{ trans('Dashboard/sections_trans.add_new_service') }}</small>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-100" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="wave-effect"></div>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('admin.Service.store') }}" method="post" class="needs-validation" novalidate
                autocomplete="off">
                @csrf

                <div class="modal-body p-5">
                    <!-- Service Name Field with Floating Label -->
                    <div class="form-group floating-label-group">
                        <input type="text" name="name" id="name" class="form-control floating-input"
                            placeholder="{{ trans('Services.name') }}" required>
                        <label for="name" class="floating-label">
                            <i class="fas fa-tag mr-2"></i>{{ trans('Services.name') }}
                        </label>
                        <div class="invalid-feedback animated fadeIn">
                            {{ trans('validation.required', ['attribute' => trans('Services.name')]) }}
                        </div>
                    </div>

                    <!-- Price Field with Currency Input -->
                    <div class="form-group floating-label-group mt-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text currency-prepend">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                            </div>
                            <input type="number" name="price" id="price" class="form-control floating-input"
                                placeholder="{{ trans('Services.price') }}" min="0" step="0.01" required>
                            <label for="price" class="floating-label">
                                {{ trans('Services.price') }}
                            </label>
                        </div>
                        <div class="invalid-feedback animated fadeIn">
                            {{ trans('validation.min.numeric', ['attribute' => trans('Services.price'), 'min' => 0]) }}
                        </div>
                    </div>

                    <!-- Description Field with Floating Label and Counter -->
                    <div class="form-group floating-label-group mt-5">
                        <textarea class="form-control floating-input" name="description" id="description" rows="3"
                            placeholder="{{ trans('Services.description') }}" style="min-height: 100px;" data-maxlength="500"></textarea>
                        <label for="description" class="floating-label">
                            <i class="fas fa-align-left mr-2"></i>{{ trans('Services.description') }}
                        </label>
                        {{-- <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">{{trans('Dashboard/sections_trans.max_500_chars')}}</small>
                            <small class="text-muted">
                                <span id="charCount">0</span>/500 {{trans('general.characters')}}
                            </small>
                        </div> --}}
                    </div>
                </div>

                <!-- Modal Footer with Animated Buttons -->
                <div class="modal-footer bg-light-5">
                    <button type="button" class="btn btn-outline-secondary btn-rounded px-4 btn-hover-transform"
                        data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>{{ trans('Dashboard/sections_trans.Close') }}
                    </button>
                    <button type="submit"
                        class="btn btn-primary-gradient btn-rounded px-4 btn-hover-transform shadow-sm">
                        <i class="fas fa-plus-circle mr-2"></i>{{ trans('Dashboard/sections_trans.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modern CSS for Modal -->


<!-- Modern JavaScript for Dynamic Functionality -->

@section('js')
    <!-- 5. ملفات JS المخصصة (إذا وجدت) -->
    @include('Script.Script')
@endsection
