<?php

namespace App\Requests\API;

use App\Models\ShipmentTracking;
use App\Requests\AppBaseFormRequest;


class UpdateShipmentTrackingAPIRequest extends AppBaseFormRequest
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
            'shipment_id' => 'required',
        'display_ordinal' => 'nullable|min:0|max:365',
        'name' => 'required|min:5|max:100',
        'current_location' => 'required|numeric|min:0',
        'next_destination' => 'required|numeric|min:0',
        'is_current' => 'required|numeric|min:0'
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
    *     title="name",
    *     description="name",
    *     type="string"
    * )
    */
    public $name;

    /**
    * @OA\Property(
    *     title="current_location",
    *     description="current_location",
    *     type="string"
    * )
    */
    public $current_location;

    /**
    * @OA\Property(
    *     title="next_destination",
    *     description="next_destination",
    *     type="string"
    * )
    */
    public $next_destination;

    /**
    * @OA\Property(
    *     title="is_current",
    *     description="is_current",
    *     type="boolean"
    * )
    */
    public $is_current;

    /**
    * @OA\Property(
    *     title="expected_depature_date",
    *     description="expected_depature_date",
    *     type="string"
    * )
    */
    public $expected_depature_date;

    /**
    * @OA\Property(
    *     title="expected_arrival_date",
    *     description="expected_arrival_date",
    *     type="string"
    * )
    */
    public $expected_arrival_date;


}
