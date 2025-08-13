{{-- resources/views/Dashboard/appointments/approval.blade.php --}}
<!-- Approval Appointment Modal -->
<div class="modal fade" id="approval{{ $appointment->id }}" tabindex="-1" aria-labelledby="approvalModalLabel{{ $appointment->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalLabel{{ $appointment->id }}">{{ trans('AdminSb.confirm_appointment_title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('admin.appointments.approval', $appointment->id) }}" method="post">
                @method('PUT')
                @csrf
                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                <div class="modal-body">
                    <p>{{ trans('AdminSb.are_you_sure_confirm_appointment') }}</p>
                    <p><strong>{{ $appointment->name }}</strong></p>
                    <p>{{ trans('AdminSb.with_doctor_colon') }} <strong>{{ $appointment->doctor->name ?? trans('AdminSb.unspecified') }}</strong></p>
                    <p>{{ trans('AdminSb.at_datetime_colon') }}</p>
                    <p><strong dir="ltr">{{ $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : trans('AdminSb.unspecified') }}</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('AdminSb.cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ trans('AdminSb.yes_confirm_appointment') }}</button>
                </div>
            </form>
        </div>
    </div>
 </div>
