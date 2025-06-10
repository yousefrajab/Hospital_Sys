<div>
    <div class="container-fluid users-list-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="fas fa-user-friends me-2"></i>اختر مريضًا لبدء محادثة</h4>
            <input type="text" wire:model.debounce.300ms="searchPatients" class="form-control form-control-sm w-25" placeholder="بحث عن مريض بالاسم أو الإيميل...">
        </div>

        @if($users && $users->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach ($users as $patient) {{-- المتغير هنا هو $users الذي يمثل المرضى --}}
                    <div class="col">
                        <div class="card user-card-item h-100 shadow-hover" wire:click="createConversation('{{ $patient->email }}')" title="بدء محادثة مع {{ $patient->name }}">
                            <div class="card-body text-center">
                                <img src="{{ $patient->image ? asset('Dashboard/img/patients/' . $patient->image->filename) : asset('Dashboard/img/default_patient_avatar.png') }}"
                                     alt="صورة {{ $patient->name }}" class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover; border: 3px solid #e9ecef;">
                                <h6 class="card-title mb-1 fw-bold">{{ $patient->name }}</h6>
                                <p class="card-text text-muted small mb-2">{{ $patient->email }}</p>
                                {{-- يمكنك إضافة معلومات أخرى مثل آخر زيارة أو رقم الملف --}}
                                <button class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-comments me-1"></i> بدء محادثة
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- Pagination إذا كنت تستخدمه --}}
            {{-- <div class="mt-4"> {{ $users->links() }} </div> --}}
        @else
            <div class="text-center text-muted p-5">
                <i class="fas fa-users-slash fa-3x mb-3"></i>
                <p>لا يوجد مرضى مسجلون حاليًا لبدء محادثة معهم.</p>
            </div>
        @endif
        <div wire:loading wire:target="searchPatients" class="text-center p-3 text-muted">
            <i class="fas fa-spinner fa-spin me-2"></i>جاري البحث...
        </div>
    </div>
</div>

@push('css')
<style>
.shadow-hover:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.1)!important; transform: translateY(-2px); }
.users-list-container .card-title { font-size: 1rem; }
</style>
@endpush

{{-- لا حاجة لـ JavaScript معقد هنا إذا كان wire:click يعالج كل شيء --}}
