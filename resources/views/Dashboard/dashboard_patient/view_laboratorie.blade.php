@extends('Dashboard.layouts.master') {{-- افترض أن لديك layout خاص بواجهة المريض --}}
@section('title')
    نتائج التحاليل الخاصة بي
@stop
@section('css')
    <style>
        .demo-gallery ul {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            padding-left: 0;
        }

        .demo-gallery li {
            list-style-type: none;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
        }

        .gallery-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease-in-out;
            display: block;
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
            background-color: #fff !important;
            border: 1px solid #ced4da;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">نتائج التحاليل</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    @if ($laboratorie)
        <div class="notes-section">
            <label for="lab_notes">ملاحظات قسم المختبر:</label>
            <textarea readonly class="form-control" id="lab_notes" rows="3">{{ $laboratorie->description_employee ?? 'لا توجد ملاحظات.' }}</textarea>
        </div>

        @if ($laboratorie->images && $laboratorie->images->count() > 0)
            <h4>صور التحاليل:</h4>
            <div class="demo-gallery">
                <ul id="lightgallery" class="list-unstyled row row-sm pr-0">
                    @foreach ($laboratorie->images as $image)
                        <li class="col-sm-6 col-lg-4 mb-4">
                            <a href="javascript:void(0);"
                                onclick="openImage('{{ URL::asset('Dashboard/img/laboratories/' . $image->filename) }}')">
                                <img width="100%" height="350px" class="img-responsive"
                                    src="{{ URL::asset('Dashboard/img/laboratories/' . $image->filename) }}" alt="NoImg">
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="alert alert-info">لا توجد صور مرفقة لهذا التحليل.</div>
        @endif
    @else
        <div class="alert alert-warning">لم يتم العثور على تفاصيل التحليل المطلوب.</div>
    @endif

    <script>
        function openImage(imageUrl) {
            window.open(imageUrl, '_blank', 'width=800,height=600,top=100,left=200,scrollbars=yes,resizable=yes');
        }
    </script>

@endsection

@section('js')
@endsection
