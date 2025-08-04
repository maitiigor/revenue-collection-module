<?php

namespace App\Requests;

use App\Requests\AppBaseFormRequest;
use App\Models\Customer;

class UpdateCustomerRequest extends AppBaseFormRequest
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
            'name' => 'required|min:5|max:100',
        'available_cbm' => 'required|numeric|min:0',
        'accumulated_cbm' => 'required|numeric|min:0'
        ];
    }
}
