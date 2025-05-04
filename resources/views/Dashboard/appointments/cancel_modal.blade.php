{{-- resources/views/Dashboard/appointments/cancel_modal.blade.php --}}
<div class="modal fade" id="cancelModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $appointment->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="cancelModalLabel{{ $appointment->id }}">
                    <i class="fas fa-exclamation-triangle"></i> تأكيد إلغاء الموعد
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('admin.admin_cancel', $appointment->id) }}" method="post"> {{-- استخدام اسم المسار الجديد --}}
                @method('PATCH') {{-- استخدام PATCH لتحديث الحالة --}}
                @csrf
                <div class="modal-body">
                    <p>هل أنت متأكد من رغبتك في إلغاء هذا الموعد للمريض:</p>
                    <p><strong>{{ $appointment->name }}</strong></p>
                    <p>مع الطبيب: <strong>{{ $appointment->doctor->name ?? '-' }}</strong></p>
                    <p>في تاريخ: <strong dir="ltr">{{ $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : '-' }}</strong></p>
                    <hr>
                    <p class="text-danger small">ملاحظة: سيتم إرسال إشعار إلغاء للمريض والطبيب.</p>
                    {{-- (اختياري) إضافة حقل لسبب الإلغاء --}}
                    {{-- <div class="form-group mt-3">
                        <label for="cancel_reason_{{ $appointment->id }}">سبب الإلغاء (اختياري):</label>
                        <textarea class="form-control form-control-sm" id="cancel_reason_{{ $appointment->id }}" name="cancel_reason" rows="2"></textarea>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">تراجع</button>
                    <button type="submit" class="btn btn-danger">نعم، إلغاء الموعد</button>
                </div>
            </form>
        </div>
    </div>
</div>
