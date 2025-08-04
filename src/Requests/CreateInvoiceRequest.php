<?php

namespace Maitiigor\RC\Requests;

use Maitiigor\RC\Requests\AppBaseFormRequest;
use Maitiigor\RC\Models\Invoice;

class CreateInvoiceRequest extends AppBaseFormRequest
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
        'invoice_number' => 'required|max:200',
        'amount' => 'required|numeric|min:-99999.99|max:99999.99',
        'status' => 'max:100',
        'project_id' => 'required',
        'creator_user_id' => 'required',
        'revenue_ledger_id' => 'required',
        'amount' => 'required|numeric|min:-99999.99|max:99999.99'
        ];
    }
}
