<!-- Delete Insurance Modal - Enhanced Version -->
<div class="modal fade" id="Deleted{{ $insurance->id }}" tabindex="-1" aria-labelledby="deleteInsuranceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-invoice-dollar fa-2x mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deleteInsuranceModalLabel">{{ trans('insurance.Title_deleted') }}</h5>
                        <p class="mb-0 small">{{ trans('sections_trans.Warning') }}</p>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                <form action="{{ route('admin.insurance.destroy', 'test') }}" method="post" id="deleteInsuranceForm{{ $insurance->id }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $insurance->id }}">

                    <div class="text-center mb-4">
                        <div class="delete-icon-container">
                            <i class="fas fa-file-invoice-dollar text-danger fa-3x"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold mt-3">تحذير!</h4>
                        <p class="text-muted">أنت على وشك حذف شركة التأمين التالية:</p>
                    </div>

                    <div class="alert alert-light border-danger border text-center py-3">
                        <h5 class="font-weight-bold mb-1">{{ $insurance->name }}</h5>
                        <p class="text-muted mb-0">رمز الشركة: #{{ $insurance->insurance_code }}</p>
                        <p class="text-muted mb-0">نسبة الخصم: {{ $insurance->discount_percentage }}%</p>
                        <p class="text-muted mb-0">نسبة التحمل: {{ $insurance->Company_rate }}%</p>
                    </div>

                    <div class="custom-control custom-checkbox my-4 text-center">
                        <input type="checkbox" class="custom-control-input" id="confirmInsuranceCheck{{ $insurance->id }}" required>
                        <label class="custom-control-label text-danger" for="confirmInsuranceCheck{{ $insurance->id }}">
                            أؤكد أنني أريد حذف هذا السجل ولا يمكن التراجع
                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i> {{ trans('insurance.close') }}
                        </button>
                        <button type="submit" id="deleteInsuranceBtn{{ $insurance->id }}" class="btn btn-danger bg-gradient-danger">
                            <i class="fas fa-trash-alt mr-2"></i> {{ trans('insurance.save') }}
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
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('Dashboard/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>


    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/neomorphic-ui/neomorphic.js') }}"></script>
    @include('Script.Script')
@endsection
