@extends('Dashboard.layouts.master')

@section('title')
    تعديل قراءة علامات حيوية
@endsection

@section('css')
    {{-- أي CSS إضافي --}}
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-edit fa-lg me-2"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">العلامات الحيوية</h4>
                    <span class="text-muted mt-0 tx-13">/ تعديل قراءة لـ {{ $vitalSign->patientAdmission->patient->name ?? 'مريض' }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
             <a href="{{ route('admin.patient_admissions.vital_signs_sheet', $vitalSign->patient_admission_id) }}" class="btn btn-outline-secondary btn-sm">
                 <i class="fas fa-arrow-left me-1"></i> العودة لورقة المراقبة
             </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">تعديل قراءة علامات حيوية (مسجلة في: {{ $vitalSign->recorded_at->format('Y-m-d H:i A') }})</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any()) {{--  يفضل استخدام error bag مخصص إذا كان هناك فورمات أخرى قد تفشل --}}
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.vital_signs.update', $vitalSign->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- نفس حقول نموذج الإضافة، ولكن مع ملء القيم الحالية --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vs_recorded_at" class="form-label">وقت التسجيل <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('recorded_at') is-invalid @enderror" id="vs_recorded_at" name="recorded_at" value="{{ old('recorded_at', $vitalSign->recorded_at->format('Y-m-d\TH:i')) }}" required>
                                @error('recorded_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vs_temperature" class="form-label">درجة الحرارة (°C)</label>
                                <input type="number" step="0.1" class="form-control @error('temperature') is-invalid @enderror" id="vs_temperature" name="temperature" value="{{ old('temperature', $vitalSign->temperature) }}">
                                @error('temperature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vs_systolic_bp" class="form-label">ضغط الدم الانقباضي (mmHg)</label>
                                <input type="number" class="form-control @error('systolic_bp') is-invalid @enderror" id="vs_systolic_bp" name="systolic_bp" value="{{ old('systolic_bp', $vitalSign->systolic_bp) }}">
                                @error('systolic_bp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vs_diastolic_bp" class="form-label">ضغط الدم الانبساطي (mmHg)</label>
                                <input type="number" class="form-control @error('diastolic_bp') is-invalid @enderror" id="vs_diastolic_bp" name="diastolic_bp" value="{{ old('diastolic_bp', $vitalSign->diastolic_bp) }}">
                                @error('diastolic_bp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vs_heart_rate" class="form-label">معدل النبض (bpm)</label>
                                <input type="number" class="form-control @error('heart_rate') is-invalid @enderror" id="vs_heart_rate" name="heart_rate" value="{{ old('heart_rate', $vitalSign->heart_rate) }}">
                                @error('heart_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vs_respiratory_rate" class="form-label">معدل التنفس (rpm)</label>
                                <input type="number" class="form-control @error('respiratory_rate') is-invalid @enderror" id="vs_respiratory_rate" name="respiratory_rate" value="{{ old('respiratory_rate', $vitalSign->respiratory_rate) }}">
                                @error('respiratory_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vs_oxygen_saturation" class="form-label">تشبع الأكسجين (%)</label>
                                <input type="number" step="0.1" class="form-control @error('oxygen_saturation') is-invalid @enderror" id="vs_oxygen_saturation" name="oxygen_saturation" value="{{ old('oxygen_saturation', $vitalSign->oxygen_saturation) }}">
                                @error('oxygen_saturation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vs_pain_level" class="form-label">مستوى الألم (0-10)</label>
                                <input type="number" class="form-control @error('pain_level') is-invalid @enderror" id="vs_pain_level" name="pain_level" value="{{ old('pain_level', $vitalSign->pain_level) }}" min="0" max="10">
                                @error('pain_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="vs_notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="vs_notes" name="notes" rows="3">{{ old('notes', $vitalSign->notes) }}</textarea>
                            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                            <a href="{{ route('admin.patient_admissions.vital_signs_sheet', $vitalSign->patient_admission_id) }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    {{-- أي JS إضافي --}}
@endsection
