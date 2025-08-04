<?php

namespace Maitiigor\RC\Requests\API;

use Maitiigor\RC\Models\Invoice;
use Maitiigor\RC\Requests\AppBaseFormRequest;


class CreateInvoiceAPIRequest extends AppBaseFormRequest
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
    *     title="invoice_number",
    *     description="invoice_number",
    *     type="string"
    * )
    */
    public $invoice_number;

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
    *     title="status",
    *     description="status",
    *     type="string"
    * )
    */
    public $status;

    /**
    * @OA\Property(
    *     title="project_id",
    *     description="project_id",
    *     type="string"
    * )
    */
    public $project_id;

    /**
    * @OA\Property(
    *     title="creator_user_id",
    *     description="creator_user_id",
    *     type="string"
    * )
    */
    public $creator_user_id;

    /**
    * @OA\Property(
    *     title="revenue_ledger_id",
    *     description="revenue_ledger_id",
    *     type="string"
    * )
    */
    public $revenue_ledger_id;

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
    *     title="invoice_date",
    *     description="invoice_date",
    *     type="string"
    * )
    */
    public $invoice_date;

    /**
    * @OA\Property(
    *     title="invoice_due_date",
    *     description="invoice_due_date",
    *     type="string"
    * )
    */
    public $invoice_due_date;


}
