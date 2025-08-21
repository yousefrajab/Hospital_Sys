{{-- resources/views/livewire/chat/createchat.blade.php --}}
<div>
    <div class="container-fluid users-list-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="fas fa-user-md me-2"></i>{{ trans('doctors.chat_select_doctor_title') }}</h4>
            <input type="text" wire:model.debounce.300ms="searchDoctors" class="form-control form-control-sm w-25" placeholder="{{ trans('doctors.chat_search_doctor_placeholder') }}">
        </div>

        @if($users && $users->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach ($users as $doctor) {{-- المتغير هنا هو $users الذي يمثل الأطباء --}}
                    <div class="col">
                        <div class="card user-card-item h-100 shadow-hover" wire:click="createConversation('{{ $doctor->email }}')" title="{{ trans('doctors.chat_start_conversation_with_dr', ['name' => $doctor->name]) }}">
                            <div class="card-body text-center">
                                <img src="{{ $doctor->image ? asset('Dashboard/img/doctors/' . $doctor->image->filename) : asset('Dashboard/img/faces/doctor_default.png') }}"
                                     alt="{{ trans('doctors.chat_doctor_image_alt', ['name' => $doctor->name]) }}" class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover; border: 3px solid #e9ecef;">
                                <h6 class="card-title mb-1 fw-bold">{{ trans('doctors.doctor_prefix_name', ['name' => $doctor->name]) }}</h6>
                                <p class="card-text text-muted small mb-1">{{ $doctor->section->name ?? trans('doctors.general_specialty') }}</p>
                                <p class="card-text text-muted small mb-2">{{ $doctor->email }}</p>
                                <button class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-comments me-1"></i> {{ trans('doctors.chat_start_conversation_button') }}
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
                <p>{{ trans('doctors.chat_no_doctors_found') }}</p>
            </div>
        @endif
         <div wire:loading wire:target="searchDoctors" class="text-center p-3 text-muted">
            <i class="fas fa-spinner fa-spin me-2"></i>{{ trans('doctors.chat_searching') }}
        </div>
    </div>
</div>
@push('css')
<style>
.shadow-hover:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.1)!important; transform: translateY(-2px); }
.users-list-container .card-title { font-size: 1rem; }
</style>
@endpush
