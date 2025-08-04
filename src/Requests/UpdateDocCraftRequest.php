<?php

namespace Maitiigor\LCC\DocCraft\Requests;

use Maitiigor\LCC\DocCraft\Requests\AppBaseFormRequest;
use Maitiigor\LCC\DocCraft\Models\DocCraft;

class UpdateDocCraftRequest extends AppBaseFormRequest
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
        'name' => 'required',
        'description' => 'nullable|max:100',
        'is_active' => 'required'
        ];
    }
}
