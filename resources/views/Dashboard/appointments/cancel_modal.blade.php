{{-- resources/views/Dashboard/appointments/cancel_modal.blade.php --}}
<div class="modal fade" id="cancelModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $appointment->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="cancelModalLabel{{ $appointment->id }}">
                    <i class="fas fa-exclamation-triangle"></i> {{ trans('AdminSb.confirm_appointment_cancellation_title') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('admin.admin_cancel', $appointment->id) }}" method="post">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <p>{{ trans('AdminSb.are_you_sure_cancel_appointment') }}</p>
                    <p><strong>{{ $appointment->name }}</strong></p>
                    <p>{{ trans('AdminSb.with_doctor_colon') }} <strong>{{ $appointment->doctor->name ?? '-' }}</strong></p>
                    <p>{{ trans('AdminSb.on_date_colon') }} <strong dir="ltr">{{ $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : '-' }}</strong></p>
                    <hr>
                    <p class="text-danger small">{{ trans('AdminSb.cancellation_notification_note') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('AdminSb.go_back') }}</button>
                    <button type="submit" class="btn btn-danger">{{ trans('AdminSb.yes_cancel_appointment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
