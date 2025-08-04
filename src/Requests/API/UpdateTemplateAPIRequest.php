<?php

namespace Maitiigor\LCC\DocCraft\Requests\API;

use Maitiigor\LCC\DocCraft\Models\Template;
use Maitiigor\LCC\DocCraft\Requests\AppBaseFormRequest;


class UpdateTemplateAPIRequest extends AppBaseFormRequest
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
        $rules['slug'] = $rules['slug'].",".$this->route("dc_template");
        */
        return [
            'organization_id' => 'required',
        'name' => 'required',
        'description' => 'nullable|max:100',
        'category_id' => 'required',
        'slug' => 'required|max:300|unique:dc_templates,slug',
        'amount' => 'nullable|numeric|min:0|max:999999.99',
        'discount_percentage' => 'nullable|numeric|min:0|max:100',
        'is_featured' => 'nullable',
        'is_published' => 'nullable',
        'meta_data_json' => 'nullable|max:1000'
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
    *     title="category_id",
    *     description="category_id",
    *     type="string"
    * )
    */
    public $category_id;

    /**
    * @OA\Property(
    *     title="slug",
    *     description="slug",
    *     type="string"
    * )
    */
    public $slug;

    /**
    * @OA\Property(
    *     title="amount",
    *     description="amount",
    *     type="number"
    * )
    */
    public $amount;

    /**
    * @OA\Property(
    *     title="discount_percentage",
    *     description="discount_percentage",
    *     type="number"
    * )
    */
    public $discount_percentage;

    /**
    * @OA\Property(
    *     title="is_featured",
    *     description="is_featured",
    *     type="boolean"
    * )
    */
    public $is_featured;

    /**
    * @OA\Property(
    *     title="is_published",
    *     description="is_published",
    *     type="boolean"
    * )
    */
    public $is_published;


}
