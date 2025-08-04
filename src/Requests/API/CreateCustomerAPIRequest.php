<?php

namespace App\Requests\API;

use App\Models\Customer;
use App\Requests\AppBaseFormRequest;


class CreateCustomerAPIRequest extends AppBaseFormRequest
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
            'name' => 'required|min:5|max:100',
        'available_cbm' => 'required|numeric|min:0',
        'accumulated_cbm' => 'required|numeric|min:0'
        ];
    }

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
    *     title="available_cbm",
    *     description="available_cbm",
    *     type="number"
    * )
    */
    public $available_cbm;

    /**
    * @OA\Property(
    *     title="accumulated_cbm",
    *     description="accumulated_cbm",
    *     type="number"
    * )
    */
    public $accumulated_cbm;


}
