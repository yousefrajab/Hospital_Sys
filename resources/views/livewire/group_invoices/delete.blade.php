<!-- Modal -->
<div wire:ignore.self class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">حذف بيانات الفاتورة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
               هل انت متأكد من عملية الحذف؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                {{-- زر الحذف داخل المودال يستدعي destroy --}}
                <button type="button" wire:click.prevent="destroy()" class="btn btn-danger">
                    {{-- (اختياري) إضافة مؤشر تحميل --}}
                    <span wire:loading.remove wire:target="destroy">حذف</span>
                    <span wire:loading wire:target="destroy">جاري الحذف... <i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>
        </div>
    </div>
</div>
