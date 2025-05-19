{{-- resources/views/Dashboard/PharmacyManager/Stocks/delete_confirm_modal.blade.php --}}
{{-- يتوقع متغير $stock_for_modal --}}

@if(isset($stock_for_modal) && $stock_for_modal instanceof \App\Models\PharmacyStock)
<div class="modal fade" id="deleteStockModal{{ $stock_for_modal->id }}" tabindex="-1" aria-labelledby="deleteStockModalLabel{{ $stock_for_modal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteStockModalLabel{{ $stock_for_modal->id }}">
                    <i class="fas fa-exclamation-triangle me-2"></i> تأكيد حذف دفعة المخزون
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <form action="{{ route('pharmacy_manager.stocks.destroy', $stock_for_modal->id) }}" method="post" id="confirmDeleteStockForm{{ $stock_for_modal->id }}">
                    @csrf
                    @method('DELETE')
                    <div style="font-size: 3rem; color: var(--admin-danger, #ef4444); margin-bottom: 1rem;">
                        <i class="fas fa-archive"></i>
                    </div>
                    <h4>هل أنت متأكد من حذف دفعة المخزون التالية نهائيًا؟</h4>
                    <p class="text-muted">
                        للدواء: <strong>{{ $stock_for_modal->medication->name ?? 'غير معروف' }}</strong>
                        <br><small>رقم الدفعة: {{ $stock_for_modal->batch_number ?: 'لا يوجد' }} - الكمية الحالية: {{ $stock_for_modal->quantity_on_hand }}</small>
                    </p>
                    <p class="text-danger small mt-2">
                        <i class="fas fa-info-circle"></i> هذا الإجراء لا يمكن التراجع عنه.
                        @if($stock_for_modal->quantity_on_hand > 0)
                            <br><strong>تحذير: هذه الدفعة لا تزال تحتوي على كمية!</strong>
                        @endif
                    </p>
                </form>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width: 100px;">
                    <i class="fas fa-times me-1"></i> إلغاء
                </button>
                <button type="submit" form="confirmDeleteStockForm{{ $stock_for_modal->id }}" class="btn btn-danger" style="min-width: 100px;">
                    <i class="fas fa-trash-alt me-1"></i> نعم، احذف
                </button>
            </div>
        </div>
    </div>
</div>
@endif
