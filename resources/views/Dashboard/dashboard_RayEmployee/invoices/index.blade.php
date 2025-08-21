@extends('Dashboard.layouts.master')
 @section('title')
     الكشوفات
 @stop
 @section('css')


     <link href="{{URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>

 @section('page-header')
     <!-- breadcrumb -->
     <div class="breadcrumb-header justify-content-between">
         <div class="my-auto">
             <div class="d-flex">
                 <h4 class="content-title mb-0 my-auto">{{ trans('ray.statements') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ trans('ray.invoices') }}</span>
             </div>
         </div>
     </div>
     <!-- breadcrumb -->
 @endsection
 @section('content')
     @include('Dashboard.messages_alert')
     <!-- row -->
     <!-- row opened -->
     <div class="row row-sm">
         <div class="col-xl-12">
             <div class="card">
                 <div class="card-body">
                     <div class="table-responsive">
                         <table class="table text-md-nowrap" id="example1">
                             <thead>
                             <tr>
                                 <th>#</th>
                                 <th>{{ trans('ray.invoice_date') }}</th>
                                 <th>{{ trans('ray.patient_name') }}</th>
                                 <th>{{ trans('ray.doctor_name') }}</th>
                                 <th>{{ trans('ray.required_scan') }}</th>
                                 <th>{{ trans('ray.invoice_status') }}</th>
                                 <th>{{ trans('ray.actions') }}</th>
                             </tr>
                             </thead>
                             <tbody>
                             @foreach($invoices as $invoice)
                                 <tr>
                                     <td>{{ $loop->iteration}}</td>
                                     <td>{{ $invoice->created_at }}</td>
                                     <td>{{ $invoice->Patient->name }}</td>
                                     <td>{{ $invoice->doctor->name }}</td>
                                     <td>{{ $invoice->description }}</td>
                                     <td>
                                         @if($invoice->case == 0)
                                             <span class="badge badge-danger">{{ trans('ray.status_in_progress') }}</span>
                                         @else
                                             <span class="badge badge-success">{{ trans('ray.status_completed') }}</span>
                                         @endif
                                     </td>

                                     <td>
                                         <div class="dropdown">
                                             <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-outline-primary btn-sm" data-toggle="dropdown" type="button">{{trans('ray.Processes')}}<i class="fas fa-caret-down mr-1"></i></button>
                                             <div class="dropdown-menu tx-13">
                                                 <a class="dropdown-item" href="{{route('ray_employee.invoices_ray_employee.edit',$invoice->id)}}"><i class="text-primary fa fa-stethoscope"></i>&nbsp;&nbsp;{{ trans('ray.add_diagnosis') }}</a>
                                             </div>
                                         </div>
                                     </td>
                                 </tr>
                             @endforeach
                             </tbody>
                         </table>
                     </div>
                 </div><!-- bd -->
             </div><!-- bd -->
         </div>
         <!--/div-->

         <!-- /row -->
     </div>
     <!-- row closed -->

     <!-- Container closed -->

     <!-- main-content closed -->
 @endsection
 @section('js')

     <!--Internal  Notify js -->
     <script src="{{URL::asset('dashboard/plugins/notify/js/notifIt.js')}}"></script>
     <script src="{{URL::asset('/plugins/notify/js/notifit-custom.js')}}"></script>

 @endsection
