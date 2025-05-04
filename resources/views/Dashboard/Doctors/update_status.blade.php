<!-- Clean Professional Modal -->
<div class="modal fade" id="update_status{{ $doctor->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark font-weight-bold" id="statusModalLabel">
                    <i class="fas fa-sync-alt mr-2 text-primary"></i>
                    {{ trans('doctors.Status_change') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-dark">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.update_status') }}" method="post" autocomplete="off">
                @csrf
                <div class="modal-body py-4">
                    <div class="form-group mb-4">
                        <label for="status" class="font-weight-bold text-dark mb-2">{{ trans('doctors.Status') }}</label>
                        <select class="form-control border border-light rounded" id="status" name="status" required>
                            <option value="" selected disabled>-- {{ trans('doctors.Choose') }} --</option>
                            <option value="1" class="text-success">{{ trans('doctors.Enabled') }}</option>
                            <option value="0" class="text-danger">{{ trans('doctors.Not_enabled') }}</option>
                        </select>
                    </div>

                    <input type="hidden" name="id" value="{{ $doctor->id }}">
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>
                        {{ trans('Dashboard/sections_trans.Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-check mr-2"></i>
                        {{ trans('Dashboard/sections_trans.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modal-content {
    border-radius: 12px !important;
}
.form-control {
    padding: 12px 15px;
    transition: border-color 0.3s;
}
.form-control:focus {
    border-color: #4d90fe;
    box-shadow: 0 0 0 2px rgba(77, 144, 254, 0.2);
}
.btn {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s;
}
</style>
<style>
    /* Modern Styling */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
    }

    .wave-bg {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="rgba(255,255,255,0.1)"/></svg>');
        background-size: cover;
        opacity: 0.5;
    }



    .status-indicator {
        position: absolute;
        bottom: 0;
        right: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .status-toggle-container {
        position: relative;
        display: flex;
        background: #f8f9fa;
        border-radius: 50px;
        padding: 5px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .status-radio {
        display: none;
    }

    .status-label {
        flex: 1;
        text-align: center;
        padding: 10px 15px;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1;
        font-weight: 500;
    }

    .status-enabled {
        color: #28a745;
    }

    .status-disabled {
        color: #dc3545;
    }

    .status-slider {
        position: absolute;
        top: 5px;
        bottom: 5px;
        width: calc(50% - 10px);
        background: white;
        border-radius: 50px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        z-index: 0;
    }



    .submit-btn {
        position: relative;
        overflow: hidden;
    }

    .submit-btn .submit-text {
        position: relative;
        z-index: 1;
        transition: all 0.3s;
    }

    .submit-btn:hover .submit-text {
        transform: translateY(-2px);
    }
</style>
