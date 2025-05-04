<!-- Add Section Modal - Modern Style -->
@section('css')
    @include('Style.Style')
@endsection



<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-plus-circle fa-2x mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold" id="addSectionModalLabel">
                            {{ trans('Dashboard/sections_trans.add_sections') }}
                        </h5>
                        <p class="mb-0 small">{{ trans('sections_trans.Enter the data of the new section') }}</p>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('admin.Sections.store') }}" method="post" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sectionName" class="font-weight-bold">
                            <i class="fas fa-tag text-primary mr-1"></i>
                            {{ trans('sections_trans.name_sections') }}
                        </label>
                        <input type="text" name="name" class="form-control rounded-pill shadow-sm" id="sectionName" placeholder="{{ trans('sections_trans.name_sections') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="sectionDesc" class="font-weight-bold">
                            <i class="fas fa-align-left text-primary mr-1"></i>
                            {{ trans('sections_trans.description') }}
                        </label>
                        <input type="text" name="description" class="form-control rounded-pill shadow-sm" id="sectionDesc" placeholder="{{ trans('sections_trans.description') }}" >
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light bg-gradient-light rounded-pill px-4" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-2"></i>{{ trans('sections_trans.Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary bg-gradient-primary rounded-pill px-4">
                        <i class="fas fa-check-circle mr-2"></i>{{ trans('sections_trans.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

