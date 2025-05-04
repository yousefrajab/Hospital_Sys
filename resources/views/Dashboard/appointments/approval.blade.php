{{-- resources/views/Dashboard/appointments/approval.blade.php --}}
<!-- Approval Appointment Modal -->
<div class="modal fade" id="approval{{ $appointment->id }}" tabindex="-1" aria-labelledby="approvalModalLabel{{ $appointment->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                {{-- تعديل العنوان --}}
                <h5 class="modal-title" id="approvalModalLabel{{ $appointment->id }}">تأكيد الموعد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            {{-- تغيير الفورم ليكون أبسط --}}
            <form action="{{ route('admin.appointments.approval', $appointment->id) }}" method="post">
                @method('PUT') {{-- استخدام PUT مناسب لتحديث الحالة --}}
                @csrf
                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}"> {{-- إرسال ID الموعد --}}

                <div class="modal-body">
                    {{-- رسالة تأكيد واضحة --}}
                    <p>هل أنت متأكد من تأكيد الموعد للمريض:</p>
                    <p><strong>{{ $appointment->name }}</strong></p>
                    <p>مع الطبيب: <strong>{{ $appointment->doctor->name ?? 'غير محدد' }}</strong></p>
                    <p>في تاريخ ووقت:</p>
                    <p><strong dir="ltr">{{ $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : 'غير محدد' }}</strong></p>
                    {{-- *** تمت إزالة حقل اختيار الوقت من هنا *** --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    {{-- تغيير نص زر الحفظ --}}
                    <button type="submit" class="btn btn-success">نعم، تأكيد الموعد</button>
                </div>
            </form>
        </div>
    </div>
 </div>
