<div class="services-container">
    <!-- Header with Add Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title">{{ trans('Services.Group Services') }}</h4>
        <button class="btn btn-primary-gradient btn-add" wire:click="show_form_add" type="button">
            <i class="fas fa-layer-group mr-2"></i>{{ trans('Services.Add Group Services') }}
        </button>
    </div>

    <!-- Services Table -->
    <div class="card custom-card">
        <div class="card-body p-0">
            <div class="table-responsive" style="overflow: visible;">
                <table class="table text-center border-0 rounded-3 shadow table-hover" id="example1"
                    style="transition: all 0.3s ease-in-out;">
                    <thead class="bg-gradient-primary text-white">
                        <tr>
                            <th class="text-center" style="width: 60px">#</th>
                            <th>{{ trans('Services.name') }}</th>
                            <th class="text-center">{{ trans('Services.Total Amount Including Tax') }}</th>
                            <th>{{ trans('Services.description') }}</th>
                            <th class="text-center" style="width: 150px">{{ trans('Services.Operations') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groups as $group)
                            <tr class="group-row" wire:key="group-{{ $group->id }}">
                                <td class="text-center">
                                    <span class="badge badge-pill badge-light">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="group-icon mr-3">
                                            <i class="fas fa-folder-open text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 group-name">{{ $group->name }}</h6>
                                            <small class="text-muted">ID: {{ $group->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="price-badge">
                                        {{ number_format($group->Total_with_tax, 2) }}
                                        <small>(شيكل)</small>
                                    </span>
                                </td>
                                <td>
                                    <div class="description-tooltip" data-toggle="tooltip" data-placement="top"
                                        title="{{ $group->notes }}">
                                        {{ Str::limit($group->notes, 50) }}
                                        @if (strlen($group->notes) > 50)
                                            <small class="text-primary">...المزيد</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="action-btns">
                                        <button wire:click="edit({{ $group->id }})" class="btn btn-primary btn-sm"
                                            onclick="event.stopPropagation()">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deleteGroup{{ $group->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
            </div>

            </tr>
            @include('livewire.GroupServices.delete')
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- Modern CSS Styling -->
<style>
    /* Main Container */
    .services-container {
        background-color: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    /* Section Title */
    .section-title {
        color: #2c3e50;
        font-weight: 600;
        position: relative;
        padding-left: 15px;
    }

    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 5px;
        height: 20px;
        width: 4px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
        border-radius: 4px;
    }

    /* Add Button */
    .btn-add {
        border: none;
        border-radius: 30px;
        padding: 10px 20px;
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    /* Card Styling */
    .custom-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
    }

    /* Table Header */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .table-advanced thead th {
        border: none;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 15px 10px;
        text-align: center;
    }

    /* Table Body */
    .table-advanced tbody tr {
        transition: all 0.3s ease;
    }

    .table-advanced tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transform: translateY(-1px);
    }

    /* Group Icon */
    .group-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(102, 126, 234, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .group-name {
        font-weight: 600;
        color: #2c3e50;
    }

    /* Price Badge */
    .price-badge {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    /* Action Buttons */
    /* .action-btns {
        display: flex;
        justify-content: center;
        gap: 8px;
    } */


    .action-btns {
        display: flex;
        justify-content: center;
        gap: 8px;
        /* إزالة هذه الخاصية إذا كانت موجودة */
        pointer-events: auto !important;
    }

    .action-btns button {
        pointer-events: auto !important;
    }

    /* .action-btns button {
        pointer-events: auto;
        /* هذا الإصلاح يسمح للأزرار بالاستجابة للأحداث */
    }

    */ .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 0.3s ease;
        border: none;
        background-color: transparent;
    }

    .btn-icon:hover {
        transform: scale(1.1);
    }

    .btn-edit {
        background-color: rgba(23, 162, 184, 0.1);
    }

    .btn-delete {
        background-color: rgba(220, 53, 69, 0.1);
    }

    .btn-view {
        background-color: rgba(0, 123, 255, 0.1);
    }

    /* Tooltip for Description */
    .description-tooltip {
        cursor: pointer;
        transition: all 0.2s;
    }

    .description-tooltip:hover {
        color: #667eea;
    }

    /* Badge Styling */
    .badge-pill {
        font-weight: 500;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .services-container {
            padding: 15px;
        }

        .action-btns {
            flex-direction: column;
            gap: 5px;
        }

        .btn-icon {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }

        .group-icon {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .group-name {
            font-size: 14px;
        }
    }
</style>

@include('Style.Style')

<!-- JavaScript Enhancements -->
<script>
    // document.querySelectorAll('.action-btns button[wire\\:click]').forEach(btn => {
    //     btn.addEventListener('click', function(e) {
    //         console.log('Button clicked - Event reached');
    //         e.stopImmediatePropagation();
    //     });
    // });



    document.addEventListener('livewire:load', function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover',
            animation: true
        });




        // Initialize DataTable
        $('#group-services-table').DataTable({
            dom: '<"top"lf>rt<"bottom"ip>',
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
            },
            responsive: true,
            columnDefs: [{
                orderable: false,
                targets: [4]
            }],
            initComplete: function() {
                $('.dataTables_filter input').attr('placeholder', 'ابحث هنا...');
            }
        });

        // Row hover effect
        $('.group-row').hover(
            function() {
                $(this).css('box-shadow', '0 4px 12px rgba(0,0,0,0.08)');
            },
            function() {
                $(this).css('box-shadow', 'none');
            }
        );

        // Livewire event listeners
        Livewire.on('groupAdded', () => {
            $('#group-services-table').DataTable().ajax.reload();
            toastr.success('تمت إضافة المجموعة بنجاح');
        });

        Livewire.on('groupUpdated', () => {
            $('#group-services-table').DataTable().ajax.reload();
            toastr.success('تم تحديث المجموعة بنجاح');
        });

        Livewire.on('groupDeleted', () => {
            $('#group-services-table').DataTable().ajax.reload();
            toastr.success('تم حذف المجموعة بنجاح');
        });
    });
</script>
@include('Script.Script')
