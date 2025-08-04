<?php

namespace Maitiigor\Payroll\Controllers\Frontend;

use Maitiigor\Payroll\Models\ExternalVendor;

use Maitiigor\Payroll\Events\ExternalVendorCreated;
use Maitiigor\Payroll\Events\ExternalVendorUpdated;
use Maitiigor\Payroll\Events\ExternalVendorDeleted;

use Maitiigor\Payroll\Requests\CreateExternalVendorRequest;
use Maitiigor\Payroll\Requests\UpdateExternalVendorRequest;

use Maitiigor\Payroll\DataTables\ExternalVendorDataTable;

use Maitiigor\FoundationCore\Controllers\BaseController;
use Maitiigor\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class FrontendController extends BaseController
{
    /**
     * Display a listing of the ExternalVendor.
     *
     * @param ExternalVendorDataTable $externalVendorDataTable
     * @return Response
     */
    public function index(Organization $org, Request $request)
    {
       

        return view('Payroll-module::frontend.index');

    }

    public function startPrPayrollApplication(Request $request){

        
        
        return view('Payroll-module::frontend.transcript-start');
    }

    public function startTranscriptApplication(Request $request){

        
        
        return view('Payroll-module::frontend.transcript-form');
    }

   

        
   
}