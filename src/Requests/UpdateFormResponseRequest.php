<?php

namespace Maitiigor\LCC\DocCraft\Requests;

use Maitiigor\LCC\DocCraft\Requests\AppBaseFormRequest;
use Maitiigor\LCC\DocCraft\Models\FormResponse;

class UpdateFormResponseRequest extends AppBaseFormRequest
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
}
