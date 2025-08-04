 @extends('layouts.app')

 @section('title_postfix')
     My Payslips
 @stop

 @section('page_title')
     My Payslips
 @stop

 @section('page_title_suffix')
        {{auth()->user()->full_name}}
 @stop

 @section('app_css')
     @include('layouts.datatables_css')
 @stop

 @section('page_title_subtext')
     <a class="ms-1" href="{{ route('dashboard') }}">
         <i class="bx bx-chevron-left"></i> Back to Dashboard
     </a>
 @stop

 @section('page_title_buttons')
     {{-- <a id="btn-new-mdl-employee-modal" class="btn btn-sm btn-primary btn-new-mdl-employee-modal">
                <i class="fas fa-plus-square me-1"></i>New Employees
            </a> --}}
 @stop

 @section('app_css')
     @include('layouts.datatables_css')
 @endsection


 @section('content')

     <!-- Statistics Row -->
     <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">

         <div class="col">
             <div class="card radius-10 bg-success text-white">
                 <div class="card-body">
                     <div class="d-flex align-items-center">
                         <div>
                             <p class="mb-0">Paid Periods</p>
                             <h4 class="my-1">{{ $paidPeriods }}</h4>
                             <p class="mb-0 font-13">Successfully processed</p>
                         </div>
                         <div class="ms-auto font-35">
                             <i class="bx bx-check-circle"></i>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <div class="col">
             <div class="card radius-10 bg-primary text-white">
                 <div class="card-body">
                     <div class="d-flex align-items-center">
                         <div>
                             <p class="mb-0">Total Payments</p>
                             <h4 class="my-1">{{ $totalPayments }}</h4>

                         </div>
                         <div class="ms-auto font-35">
                             <i class="bx bx-user-check"></i>
                         </div>
                     </div>
                 </div>
             </div>
         </div>


         <div class="col">
             <div class="card radius-10 bg-warning text-white">
                 <div class="card-body">
                     <div class="d-flex align-items-center">
                         <div>
                             <p class="mb-0">Total Deductions</p>
                             <h4 class="my-1">{{ $totalDeductions }}</h4>
                         </div>
                         <div class="ms-auto font-35">
                             <i class="bx bx-sitemap"></i>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <div class="col">
             <div class="card radius-10 bg-warning text-white">
                 <div class="card-body">
                     <div class="d-flex align-items-center">
                         <div>
                             <p class="mb-0">Average Payment</p>
                             <h4 class="my-1">{{ $averagePayment }}</h4>
                         </div>
                         <div class="ms-auto font-35">
                             <i class="bx bx-sitemap"></i>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <div class="row">
         <div class="col-md-12">
             <div class="card">
                 <div class="card-body">

                     {!! $dataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered']) !!}

                 </div>
             </div>

         </div>
     </div>

 @endsection

 @push('page_scripts')
     @include('layouts.datatables_js')
     {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
 @endpush
