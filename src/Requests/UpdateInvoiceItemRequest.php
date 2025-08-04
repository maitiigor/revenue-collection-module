<?php

namespace Maitiigor\RC\Requests;

use Maitiigor\RC\Requests\AppBaseFormRequest;
use Maitiigor\RC\Models\InvoiceItem;

class UpdateInvoiceItemRequest extends AppBaseFormRequest
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
        'invoice_id' => 'required',
        'title' => 'required|max:200',
        'description' => 'nullable|max:2000',
        'quantity' => 'required|min:1',
        'unit_price' => 'required|min:0',
        'total_amount' => 'required|min:0',
        'item_type' => 'required|in:base_tax,penalty,processing_fee,discount'
        ];
    }
}
