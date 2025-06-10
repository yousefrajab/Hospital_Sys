@extends('layouts.app') {{-- أو أي layout رئيسي تستخدمه --}}

@section('title', 'الصفحة غير موجودة')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-danger">404</h1>
    <h2 class="mb-4">عفواً، الصفحة التي تبحث عنها غير موجودة!</h2>
    <p class="lead mb-4">
        ربما تم حذف الصفحة، أو تغيير اسمها، أو أنها غير متاحة مؤقتًا.
    </p>
    <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-home me-2"></i> العودة إلى الصفحة الرئيسية
    </a>
    {{-- يمكنك إضافة صورة أو تصميم إضافي هنا --}}
</div>

<style>
    /* يمكنك إضافة بعض الأنماط هنا أو في ملف CSS عام */
    .display-1 {
        font-size: 8rem;
        font-weight: bold;
    }
</style>
@endsection
