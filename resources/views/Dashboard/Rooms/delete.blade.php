<div class="modal fade" id="deleteRoomConfirmModal{{ $room->id }}" tabindex="-1"
    aria-labelledby="deleteRoomModalLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white"
                style="background: linear-gradient(135deg, var(--admin-danger, #ef4444), var(--admin-danger-dark, #c82333)); border-bottom: none;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i> {{-- استخدام me-3 لـ RTL --}}
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deleteRoomModalLabel{{ $room->id }}">
                            تأكيد حذف الغرفة
                        </h5>
                        <p class="mb-0 small">يرجى التأكد قبل المتابعة.</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button> {{-- زر إغلاق Bootstrap 5 --}}
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                {{-- الفورم الفعلي للحذف يتم إرساله من هنا --}}
                <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="post"
                    id="confirmDeleteRoomForm{{ $room->id }}">
                    @csrf
                    @method('DELETE')
                    {{-- لا نحتاج لـ input hidden لـ id لأن الـ ID موجود في الـ route action --}}

                    <div class="text-center mb-4">
                        <div style="font-size: 3rem; color: var(--admin-danger, #ef4444); margin-bottom: 1rem;">
                            <i class="fas fa-door-closed"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold mt-3">تحذير!</h4>
                        <p class="text-muted">أنت على وشك حذف الغرفة بشكل نهائي.</p>
                    </div>

                    <div class="alert alert-light border-danger border text-center py-3"
                        style="background-color: rgba(239,68,68,0.05);">
                        <h5 class="font-weight-bold mb-1">الغرفة: {{ $room->room_number }}</h5>
                        @if ($room->section)
                            <p class="text-muted small mb-0">القسم: {{ $room->section->name }}</p>
                        @endif
                        <p class="text-muted small mb-0">معرف الغرفة: #{{ $room->id }}</p>
                    </div>

                    <p class="text-center text-muted small my-3">
                        هذا الإجراء لا يمكن التراجع عنه. سيتم أيضًا حذف أي أسرة مرتبطة بهذه الغرفة إذا كانت فارغة
                    </p>

                    {{-- (اختياري) يمكنك إضافة checkbox تأكيد هنا إذا أردت، لكن المودال نفسه يعتبر تأكيدًا --}}
                    {{-- <div class="custom-control custom-checkbox my-4 text-center">
                        <input type="checkbox" class="custom-control-input" id="confirmDeleteCheckboxRoom{{ $room->id }}" required>
                        <label class="custom-control-label text-danger" for="confirmDeleteCheckboxRoom{{ $section->id }}">
                            أوافق على أن هذا الإجراء لا يمكن التراجع عنه
                        </label>
                    </div> --}}

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0 d-flex justify-content-center">
                        <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i> {{ trans('sections_trans.Close') }}</button>
                        <button type="submit" id="confirmDeleteBtnRoom{{ $room->id }}" class="btn btn-delete-form"
                            style="min-width: 120px;">
                            <i class="fas fa-trash-alt me-2"></i> نعم، قم بالحذف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
