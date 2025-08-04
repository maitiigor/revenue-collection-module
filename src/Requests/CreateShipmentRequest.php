<?php

namespace App\Requests;

use App\Requests\AppBaseFormRequest;
use App\Models\Shipment;

class CreateShipmentRequest extends AppBaseFormRequest
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
            'customer_id' => 'required',
        'shipment_date' => 'required',
        'is_completed' => 'required|numeric|min:0',
        'expected_arrival_date' => 'required',
        'arrival_date' => 'required',
        'reference_reciept' => 'required|min:5|max:100',
        'cbm' => 'required|numeric|min:0'
        ];
    }
}
