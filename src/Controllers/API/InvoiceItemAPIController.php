<?php

namespace Maitiigor\RC\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maitiigor\RC\Models\InvoiceItem;

use Maitiigor\RC\Events\InvoiceItemCreated;
use Maitiigor\RC\Events\InvoiceItemUpdated;
use Maitiigor\RC\Events\InvoiceItemDeleted;

use Maitiigor\RC\Requests\API\CreateInvoiceItemAPIRequest;
use Maitiigor\RC\Requests\API\UpdateInvoiceItemAPIRequest;

use Maitiigor\FoundationCore\Traits\ApiResponder;
use Maitiigor\FoundationCore\Models\Organization;

use Maitiigor\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class InvoiceItemController
 * @package Maitiigor\RC\Controllers\API
 */

class InvoiceItemAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the InvoiceItem.
     * GET|HEAD /invoiceItems
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
        $query = InvoiceItem::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $invoiceItems = $this->showAll($query->get());

        return $this->sendResponse($invoiceItems->toArray(), 'Invoice Items retrieved successfully');
    }

    /**
     * Store a newly created InvoiceItem in storage.
     * POST /invoiceItems
     *
     * @param CreateInvoiceItemAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateInvoiceItemAPIRequest $request, Organization $organization)
    {
        $input = $request->all();

        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = InvoiceItem::create($input);
        
        InvoiceItemCreated::dispatch($invoiceItem);
        return $this->sendResponse($invoiceItem->toArray(), 'Invoice Item saved successfully');
    }

    /**
     * Display the specified InvoiceItem.
     * GET|HEAD /invoiceItems/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = InvoiceItem::find($id);

        if (empty($invoiceItem)) {
            return $this->sendError('Invoice Item not found');
        }

        return $this->sendResponse($invoiceItem->toArray(), 'Invoice Item retrieved successfully');
    }

    /**
     * Update the specified InvoiceItem in storage.
     * PUT/PATCH /invoiceItems/{id}
     *
     * @param int $id
     * @param UpdateInvoiceItemAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInvoiceItemAPIRequest $request, Organization $organization)
    {
        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = InvoiceItem::find($id);

        if (empty($invoiceItem)) {
            return $this->sendError('Invoice Item not found');
        }

        $invoiceItem->fill($request->all());
        $invoiceItem->save();
        
        InvoiceItemUpdated::dispatch($invoiceItem);
        return $this->sendResponse($invoiceItem->toArray(), 'InvoiceItem updated successfully');
    }

    /**
     * Remove the specified InvoiceItem from storage.
     * DELETE /invoiceItems/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var InvoiceItem $invoiceItem */
        $invoiceItem = InvoiceItem::find($id);

        if (empty($invoiceItem)) {
            return $this->sendError('Invoice Item not found');
        }

        $invoiceItem->delete();
        InvoiceItemDeleted::dispatch($invoiceItem);
        return $this->sendSuccess('Invoice Item deleted successfully');
    }
}
