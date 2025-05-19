@if(isset($medication_for_modal) && $medication_for_modal instanceof \App\Models\Medication)
<div class="modal fade" id="deleteMedicationModal{{ $medication_for_modal->id }}" tabindex="-1" aria-labelledby="deleteMedicationModalLabel{{ $medication_for_modal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white"> {{-- استخدام لون الخطر --}}
                <h5 class="modal-title" id="deleteMedicationModalLabel{{ $medication_for_modal->id }}">
                    <i class="fas fa-exclamation-triangle me-2"></i> تأكيد حذف الدواء
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <form action="{{ route('pharmacy_manager.medications.destroy', $medication_for_modal->id) }}" method="post" id="confirmDeleteMedicationForm{{ $medication_for_modal->id }}">
                    @csrf
                    @method('DELETE')
                    <div style="font-size: 3rem; color: var(--admin-danger, #ef4444); margin-bottom: 1rem;">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h4>هل أنت متأكد من حذف الدواء التالي نهائيًا؟</h4>
                    <p class="text-muted">
                        الدواء: <strong>{{ $medication_for_modal->name }}</strong>
                        @if($medication_for_modal->generic_name)
                            <br><small>(الاسم العلمي: {{ $medication_for_modal->generic_name }})</small>
                        @endif
                    </p>
                    <p class="text-danger small mt-2">
                        <i class="fas fa-info-circle"></i> هذا الإجراء لا يمكن التراجع عنه.
                        <br>سيتم التحقق مما إذا كان الدواء مستخدمًا قبل الحذف.
                    </p>
                </form>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width: 100px;">
                    <i class="fas fa-times me-1"></i> إلغاء
                </button>
                <button type="submit" form="confirmDeleteMedicationForm{{ $medication_for_modal->id }}" class="btn btn-danger" style="min-width: 100px;">
                    <i class="fas fa-trash-alt me-1"></i> نعم، احذف
                </button>
            </div>
        </div>
    </div>
</div>
@endif
