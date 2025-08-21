{{-- resources/views/Dashboard/PharmacyManager/Stocks/delete_confirm_modal.blade.php --}}
{{-- يتوقع متغير $stock_for_modal --}}

@if(isset($stock_for_modal) && $stock_for_modal instanceof \App\Models\PharmacyStock)
<div class="modal fade" id="deleteStockModal{{ $stock_for_modal->id }}" tabindex="-1" aria-labelledby="deleteStockModalLabel{{ $stock_for_modal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteStockModalLabel{{ $stock_for_modal->id }}">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ trans('phrM.confirm_stock_batch_deletion_title') }}
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
                    <h4>{{ trans('phrM.are_you_sure_delete_batch_permanently') }}</h4>
                    <p class="text-muted">
                        {{ trans('phrM.for_medication') }} <strong>{{ $stock_for_modal->medication->name ?? trans('phrM.unknown_medication') }}</strong>
                        <br><small>{{ trans('phrM.batch_number_col') }}: {{ $stock_for_modal->batch_number ?: trans('phrM.no_batch_number_available') }} - {{ trans('phrM.current_quantity_col') }}: {{ $stock_for_modal->quantity_on_hand }}</small>
                    </p>
                    <p class="text-danger small mt-2">
                        <i class="fas fa-info-circle"></i> {{ trans('phrM.action_cannot_be_undone') }}
                        @if($stock_for_modal->quantity_on_hand > 0)
                            <br><strong>{{ trans('phrM.warning_batch_has_quantity') }}</strong>
                        @endif
                    </p>
                </form>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width: 100px;">
                    <i class="fas fa-times me-1"></i> {{ trans('phrM.cancel') }}
                </button>
                <button type="submit" form="confirmDeleteStockForm{{ $stock_for_modal->id }}" class="btn btn-danger" style="min-width: 100px;">
                    <i class="fas fa-trash-alt me-1"></i> {{ trans('phrM.yes_delete_button') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endif
