<?php

namespace Maitiigor\LCC\DocCraft\Requests\API;

use Maitiigor\LCC\DocCraft\Models\DocCraft;
use Maitiigor\LCC\DocCraft\Requests\AppBaseFormRequest;


class CreateDocCraftAPIRequest extends AppBaseFormRequest
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
        'name' => 'required',
        'description' => 'nullable|max:100',
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
    *     title="name",
    *     description="name",
    *     type="string"
    * )
    */
    public $name;

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
    *     title="is_active",
    *     description="is_active",
    *     type="boolean"
    * )
    */
    public $is_active;


}
