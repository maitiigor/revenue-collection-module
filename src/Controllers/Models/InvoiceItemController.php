<?php

namespace Maitiigor\RC\Controllers\Models;

use Maitiigor\RC\Models\InvoiceItem;

use Maitiigor\RC\Events\InvoiceItemCreated;
use Maitiigor\RC\Events\InvoiceItemUpdated;
use Maitiigor\RC\Events\InvoiceItemDeleted;

use Maitiigor\RC\Requests\CreateInvoiceItemRequest;
use Maitiigor\RC\Requests\UpdateInvoiceItemRequest;

use Maitiigor\RC\DataTables\InvoiceItemDataTable;

use Maitiigor\FoundationCore\Controllers\BaseController;
use Maitiigor\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class InvoiceItemController extends BaseController
{
    /**
     * Display a listing of the InvoiceItem.
     *
     * @param InvoiceItemDataTable $invoiceItemDataTable
     * @return Response
     */
    public function index(Organization $org, InvoiceItemDataTable $invoiceItemDataTable)
    {
        $current_user = Auth()->user();

        $cdv_invoice_items = new \Maitiigor\FoundationCore\View\Components\CardDataView(InvoiceItem::class, "nda-hrm-module::pages.invoice_items.card_view_item");
        $cdv_invoice_items->setDataQuery(['organization_id'=>$org->id])
                        //->addDataGroup('label','field','value')
                        //->setSearchFields(['field_to_search1','field_to_search2'])
                        //->addDataOrder('display_ordinal','DESC')
                        //->addDataOrder('id','DESC')
                        ->enableSearch(true)
                        ->enablePagination(true)
                        ->setPaginationLimit(20)
                        ->setSearchPlaceholder('Search InvoiceItem');

        if (request()->expectsJson()){
            return $cdv_invoice_items->render();
        }

        return view('nda-hrm-module::pages.invoice_items.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList())
                    ->with('cdv_invoice_items', $cdv_invoice_items);

        /*
        return $invoiceItemDataTable->render('nda-hrm-module::pages.invoice_items.index',[
            'current_user'=>$current_user,
            'months_list'=>BaseController::monthsList(),
            'states_list'=>BaseController::statesList()
        ]);
        */
    }

    /**
     * Show the form for creating a new InvoiceItem.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('nda-hrm-module::pages.invoice_items.create');
    }

    /**
     * Store a newly created InvoiceItem in storage.
     *
     * @param CreateInvoiceItemRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateInvoiceItemRequest $request)
    {
        $input = $request->all();

        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = InvoiceItem::create($input);

        InvoiceItemCreated::dispatch($invoiceItem);
        return redirect(route('lcc-platform-mgt.invoiceItems.index'));
    }

    /**
     * Display the specified InvoiceItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = InvoiceItem::find($id);

        if (empty($invoiceItem)) {
            return redirect(route('lcc-platform-mgt.invoiceItems.index'));
        }

        return view('nda-hrm-module::pages.invoice_items.show')
                            ->with('invoiceItem', $invoiceItem)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Show the form for editing the specified InvoiceItem.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = InvoiceItem::find($id);

        if (empty($invoiceItem)) {
            return redirect(route('lcc-platform-mgt.invoiceItems.index'));
        }

        return view('nda-hrm-module::pages.invoice_items.edit')
                            ->with('invoiceItem', $invoiceItem)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Update the specified InvoiceItem in storage.
     *
     * @param  int              $id
     * @param UpdateInvoiceItemRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateInvoiceItemRequest $request)
    {
        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = InvoiceItem::find($id);

        if (empty($invoiceItem)) {
            return redirect(route('lcc-platform-mgt.invoiceItems.index'));
        }

        $invoiceItem->fill($request->all());
        $invoiceItem->save();
        
        InvoiceItemUpdated::dispatch($invoiceItem);
        return redirect(route('lcc-platform-mgt.invoiceItems.index'));
    }

    /**
     * Remove the specified InvoiceItem from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = InvoiceItem::find($id);

        if (empty($invoiceItem)) {
            return redirect(route('lcc-platform-mgt.invoiceItems.index'));
        }

        $invoiceItem->delete();

        InvoiceItemDeleted::dispatch($invoiceItem);
        return redirect(route('lcc-platform-mgt.invoiceItems.index'));
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
