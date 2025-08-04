<?php

namespace App\Requests;

use App\Requests\AppBaseFormRequest;
use App\Models\ShipmentItem;

class UpdateShipmentItemRequest extends AppBaseFormRequest
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
        'product_name' => 'required|min:5|max:100',
        'quantity' => 'required|numeric|min:0',
        'price_per_item' => 'required|numeric|min:0'
        ];
    }
}
