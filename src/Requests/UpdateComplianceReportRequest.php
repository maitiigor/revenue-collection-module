<?php

namespace Maitiigor\RC\Requests;

use Maitiigor\RC\Requests\AppBaseFormRequest;
use Maitiigor\RC\Models\ComplianceReport;

class UpdateComplianceReportRequest extends AppBaseFormRequest
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
}
