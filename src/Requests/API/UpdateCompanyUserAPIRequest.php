<?php

namespace Maitiigor\RC\Requests\API;

use Maitiigor\RC\Models\CompanyUser;
use Maitiigor\RC\Requests\AppBaseFormRequest;


class UpdateCompanyUserAPIRequest extends AppBaseFormRequest
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
        'company_id' => 'required',
        'user_id' => 'required'
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
    *     title="company_id",
    *     description="company_id",
    *     type="string"
    * )
    */
    public $company_id;

    /**
    * @OA\Property(
    *     title="user_id",
    *     description="user_id",
    *     type="string"
    * )
    */
    public $user_id;

    /**
    * @OA\Property(
    *     title="role",
    *     description="role",
    *     type="string"
    * )
    */
    public $role;


}
