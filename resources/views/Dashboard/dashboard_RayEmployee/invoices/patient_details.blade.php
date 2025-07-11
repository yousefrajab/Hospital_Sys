@extends('Dashboard.layouts.master')
 @section('title')
     الكشوفات
 @stop
 @section('css')


 @endsection
 @section('page-header')
     <!-- breadcrumb -->
     <div class="breadcrumb-header justify-content-between">
         <div class="my-auto">
             <div class="d-flex">
                 <h4 class="content-title mb-0 my-auto">صور الاشعة</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{$rays->Patient->name}}</span>
             </div>
         </div>
     </div>
     <!-- breadcrumb -->
 @endsection
 @section('content')

     <div class="form-group">
         <label for="exampleFormControlTextarea1">ملاحظات دكتور الاشعة</label>
         <textarea readonly class="form-control" id="exampleFormControlTextarea1" rows="3">{{$rays->description_employee}}</textarea>
     </div>

 

    <div class="demo-gallery">
        <ul id="lightgallery" class="list-unstyled row row-sm pr-0">
            @foreach($rays->images as $image)
                <li class="col-sm-6 col-lg-4">
                    <a href="javascript:void(0);" onclick="openImage('{{ URL::asset('Dashboard/img/Rays/'.$image->filename) }}')">
                        <img width="100%" height="350px" class="img-responsive" src="{{ URL::asset('Dashboard/img/Rays/'.$image->filename) }}" alt="NoImg">
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <script>
        function openImage(imageUrl) {
            // فتح نافذة جديدة بمقاس معين
            window.open(imageUrl, '_blank', 'width=800,height=600,top=100,left=200,scrollbars=yes,resizable=yes');
        }
    </script>

     <!-- row closed -->
     </div>
     <!-- Container closed -->
     </div>
     <!-- main-content closed -->

 @endsection
 @section('js')


 @endsection
