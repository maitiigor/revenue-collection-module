<?php

namespace Maitiigor\RC\Requests\API;

use Maitiigor\RC\Models\Company;
use Maitiigor\RC\Requests\AppBaseFormRequest;


class CreateCompanyAPIRequest extends AppBaseFormRequest
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
        'display_ordinal' => 'nullable|min:0|max:365',
        'rcc_number' => 'max:100',
        'irr_number' => 'max:100',
        'firs_vat_number' => 'max:100',
        'tin' => 'max:100',
        'is_verfied' => 'max:100',
        'verified_at' => 'max:100'
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
    *     title="short_name",
    *     description="short_name",
    *     type="string"
    * )
    */
    public $short_name;

    /**
    * @OA\Property(
    *     title="rcc_number",
    *     description="rcc_number",
    *     type="string"
    * )
    */
    public $rcc_number;

    /**
    * @OA\Property(
    *     title="irr_number",
    *     description="irr_number",
    *     type="string"
    * )
    */
    public $irr_number;

    /**
    * @OA\Property(
    *     title="firs_vat_number",
    *     description="firs_vat_number",
    *     type="string"
    * )
    */
    public $firs_vat_number;

    /**
    * @OA\Property(
    *     title="tin",
    *     description="tin",
    *     type="string"
    * )
    */
    public $tin;

    /**
    * @OA\Property(
    *     title="is_verfied",
    *     description="is_verfied",
    *     type="boolean"
    * )
    */
    public $is_verfied;

    /**
    * @OA\Property(
    *     title="verified_at",
    *     description="verified_at",
    *     type="string"
    * )
    */
    public $verified_at;

    /**
    * @OA\Property(
    *     title="contact_person",
    *     description="contact_person",
    *     type="string"
    * )
    */
    public $contact_person;

    /**
    * @OA\Property(
    *     title="contact_number",
    *     description="contact_number",
    *     type="string"
    * )
    */
    public $contact_number;

    /**
    * @OA\Property(
    *     title="email_address",
    *     description="email_address",
    *     type="string"
    * )
    */
    public $email_address;

    /**
    * @OA\Property(
    *     title="website_url",
    *     description="website_url",
    *     type="string"
    * )
    */
    public $website_url;

    /**
    * @OA\Property(
    *     title="status",
    *     description="status",
    *     type="string"
    * )
    */
    public $status;


}
