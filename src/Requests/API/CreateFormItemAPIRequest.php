<?php

namespace Maitiigor\LCC\DocCraft\Requests\API;

use Maitiigor\LCC\DocCraft\Models\FormItem;
use Maitiigor\LCC\DocCraft\Requests\AppBaseFormRequest;


class CreateFormItemAPIRequest extends AppBaseFormRequest
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
        'label' => 'required',
        'description' => 'nullable|max:100',
        'validations' => 'nullable|max:100',
        'input_type' => 'nullable|max:100',
        'options' => 'nullable|max:100',
        'is_active' => 'required'
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
    *     title="label",
    *     description="label",
    *     type="string"
    * )
    */
    public $label;

    /**
    * @OA\Property(
    *     title="description",
    *     description="description",
    *     type="string"
    * )
    */
    public $description;

    /**
    * @OA\Property(
    *     title="validations",
    *     description="validations",
    *     type="string"
    * )
    */
    public $validations;

    /**
    * @OA\Property(
    *     title="input_type",
    *     description="input_type",
    *     type="string"
    * )
    */
    public $input_type;

    /**
    * @OA\Property(
    *     title="options",
    *     description="options",
    *     type="string"
    * )
    */
    public $options;

    /**
    * @OA\Property(
    *     title="is_active",
    *     description="is_active",
    *     type="boolean"
    * )
    */
    public $is_active;


}
