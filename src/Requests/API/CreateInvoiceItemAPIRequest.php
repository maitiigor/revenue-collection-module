<?php

namespace Maitiigor\RC\Requests\API;

use Maitiigor\RC\Models\InvoiceItem;
use Maitiigor\RC\Requests\AppBaseFormRequest;


class CreateInvoiceItemAPIRequest extends AppBaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'organization_id' => 'required',
        'invoice_id' => 'required',
        'title' => 'required|max:200',
        'description' => 'nullable|max:2000',
        'quantity' => 'required|min:1',
        'unit_price' => 'required|min:0',
        'total_amount' => 'required|min:0',
        'item_type' => 'required|in:base_tax,penalty,processing_fee,discount'
        ];
    }

    /**
    * @OA\Property(
    *     title="organization_id",
    *     description="organization_id",
    *     type="string"
    * )
    */
    public $organization_id;

    /**
    * @OA\Property(
    *     title="invoice_id",
    *     description="invoice_id",
    *     type="string"
    * )
    */
    public $invoice_id;

    /**
    * @OA\Property(
    *     title="title",
    *     description="title",
    *     type="string"
    * )
    */
    public $title;

    /**
    * @OA\Property(
    *     title="description",
    *     description="description",
    *     type="string"
    * )
    */
    public $description;

    /**
    * @OA\Property(
    *     title="quantity",
    *     description="quantity",
    *     type="number"
    * )
    */
    public $quantity;

    /**
    * @OA\Property(
    *     title="unit_price",
    *     description="unit_price",
    *     type="number"
    * )
    */
    public $unit_price;

    /**
    * @OA\Property(
    *     title="total_amount",
    *     description="total_amount",
    *     type="integer"
    * )
    */
    public $total_amount;

    /**
    * @OA\Property(
    *     title="item_type",
    *     description="item_type",
    *     type="string"
    * )
    */
    public $item_type;


}
