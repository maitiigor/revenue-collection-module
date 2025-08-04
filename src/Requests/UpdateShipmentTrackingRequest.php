<?php

namespace App\Requests;

use App\Requests\AppBaseFormRequest;
use App\Models\ShipmentTracking;

class UpdateShipmentTrackingRequest extends AppBaseFormRequest
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
}
