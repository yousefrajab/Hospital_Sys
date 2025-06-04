@extends('Dashboard.layouts.master') {{-- افترض أن لديك layout خاص بواجهة المريض أو يمكنك استخدام نفس layout الطبيب إذا كان مناسبًا --}}
@section('title')
   نتائج الأشعة الخاصة بي
@stop
@section('css')
<style>
.demo-gallery ul {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    padding-left: 0; /* لإزالة الحشوة الافتراضية لـ ul */
}

.demo-gallery li {
    list-style-type: none; /* لإزالة نقاط القائمة */
    position: relative;
    overflow: hidden;
    border-radius: 8px; /* حواف دائرية للعنصر li لمظهر أفضل */
}

.gallery-img {
    width: 100%;
    height: 250px; /* يمكنك تعديل الارتفاع حسب الحاجة */
    object-fit: cover;
    border-radius: 8px;
    transition: transform 0.3s ease-in-out;
    display: block; /* لإزالة أي مسافات أسفل الصورة */
}

.gallery-img:hover {
    transform: scale(1.05);
}

.notes-section {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
}
.notes-section label {
    font-weight: bold;
    color: #495057;
}
.notes-section textarea {
    background-color: #fff !important; /* لضمان أن النص مقروء حتى لو كان readonly */
    border: 1px solid #ced4da;
}
</style>
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">نتائج الأشعة</h4>
            {{-- لا حاجة لاسم المريض هنا لأنه هو من يشاهدها --}}
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')

@if($ray)
    <div class="notes-section">
        <label for="ray_notes">ملاحظات قسم الأشعة:</label>
        <textarea readonly class="form-control" id="ray_notes" rows="3">{{ $ray->description_employee ?? 'لا توجد ملاحظات.' }}</textarea>
    </div>

    @if($ray->images && $ray->images->count() > 0)
        <h4>صور الأشعة:</h4>
        <div class="demo-gallery">
            <ul id="lightgallery" class="list-unstyled row row-sm pr-0">
                @foreach($ray->images as $image)
                    <li class="col-sm-6 col-lg-4 mb-4"> {{-- mb-4 لإضافة مسافة سفلية --}}
                       <a href="javascript:void(0);" onclick="openImage('{{ URL::asset('Dashboard/img/Rays/'.$image->filename) }}')">
                    <img width="100%" height="350px" class="img-responsive" src="{{ URL::asset('Dashboard/img/Rays/'.$image->filename) }}" alt="NoImg">
                </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="alert alert-info">لا توجد صور مرفقة لهذه الأشعة.</div>
    @endif
@else
    <div class="alert alert-warning">لم يتم العثور على تفاصيل الأشعة المطلوبة.</div>
@endif

<script>
    function openImage(imageUrl) {
        window.open(imageUrl, '_blank', 'width=800,height=600,top=100,left=200,scrollbars=yes,resizable=yes');
    }
</script>

@endsection

@section('js')
{{-- إذا كنت تستخدم مكتبة lightbox أو مشابهة، قم بتضمين الـ JS الخاص بها هنا --}}
@endsection
