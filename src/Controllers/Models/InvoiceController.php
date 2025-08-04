<?php

namespace Maitiigor\RC\Controllers\Models;

use Maitiigor\RC\Models\Invoice;

use Maitiigor\RC\Events\InvoiceCreated;
use Maitiigor\RC\Events\InvoiceUpdated;
use Maitiigor\RC\Events\InvoiceDeleted;

use Maitiigor\RC\Requests\CreateInvoiceRequest;
use Maitiigor\RC\Requests\UpdateInvoiceRequest;

use Maitiigor\RC\DataTables\InvoiceDataTable;

use Maitiigor\FoundationCore\Controllers\BaseController;
use Maitiigor\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class InvoiceController extends BaseController
{
    /**
     * Display a listing of the Invoice.
     *
     * @param InvoiceDataTable $invoiceDataTable
     * @return Response
     */
    public function index(Organization $org, InvoiceDataTable $invoiceDataTable)
    {
        $current_user = Auth()->user();

        $cdv_invoices = new \Maitiigor\FoundationCore\View\Components\CardDataView(Invoice::class, "nda-hrm-module::pages.invoices.card_view_item");
        $cdv_invoices->setDataQuery(['organization_id'=>$org->id])
                        //->addDataGroup('label','field','value')
                        //->setSearchFields(['field_to_search1','field_to_search2'])
                        //->addDataOrder('display_ordinal','DESC')
                        //->addDataOrder('id','DESC')
                        ->enableSearch(true)
                        ->enablePagination(true)
                        ->setPaginationLimit(20)
                        ->setSearchPlaceholder('Search Invoice');

        if (request()->expectsJson()){
            return $cdv_invoices->render();
        }

        return view('nda-hrm-module::pages.invoices.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList())
                    ->with('cdv_invoices', $cdv_invoices);

        /*
        return $invoiceDataTable->render('nda-hrm-module::pages.invoices.index',[
            'current_user'=>$current_user,
            'months_list'=>BaseController::monthsList(),
            'states_list'=>BaseController::statesList()
        ]);
        */
    }

    /**
     * Show the form for creating a new Invoice.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('nda-hrm-module::pages.invoices.create');
    }

    /**
     * Store a newly created Invoice in storage.
     *
     * @param CreateInvoiceRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateInvoiceRequest $request)
    {
        $input = $request->all();

        /** @var Invoice $invoice */
        $invoice = Invoice::create($input);

        InvoiceCreated::dispatch($invoice);
        return redirect(route('lcc-platform-mgt.invoices.index'));
    }

    /**
     * Display the specified Invoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var Invoice $invoice */
        $invoice = Invoice::find($id);

        if (empty($invoice)) {
            return redirect(route('lcc-platform-mgt.invoices.index'));
        }

        return view('nda-hrm-module::pages.invoices.show')
                            ->with('invoice', $invoice)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Show the form for editing the specified Invoice.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var Invoice $invoice */
        $invoice = Invoice::find($id);

        if (empty($invoice)) {
            return redirect(route('lcc-platform-mgt.invoices.index'));
        }

        return view('nda-hrm-module::pages.invoices.edit')
                            ->with('invoice', $invoice)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Update the specified Invoice in storage.
     *
     * @param  int              $id
     * @param UpdateInvoiceRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateInvoiceRequest $request)
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::find($id);

        if (empty($invoice)) {
            return redirect(route('lcc-platform-mgt.invoices.index'));
        }

        $invoice->fill($request->all());
        $invoice->save();
        
        InvoiceUpdated::dispatch($invoice);
        return redirect(route('lcc-platform-mgt.invoices.index'));
    }

    /**
     * Remove the specified Invoice from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::find($id);

        if (empty($invoice)) {
            return redirect(route('lcc-platform-mgt.invoices.index'));
        }

        $invoice->delete();

        InvoiceDeleted::dispatch($invoice);
        return redirect(route('lcc-platform-mgt.invoices.index'));
    }

        
    public function processBulkUpload(Organization $org, Request $request){

        $attachedFileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads'), $attachedFileName);
        $path_to_file = public_path('uploads').'/'.$attachedFileName;

        //Process each line
        $loop = 1;
        $errors = [];
        $lines = file($path_to_file);

        if (count($lines) > 1) {
            foreach ($lines as $line) {
                
                if ($loop > 1) {
                    $data = explode(',', $line);
                    // if (count($invalids) > 0) {
                    //     array_push($errors, $invalids);
                    //     continue;
                    // }else{
                    //     //Check if line is valid
                    //     if (!$valid) {
                    //         $errors[] = $msg;
                    //     }
                    // }
                }
                $loop++;
            }
        }else{
            $errors[] = 'The uploaded csv file is empty';
        }
        
        if (count($errors) > 0) {
            return $this->sendError($this->array_flatten($errors), 'Errors processing file');
        }
        return $this->sendResponse($subject->toArray(), 'Bulk upload completed successfully');
    }
}
