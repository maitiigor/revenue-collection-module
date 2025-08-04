<?php

namespace Maitiigor\RC\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maitiigor\RC\Models\ComplianceReport;

use Maitiigor\RC\Events\ComplianceReportCreated;
use Maitiigor\RC\Events\ComplianceReportUpdated;
use Maitiigor\RC\Events\ComplianceReportDeleted;

use Maitiigor\RC\Requests\API\CreateComplianceReportAPIRequest;
use Maitiigor\RC\Requests\API\UpdateComplianceReportAPIRequest;

use Maitiigor\FoundationCore\Traits\ApiResponder;
use Maitiigor\FoundationCore\Models\Organization;

use Maitiigor\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class ComplianceReportController
 * @package Maitiigor\RC\Controllers\API
 */

class ComplianceReportAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the ComplianceReport.
     * GET|HEAD /complianceReports
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
        $query = ComplianceReport::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $complianceReports = $this->showAll($query->get());

        return $this->sendResponse($complianceReports->toArray(), 'Compliance Reports retrieved successfully');
    }

    /**
     * Store a newly created ComplianceReport in storage.
     * POST /complianceReports
     *
     * @param CreateComplianceReportAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateComplianceReportAPIRequest $request, Organization $organization)
    {
        $input = $request->all();

        /** @var ComplianceReport $complianceReport */
        $complianceReport = ComplianceReport::create($input);
        
        ComplianceReportCreated::dispatch($complianceReport);
        return $this->sendResponse($complianceReport->toArray(), 'Compliance Report saved successfully');
    }

    /**
     * Display the specified ComplianceReport.
     * GET|HEAD /complianceReports/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        /** @var ComplianceReport $complianceReport */
        $complianceReport = ComplianceReport::find($id);

        if (empty($complianceReport)) {
            return $this->sendError('Compliance Report not found');
        }

        return $this->sendResponse($complianceReport->toArray(), 'Compliance Report retrieved successfully');
    }

    /**
     * Update the specified ComplianceReport in storage.
     * PUT/PATCH /complianceReports/{id}
     *
     * @param int $id
     * @param UpdateComplianceReportAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateComplianceReportAPIRequest $request, Organization $organization)
    {
        /** @var ComplianceReport $complianceReport */
        $complianceReport = ComplianceReport::find($id);

        if (empty($complianceReport)) {
            return $this->sendError('Compliance Report not found');
        }

        $complianceReport->fill($request->all());
        $complianceReport->save();
        
        ComplianceReportUpdated::dispatch($complianceReport);
        return $this->sendResponse($complianceReport->toArray(), 'ComplianceReport updated successfully');
    }

    /**
     * Remove the specified ComplianceReport from storage.
     * DELETE /complianceReports/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var ComplianceReport $complianceReport */
        $complianceReport = ComplianceReport::find($id);

        if (empty($complianceReport)) {
            return $this->sendError('Compliance Report not found');
        }

        $complianceReport->delete();
        ComplianceReportDeleted::dispatch($complianceReport);
        return $this->sendSuccess('Compliance Report deleted successfully');
    }
}
