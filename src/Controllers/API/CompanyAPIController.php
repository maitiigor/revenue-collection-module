<?php

namespace Maitiigor\RC\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maitiigor\RC\Models\Company;

use Maitiigor\RC\Events\CompanyCreated;
use Maitiigor\RC\Events\CompanyUpdated;
use Maitiigor\RC\Events\CompanyDeleted;

use Maitiigor\RC\Requests\API\CreateCompanyAPIRequest;
use Maitiigor\RC\Requests\API\UpdateCompanyAPIRequest;

use Maitiigor\FoundationCore\Traits\ApiResponder;
use Maitiigor\FoundationCore\Models\Organization;

use Maitiigor\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class CompanyController
 * @package Maitiigor\RC\Controllers\API
 */

class CompanyAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the Company.
     * GET|HEAD /companies
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
        $query = Company::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $companies = $this->showAll($query->get());

        return $this->sendResponse($companies->toArray(), 'Companies retrieved successfully');
    }

    /**
     * Store a newly created Company in storage.
     * POST /companies
     *
     * @param CreateCompanyAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCompanyAPIRequest $request, Organization $organization)
    {
        $input = $request->all();

        /** @var Company $company */
        $company = Company::create($input);
        
        CompanyCreated::dispatch($company);
        return $this->sendResponse($company->toArray(), 'Company saved successfully');
    }

    /**
     * Display the specified Company.
     * GET|HEAD /companies/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        /** @var Company $company */
        $company = Company::find($id);

        if (empty($company)) {
            return $this->sendError('Company not found');
        }

        return $this->sendResponse($company->toArray(), 'Company retrieved successfully');
    }

    /**
     * Update the specified Company in storage.
     * PUT/PATCH /companies/{id}
     *
     * @param int $id
     * @param UpdateCompanyAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCompanyAPIRequest $request, Organization $organization)
    {
        /** @var Company $company */
        $company = Company::find($id);

        if (empty($company)) {
            return $this->sendError('Company not found');
        }

        $company->fill($request->all());
        $company->save();
        
        CompanyUpdated::dispatch($company);
        return $this->sendResponse($company->toArray(), 'Company updated successfully');
    }

    /**
     * Remove the specified Company from storage.
     * DELETE /companies/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var Company $company */
        $company = Company::find($id);

        if (empty($company)) {
            return $this->sendError('Company not found');
        }

        $company->delete();
        CompanyDeleted::dispatch($company);
        return $this->sendSuccess('Company deleted successfully');
    }
}
