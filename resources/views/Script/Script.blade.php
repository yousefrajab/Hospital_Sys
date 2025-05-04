

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> --}}

    <!--Internal Notify js -->
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/neomorphic-ui/neomorphic.js') }}"></script>

    <!-- Internal Data tables -->
    <script src="{{ URL::asset('Dashboard/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>



<script>
    // index /Single Service
    $(document).ready(function() {
        $('#services-table').DataTable({
            responsive: true,
            language: {
                url: "{{ URL::asset('Dashboard/plugins/datatable/i18n/Arabic.json') }}"
            },
            dom: '<"top mb-3"f>rt<"bottom mt-2"lip><"clear">'
        });

        $('[data-toggle="modal"]').on('click', function() {
            var target = $(this).attr('href');
            $('.modal').modal('hide');
            setTimeout(function() {
                $(target).modal('show');
            }, 200);
        });

        $('.modal').on('shown.bs.modal', function() {
            $('body').addClass('modal-open');
        }).on('hidden.bs.modal', function() {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    });
</script>
{{-- -------------------------------------------------Index/Sections ---------------------- --}}
<script>
    $(document).ready(function() {
        $('#sections-table').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
            },
            responsive: true,
            initComplete: function() {
                $('.dataTables_filter input').attr('placeholder', 'ابحث في الأقسام...');
            }
        });
    });
</script>
{{-- --------------------------------------------------------edit Doctor-------- --}}
<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src);
        }
    };
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "اختر",
            width: '100%'
        });

        // تأثيرات عند التركيز على الحقول
        $('.form-control-modern').focus(function() {
            $(this).parent().find('label').css('color', '#8B5CF6');
        }).blur(function() {
            $(this).parent().find('label').css('color', '#6366F1');
        });
    });
</script>
<script>
    function togglePassword() {
        const input = document.getElementById('password-input');
        const icon = document.getElementById('password-icon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
{{-- ------------------------------------------Show Doctors------------------------------------------------ --}}


<script>
    $(document).ready(function() {
        $('table tbody tr').each(function(i) {
            $(this).delay(i * 100).animate({
                opacity: 1,
                left: 0
            }, 200);
        });

        $('.delete-btn').click(function(e) {
            if (!confirm('هل أنت متأكد من حذف هذا الطبيب؟')) {
                e.preventDefault();
            }
        });
    });

    document.querySelectorAll('.doctor-avatar').forEach(img => {
        img.addEventListener('error', function() {
            this.src = '{{ asset('Dashboard/img/doctor_default.png') }}';
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // حساب نسبة التعبئة بناء على عدد الكشوفات
        document.querySelectorAll('.statements-count').forEach(item => {
            const count = parseInt(item.dataset.count);
            const maxCount = 20; // الحد الأقصى للكشوفات (يمكن تعديله)
            const percentage = Math.min(count / maxCount * 100, 100);

            const circleFill = item.querySelector('.count-circle-fill');
            circleFill.style.setProperty('--percentage', percentage);
            circleFill.style.animation = 'circle-fill 1.5s ease-out forwards';

            // إضافة تأثير عند المرور إذا كان العدد كبيراً
            if (count > maxCount * 0.8) {
                item.classList.add('high-count');
                item.querySelector('.count-number').style.fontWeight = '700';
                item.querySelector('.count-circle-fill').style.stroke = '#dc3545';
            }
        });
    });
</script>
{{-- ------------------------------------------Add Doctor------------------------------------------- --}}
<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src);
        }
    };
</script>


<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "اختر",
            width: '100%'
        });

        // تأثيرات عند التركيز على الحقول
        $('.form-control-modern').focus(function() {
            $(this).parent().find('label').css('color', '#8B5CF6');
        }).blur(function() {
            $(this).parent().find('label').css('color', '#6366F1');
        });
    });
</script>

<script>
    function togglePassword() {
        const input = document.getElementById('password-input');
        const icon = document.getElementById('password-icon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

{{-- ----------------------------------------------------index doctors------------------------------- --}}
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#doctors-table').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
            },
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            initComplete: function() {
                $('.dataTables_filter input').attr('placeholder', 'ابحث باسم الطبيب');
            }
        });

        // Select all functionality
        $("[name=select_all]").click(function(source) {
            checkboxes = $("[name=delete_select]");
            for (var i in checkboxes) {
                checkboxes[i].checked = source.target.checked;
            }
        });

        // Delete selected functionality
        $("#btn_delete_all").click(function() {
            var selected = [];
            $("#doctors-table input[name=delete_select]:checked").each(function() {
                selected.push(this.value);
            });

            if (selected.length > 0) {
                $('#delete_select').modal('show')
                $('input[id="delete_select_id"]').val(selected);
            } else {
                notif({
                    msg: "{{ trans('Dashboard/messages.delete_select') }}",
                    type: "alert-danger",
                    position: "right"
                });
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // حساب نسبة التعبئة بناء على عدد الكشوفات
        document.querySelectorAll('.statements-count').forEach(item => {
            const count = parseInt(item.dataset.count);
            const maxCount = 20; // الحد الأقصى للكشوفات (يمكن تعديله)
            const percentage = Math.min(count / maxCount * 100, 100);

            const circleFill = item.querySelector('.count-circle-fill');
            circleFill.style.setProperty('--percentage', percentage);
            circleFill.style.animation = 'circle-fill 1.5s ease-out forwards';

            // إضافة تأثير عند المرور إذا كان العدد كبيراً
            if (count > maxCount * 0.8) {
                item.classList.add('high-count');
                item.querySelector('.count-number').style.fontWeight = '700';
                item.querySelector('.count-circle-fill').style.stroke = '#dc3545';
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // إضافة تأثيرات إضافية عند النقر
        document.querySelectorAll('.innovative-checkbox input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    const label = this.nextElementSibling;
                    const icon = label.querySelector('.check-icon');

                    // تأثير اهتزاز بسيط
                    icon.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1)';
                    }, 300);
                }
            });
        });

        // تأثيرات عند التركيز (لتحسين إمكانية الوصول)
        document.querySelectorAll('.innovative-checkbox input').forEach(checkbox => {
            checkbox.addEventListener('focus', function() {
                this.nextElementSibling.style.boxShadow = '0 0 0 3px rgba(67, 97, 238, 0.2)';
            });

            checkbox.addEventListener('blur', function() {
                this.nextElementSibling.style.boxShadow = 'none';
            });
        });
    });
</script>

{{-- -----------------------------------------------Update_password Doctor----------------------------- --}}
<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
