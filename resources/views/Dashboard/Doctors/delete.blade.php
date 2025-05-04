@section('css')
    @include('Style.Style')
@endsection
<!-- Delete Doctor Modal - Enhanced Version -->
<div class="modal fade" id="delete{{ $doctor->id }}" tabindex="-1" aria-labelledby="deleteDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-md fa-2x mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deleteDoctorModalLabel">{{ trans('doctors.delete_doctor') }}</h5>
                        <p class="mb-0 small">{{trans('sections_trans.Warning')}}</p>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                <form action="{{ route('admin.Doctors.destroy', 'test') }}" method="post" id="deleteDoctorForm{{ $doctor->id }}">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="page_id" value="1">
                    @if($doctor->image)
                        <input type="hidden" name="filename" value="{{$doctor->image->filename}}">
                    @endif
                    <input type="hidden" name="id" value="{{ $doctor->id }}">

                    <div class="text-center mb-4">
                        <div class="delete-icon-container">
                            <i class="fas fa-user-md text-danger"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold mt-3">{{trans('sections_trans.warning')}} !!</h4>
                        <p class="text-muted">{{trans('doctors.delete_doctor_warning')}} !!</p>
                    </div>

                    <div class="alert alert-light border-danger border text-center py-3">
                        <h5 class="font-weight-bold mb-1">{{ $doctor->name }}</h5>
                        <div class="d-flex justify-content-center align-items-center">
                            @if($doctor->image)
                                <img src="{{asset('Dashboard/img/doctors/'.$doctor->image->filename)}}"
                                     class="rounded-circle mr-2" width="40" height="40" alt="{{ $doctor->name }}">
                            @else
                                <img src="{{asset('Dashboard/img/doctor_default.png')}}"
                                     class="rounded-circle mr-2" width="40" height="40" alt="صورة افتراضية">
                            @endif
                            <div>
                                <p class="text-muted small mb-0">{{trans('doctors.Doctor_id')}}: #{{ $doctor->id }}</p>
                                <p class="text-muted small mb-0">{{trans('doctors.section')}}: {{ $doctor->section->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox my-4 text-center">
                        <input type="checkbox" class="custom-control-input" id="confirmDoctorCheck{{ $doctor->id }}" required>
                        <label class="custom-control-label text-danger" for="confirmDoctorCheck{{ $doctor->id }}">
                            {{ trans('sections_trans.I agree that this procedure cannot be undone') }}
                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i> {{trans('Dashboard/sections_trans.Close')}}
                        </button>
                        <button type="submit" id="deleteDoctorBtn{{ $doctor->id }}"
                                class="btn btn-danger bg-gradient-danger">
                            <i class="fas fa-trash-alt mr-2"></i> {{trans('Dashboard/sections_trans.submit')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@section('js')
    @include('Script.Script')
@endsection


