<?php

namespace Maitiigor\RC\Requests;

use Maitiigor\RC\Requests\AppBaseFormRequest;
use Maitiigor\RC\Models\CompanyUser;

class CreateCompanyUserRequest extends AppBaseFormRequest
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
        'company_id' => 'required',
        'user_id' => 'required'
        ];
    }
}
