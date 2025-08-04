<?php

namespace Maitiigor\RC\Requests\API;

use Maitiigor\RC\Models\ComplianceReport;
use Maitiigor\RC\Requests\AppBaseFormRequest;


class UpdateComplianceReportAPIRequest extends AppBaseFormRequest
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
        'project_id' => 'required',
        'inspector_id' => 'required',
        'status' => 'required|in:compliant,non_compliant,partial',
        'remarks' => 'nullable|max:2000',
        'report_date' => 'required|date'
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
    *     title="project_id",
    *     description="project_id",
    *     type="string"
    * )
    */
    public $project_id;

    /**
    * @OA\Property(
    *     title="inspector_id",
    *     description="inspector_id",
    *     type="string"
    * )
    */
    public $inspector_id;

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
    *     title="remarks",
    *     description="remarks",
    *     type="string"
    * )
    */
    public $remarks;

    /**
    * @OA\Property(
    *     title="report_date",
    *     description="report_date",
    *     type="string"
    * )
    */
    public $report_date;


}
