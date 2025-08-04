<?php

namespace App\Requests\API;

use App\Models\Shipment;
use App\Requests\AppBaseFormRequest;


class UpdateShipmentAPIRequest extends AppBaseFormRequest
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
        /*
        
        */
        return [
            'customer_id' => 'required',
        'shipment_date' => 'required',
        'is_completed' => 'required|numeric|min:0',
        'expected_arrival_date' => 'required',
        'arrival_date' => 'required',
        'reference_reciept' => 'required|min:5|max:100',
        'cbm' => 'required|numeric|min:0'
        ];
    }

    /**
    * @OA\Property(
    *     title="customer_id",
    *     description="customer_id",
    *     type="string"
    * )
    */
    public $customer_id;

    /**
    * @OA\Property(
    *     title="shipment_date",
    *     description="shipment_date",
    *     type="string"
    * )
    */
    public $shipment_date;

    /**
    * @OA\Property(
    *     title="is_completed",
    *     description="is_completed",
    *     type="boolean"
    * )
    */
    public $is_completed;

    /**
    * @OA\Property(
    *     title="expected_arrival_date",
    *     description="expected_arrival_date",
    *     type="string"
    * )
    */
    public $expected_arrival_date;

    /**
    * @OA\Property(
    *     title="arrival_date",
    *     description="arrival_date",
    *     type="string"
    * )
    */
    public $arrival_date;

    /**
    * @OA\Property(
    *     title="reference_reciept",
    *     description="reference_reciept",
    *     type="string"
    * )
    */
    public $reference_reciept;

    /**
    * @OA\Property(
    *     title="cbm",
    *     description="cbm",
    *     type="number"
    * )
    */
    public $cbm;


}
