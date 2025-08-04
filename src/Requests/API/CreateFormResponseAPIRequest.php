<?php

namespace Maitiigor\LCC\DocCraft\Requests\API;

use Maitiigor\LCC\DocCraft\Models\FormResponse;
use Maitiigor\LCC\DocCraft\Requests\AppBaseFormRequest;


class CreateFormResponseAPIRequest extends AppBaseFormRequest
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
        'template_id' => 'required',
        'response_data' => 'nullable|max:1000',
        'status' => 'nullable|max:100',
        'status' => 'nullable|max:100',
        'email' => 'nullable|max:300',
        'last_name' => 'nullable|max:300',
        'first_name' => 'nullable|max:300',
        'name_title' => 'nullable|max:300'
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
    *     title="template_id",
    *     description="template_id",
    *     type="string"
    * )
    */
    public $template_id;

    /**
    * @OA\Property(
    *     title="status",
    *     description="status",
    *     type="string"
    * )
    */
    public $status;

    /**
    * @OA\Property(
    *     title="status",
    *     description="status",
    *     type="string"
    * )
    */
    public $status;

    /**
    * @OA\Property(
    *     title="email",
    *     description="email",
    *     type="string"
    * )
    */
    public $email;

    /**
    * @OA\Property(
    *     title="last_name",
    *     description="last_name",
    *     type="string"
    * )
    */
    public $last_name;

    /**
    * @OA\Property(
    *     title="first_name",
    *     description="first_name",
    *     type="string"
    * )
    */
    public $first_name;

    /**
    * @OA\Property(
    *     title="name_title",
    *     description="name_title",
    *     type="string"
    * )
    */
    public $name_title;


}
