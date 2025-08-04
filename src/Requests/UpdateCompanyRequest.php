<?php

namespace Maitiigor\RC\Requests;

use Maitiigor\RC\Requests\AppBaseFormRequest;
use Maitiigor\RC\Models\Company;

class UpdateCompanyRequest extends AppBaseFormRequest
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
            'organization_id' => 'required',
        'display_ordinal' => 'nullable|min:0|max:365',
        'rcc_number' => 'max:100',
        'irr_number' => 'max:100',
        'firs_vat_number' => 'max:100',
        'tin' => 'max:100',
        'is_verfied' => 'max:100',
        'verified_at' => 'max:100'
        ];
    }
}
