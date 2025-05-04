<!-- Enhanced Employee Edit Modal -->
<div class="modal fade" id="edit{{ $ray_employee->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editEmployeeLabel{{ $ray_employee->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <!-- Modal Header with Gradient Background -->
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="editEmployeeLabel{{ $ray_employee->id }}">
                    <i class="fas fa-user-edit mr-2"></i>تعديل بيانات موظف الأشعة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.ray_employee.update', $ray_employee->id) }}" method="post" autocomplete="off">
                @method('patch')
                @csrf

                <div class="modal-body py-4">
                    <div class="row">
                        <!-- Personal Information Section -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-primary">الاسم الكامل</label>
                                <input type="text" name="name" value="{{ $ray_employee->name }}"
                                    class="form-control rounded-lg shadow-sm" placeholder="أدخل الاسم الكامل" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-primary">رقم الهوية</label>
                                <input type="text" name="national_id" value="{{ $ray_employee->national_id }}"
                                    class="form-control rounded-lg shadow-sm" placeholder="أدخل رقم الهوية"
                                    pattern="[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    maxlength="9" required>
                            </div>
                        </div>





                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-primary">البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ $ray_employee->email }}"
                                    class="form-control rounded-lg shadow-sm" placeholder="example@domain.com" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="font-weight-bold text-primary">كلمة المرور الجديدة</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control rounded-lg shadow-sm"
                                        placeholder="اتركه فارغاً إذا لم ترد التغيير" autocomplete="new-password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">يجب أن تحتوي على 8 أحرف على الأقل</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>{{ trans('Dashboard/sections_trans.Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save mr-2"></i>{{ trans('Dashboard/sections_trans.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Additional Styles for Consistency */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee, #4e54c8);
    }

    .toggle-password {
        border-top-right-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
        border-left: 0;
    }

    .toggle-password:hover {
        background-color: #f8f9fa;
    }

    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // التعامل مع تغيير نوع حقل الإدخال
        $('.toggle-password').click(function() {
            const input = $(this).siblings('input');
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                // تغيير النوع إلى نص لإظهار كلمة المرور
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                // إعادة النوع إلى كلمة مرور لإخفائها
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>
