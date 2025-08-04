<?php

namespace Maitiigor\RC\Controllers\Models;

use Maitiigor\RC\Models\CompanyUser;

use Maitiigor\RC\Events\CompanyUserCreated;
use Maitiigor\RC\Events\CompanyUserUpdated;
use Maitiigor\RC\Events\CompanyUserDeleted;

use Maitiigor\RC\Requests\CreateCompanyUserRequest;
use Maitiigor\RC\Requests\UpdateCompanyUserRequest;

use Maitiigor\RC\DataTables\CompanyUserDataTable;

use Maitiigor\FoundationCore\Controllers\BaseController;
use Maitiigor\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CompanyUserController extends BaseController
{
    /**
     * Display a listing of the CompanyUser.
     *
     * @param CompanyUserDataTable $companyUserDataTable
     * @return Response
     */
    public function index(Organization $org, CompanyUserDataTable $companyUserDataTable)
    {
        $current_user = Auth()->user();

        $cdv_company_users = new \Maitiigor\FoundationCore\View\Components\CardDataView(CompanyUser::class, "nda-hrm-module::pages.company_users.card_view_item");
        $cdv_company_users->setDataQuery(['organization_id'=>$org->id])
                        //->addDataGroup('label','field','value')
                        //->setSearchFields(['field_to_search1','field_to_search2'])
                        //->addDataOrder('display_ordinal','DESC')
                        //->addDataOrder('id','DESC')
                        ->enableSearch(true)
                        ->enablePagination(true)
                        ->setPaginationLimit(20)
                        ->setSearchPlaceholder('Search CompanyUser');

        if (request()->expectsJson()){
            return $cdv_company_users->render();
        }

        return view('nda-hrm-module::pages.company_users.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList())
                    ->with('cdv_company_users', $cdv_company_users);

        /*
        return $companyUserDataTable->render('nda-hrm-module::pages.company_users.index',[
            'current_user'=>$current_user,
            'months_list'=>BaseController::monthsList(),
            'states_list'=>BaseController::statesList()
        ]);
        */
    }

    /**
     * Show the form for creating a new CompanyUser.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('nda-hrm-module::pages.company_users.create');
    }

    /**
     * Store a newly created CompanyUser in storage.
     *
     * @param CreateCompanyUserRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateCompanyUserRequest $request)
    {
        $input = $request->all();

        /** @var CompanyUser $companyUser */
        $companyUser = CompanyUser::create($input);

        CompanyUserCreated::dispatch($companyUser);
        return redirect(route('lcc-platform-mgt.companyUsers.index'));
    }

    /**
     * Display the specified CompanyUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var CompanyUser $companyUser */
        $companyUser = CompanyUser::find($id);

        if (empty($companyUser)) {
            return redirect(route('lcc-platform-mgt.companyUsers.index'));
        }

        return view('nda-hrm-module::pages.company_users.show')
                            ->with('companyUser', $companyUser)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Show the form for editing the specified CompanyUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var CompanyUser $companyUser */
        $companyUser = CompanyUser::find($id);

        if (empty($companyUser)) {
            return redirect(route('lcc-platform-mgt.companyUsers.index'));
        }

        return view('nda-hrm-module::pages.company_users.edit')
                            ->with('companyUser', $companyUser)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Update the specified CompanyUser in storage.
     *
     * @param  int              $id
     * @param UpdateCompanyUserRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateCompanyUserRequest $request)
    {
        /** @var CompanyUser $companyUser */
        $companyUser = CompanyUser::find($id);

        if (empty($companyUser)) {
            return redirect(route('lcc-platform-mgt.companyUsers.index'));
        }

        $companyUser->fill($request->all());
        $companyUser->save();
        
        CompanyUserUpdated::dispatch($companyUser);
        return redirect(route('lcc-platform-mgt.companyUsers.index'));
    }

    /**
     * Remove the specified CompanyUser from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var CompanyUser $companyUser */
        $companyUser = CompanyUser::find($id);

        if (empty($companyUser)) {
            return redirect(route('lcc-platform-mgt.companyUsers.index'));
        }

        $companyUser->delete();

        CompanyUserDeleted::dispatch($companyUser);
        return redirect(route('lcc-platform-mgt.companyUsers.index'));
    }

        
    public function processBulkUpload(Organization $org, Request $request){

        $attachedFileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('uploads'), $attachedFileName);
        $path_to_file = public_path('uploads').'/'.$attachedFileName;

        //Process each line
        $loop = 1;
        $errors = [];
        $lines = file($path_to_file);

        if (count($lines) > 1) {
            foreach ($lines as $line) {
                
                if ($loop > 1) {
                    $data = explode(',', $line);
                    // if (count($invalids) > 0) {
                    //     array_push($errors, $invalids);
                    //     continue;
                    // }else{
                    //     //Check if line is valid
                    //     if (!$valid) {
                    //         $errors[] = $msg;
                    //     }
                    // }
                }
                $loop++;
            }
        }else{
            $errors[] = 'The uploaded csv file is empty';
        }
        
        if (count($errors) > 0) {
            return $this->sendError($this->array_flatten($errors), 'Errors processing file');
        }
        return $this->sendResponse($subject->toArray(), 'Bulk upload completed successfully');
    }
}
