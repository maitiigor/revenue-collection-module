<?php

namespace App\Requests\API;

use App\Models\ShipmentItem;
use App\Requests\AppBaseFormRequest;


class CreateShipmentItemAPIRequest extends AppBaseFormRequest
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
            'shipment_id' => 'required',
        'display_ordinal' => 'nullable|min:0|max:365',
        'product_name' => 'required|min:5|max:100',
        'quantity' => 'required|numeric|min:0',
        'price_per_item' => 'required|numeric|min:0'
        ];
    }

    /**
    * @OA\Property(
    *     title="shipment_id",
    *     description="shipment_id",
    *     type="string"
    * )
    */
    public $shipment_id;

    /**
    * @OA\Property(
    *     title="display_ordinal",
    *     description="display_ordinal",
    *     type="integer"
    * )
    */
    public $display_ordinal;

    /**
    * @OA\Property(
    *     title="product_name",
    *     description="product_name",
    *     type="string"
    * )
    */
    public $product_name;

    /**
    * @OA\Property(
    *     title="quantity",
    *     description="quantity",
    *     type="integer"
    * )
    */
    public $quantity;

    /**
    * @OA\Property(
    *     title="price_per_item",
    *     description="price_per_item",
    *     type="number"
    * )
    */
    public $price_per_item;


}
