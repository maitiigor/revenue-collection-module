<?php

namespace Maitiigor\RC\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maitiigor\RC\Models\CompanyUser;

use Maitiigor\RC\Events\CompanyUserCreated;
use Maitiigor\RC\Events\CompanyUserUpdated;
use Maitiigor\RC\Events\CompanyUserDeleted;

use Maitiigor\RC\Requests\API\CreateCompanyUserAPIRequest;
use Maitiigor\RC\Requests\API\UpdateCompanyUserAPIRequest;

use Maitiigor\FoundationCore\Traits\ApiResponder;
use Maitiigor\FoundationCore\Models\Organization;

use Maitiigor\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class CompanyUserController
 * @package Maitiigor\RC\Controllers\API
 */

class CompanyUserAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the CompanyUser.
     * GET|HEAD /companyUsers
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
        $query = CompanyUser::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $companyUsers = $this->showAll($query->get());

        return $this->sendResponse($companyUsers->toArray(), 'Company Users retrieved successfully');
    }

    /**
     * Store a newly created CompanyUser in storage.
     * POST /companyUsers
     *
     * @param CreateCompanyUserAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCompanyUserAPIRequest $request, Organization $organization)
    {
        $input = $request->all();

        /** @var CompanyUser $companyUser */
        $companyUser = CompanyUser::create($input);
        
        CompanyUserCreated::dispatch($companyUser);
        return $this->sendResponse($companyUser->toArray(), 'Company User saved successfully');
    }

    /**
     * Display the specified CompanyUser.
     * GET|HEAD /companyUsers/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        /** @var CompanyUser $companyUser */
        $companyUser = CompanyUser::find($id);

        if (empty($companyUser)) {
            return $this->sendError('Company User not found');
        }

        return $this->sendResponse($companyUser->toArray(), 'Company User retrieved successfully');
    }

    /**
     * Update the specified CompanyUser in storage.
     * PUT/PATCH /companyUsers/{id}
     *
     * @param int $id
     * @param UpdateCompanyUserAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCompanyUserAPIRequest $request, Organization $organization)
    {
        /** @var CompanyUser $companyUser */
        $companyUser = CompanyUser::find($id);

        if (empty($companyUser)) {
            return $this->sendError('Company User not found');
        }

        $companyUser->fill($request->all());
        $companyUser->save();
        
        CompanyUserUpdated::dispatch($companyUser);
        return $this->sendResponse($companyUser->toArray(), 'CompanyUser updated successfully');
    }

    /**
     * Remove the specified CompanyUser from storage.
     * DELETE /companyUsers/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var CompanyUser $companyUser */
        $companyUser = CompanyUser::find($id);

        if (empty($companyUser)) {
            return $this->sendError('Company User not found');
        }

        $companyUser->delete();
        CompanyUserDeleted::dispatch($companyUser);
        return $this->sendSuccess('Company User deleted successfully');
    }
}
