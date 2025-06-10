{{-- resources/views/livewire/chat/createchat.blade.php --}}
<div>
    <div class="container-fluid users-list-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="fas fa-user-md me-2"></i>اختر طبيبًا لبدء محادثة</h4>
            <input type="text" wire:model.debounce.300ms="searchDoctors" class="form-control form-control-sm w-25" placeholder="بحث عن طبيب بالاسم أو التخصص...">
        </div>

        @if($users && $users->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach ($users as $doctor) {{-- المتغير هنا هو $users الذي يمثل الأطباء --}}
                    <div class="col">
                        <div class="card user-card-item h-100 shadow-hover" wire:click="createConversation('{{ $doctor->email }}')" title="بدء محادثة مع د. {{ $doctor->name }}">
                            <div class="card-body text-center">
                                <img src="{{ $doctor->image ? asset('Dashboard/img/doctors/' . $doctor->image->filename) : asset('Dashboard/img/faces/doctor_default.png') }}"
                                     alt="صورة د. {{ $doctor->name }}" class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover; border: 3px solid #e9ecef;">
                                <h6 class="card-title mb-1 fw-bold">د. {{ $doctor->name }}</h6>
                                <p class="card-text text-muted small mb-1">{{ $doctor->section->name ?? 'تخصص عام' }}</p>
                                <p class="card-text text-muted small mb-2">{{ $doctor->email }}</p>
                                <button class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-comments me-1"></i> بدء محادثة
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- {{ $users->links() }} --}}
        @else
            <div class="text-center text-muted p-5">
                <i class="fas fa-user-md fa-3x mb-3"></i>
                <p>لا يوجد أطباء متاحون حاليًا لبدء محادثة معهم.</p>
            </div>
        @endif
         <div wire:loading wire:target="searchDoctors" class="text-center p-3 text-muted">
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
