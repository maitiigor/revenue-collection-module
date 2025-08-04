<?php

namespace Maitiigor\RC\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maitiigor\RC\Models\Invoice;

use Maitiigor\RC\Events\InvoiceCreated;
use Maitiigor\RC\Events\InvoiceUpdated;
use Maitiigor\RC\Events\InvoiceDeleted;

use Maitiigor\RC\Requests\API\CreateInvoiceAPIRequest;
use Maitiigor\RC\Requests\API\UpdateInvoiceAPIRequest;

use Maitiigor\FoundationCore\Traits\ApiResponder;
use Maitiigor\FoundationCore\Models\Organization;

use Maitiigor\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class InvoiceController
 * @package Maitiigor\RC\Controllers\API
 */

class InvoiceAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the Invoice.
     * GET|HEAD /invoices
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
        $query = Invoice::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $invoices = $this->showAll($query->get());

        return $this->sendResponse($invoices->toArray(), 'Invoices retrieved successfully');
    }

    /**
     * Store a newly created Invoice in storage.
     * POST /invoices
     *
     * @param CreateInvoiceAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateInvoiceAPIRequest $request, Organization $organization)
    {
        $input = $request->all();

        /** @var Invoice $invoice */
        $invoice = Invoice::create($input);
        
        InvoiceCreated::dispatch($invoice);
        return $this->sendResponse($invoice->toArray(), 'Invoice saved successfully');
    }

    /**
     * Display the specified Invoice.
     * GET|HEAD /invoices/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::find($id);

        if (empty($invoice)) {
            return $this->sendError('Invoice not found');
        }

        return $this->sendResponse($invoice->toArray(), 'Invoice retrieved successfully');
    }

    /**
     * Update the specified Invoice in storage.
     * PUT/PATCH /invoices/{id}
     *
     * @param int $id
     * @param UpdateInvoiceAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInvoiceAPIRequest $request, Organization $organization)
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::find($id);

        if (empty($invoice)) {
            return $this->sendError('Invoice not found');
        }

        $invoice->fill($request->all());
        $invoice->save();
        
        InvoiceUpdated::dispatch($invoice);
        return $this->sendResponse($invoice->toArray(), 'Invoice updated successfully');
    }

    /**
     * Remove the specified Invoice from storage.
     * DELETE /invoices/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::find($id);

        if (empty($invoice)) {
            return $this->sendError('Invoice not found');
        }

        $invoice->delete();
        InvoiceDeleted::dispatch($invoice);
        return $this->sendSuccess('Invoice deleted successfully');
    }
}
