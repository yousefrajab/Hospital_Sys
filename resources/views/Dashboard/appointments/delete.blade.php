<div class="modal fade" id="Deleted{{$appointment->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('AdminSb.confirm_permanent_deletion') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.appointments.destroy',$appointment->id)}}" method="post">
                    @method('DELETE')
                    @csrf
                    <div class="row">
                        <div class="col">
                            <p class="h5 text-danger">{{ trans('AdminSb.are_you_sure_delete_permanently') }}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('AdminSb.cancel') }}</button>
                        <button class="btn btn-success">{{ trans('AdminSb.yes_delete_permanently') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
