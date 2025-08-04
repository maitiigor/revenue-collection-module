<?php

namespace Maitiigor\LCC\DocCraft\Requests;

use Maitiigor\LCC\DocCraft\Requests\AppBaseFormRequest;
use Maitiigor\LCC\DocCraft\Models\Template;

class UpdateTemplateRequest extends AppBaseFormRequest
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
}
