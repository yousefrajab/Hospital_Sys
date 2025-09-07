<!-- Modal -->
<div wire:ignore.self class="modal fade" id="delete_invoice" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('AdminSb.delete_invoice_data')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php echo e(trans('AdminSb.are_you_sure_about_deleting')); ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal"><?php echo e(trans('AdminSb.close')); ?></button>
                <button type="button" wire:click.prevent="destroy()"
                    class="btn btn-danger"><?php echo e(trans('AdminSb.delete')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH H:\Hospital_Sys\resources\views/livewire/single_invoices/delete.blade.php ENDPATH**/ ?>