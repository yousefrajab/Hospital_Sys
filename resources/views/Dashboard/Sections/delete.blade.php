@section('css')
    @include('Style.Style')
@endsection
<!-- Delete Section Modal - Enhanced Style -->
<div class="modal fade" id="delete{{ $section->id }}" tabindex="-1" aria-labelledby="deleteSectionModalLabel{{ $section->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deleteSectionModalLabel{{ $section->id }}">
                            {{ trans('Dashboard/sections_trans.delete_sections') }}
                        </h5>
                        <p class="mb-0 small">{{ trans('sections_trans.delete_warning') }}</p>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                <form action="{{ route('admin.Sections.destroy', 'test') }}" method="post" id="deleteSectionForm{{ $section->id }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $section->id }}">

                    <div class="text-center mb-4">
                        <div class="delete-icon-container">
                            <i class="fas fa-layer-group text-danger"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold mt-3">{{ trans('sections_trans.warning') }}!</h4>
                        <p class="text-muted">{{ trans('sections_trans.delete_section_warning') }}</p>
                    </div>

                    <div class="alert alert-light border-danger border text-center py-3">
                        <h5 class="font-weight-bold mb-1">{{ $section->name }}</h5>
                        <p class="text-muted small mb-0">{{ trans('sections_trans.section_id') }}: #{{ $section->id }}</p>
                    </div>

                    <div class="custom-control custom-checkbox my-4 text-center">
                        <input type="checkbox" class="custom-control-input" id="confirmDeleteSection{{ $section->id }}" required>
                        <label class="custom-control-label text-danger" for="confirmDeleteSection{{ $section->id }}">
                            {{ trans('sections_trans.I agree that this procedure cannot be undone') }}
                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i> {{ trans('sections_trans.Close') }}</button>
                        <button type="submit" id="deleteSectionBtn{{ $section->id }}" class="btn btn-danger bg-gradient-danger">
                            <i class="fas fa-trash-alt mr-2"></i>{{ trans('sections_trans.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@section('js')
<script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('/plugins/notify/js/notifit-custom.js') }}"></script>
@include('Script.Script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteSectionForm{{ $section->id }}');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                const deleteBtn = document.getElementById('deleteSectionBtn{{ $section->id }}');
                if (deleteBtn) {
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> جاري الحذف...';
                    deleteBtn.disabled = true;
                }
            });
        }
    });
</script>
@endsection



