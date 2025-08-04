<?php

namespace Maitiigor\RC\Controllers\Models;

use Maitiigor\RC\Models\Company;

use Maitiigor\RC\Events\CompanyCreated;
use Maitiigor\RC\Events\CompanyUpdated;
use Maitiigor\RC\Events\CompanyDeleted;

use Maitiigor\RC\Requests\CreateCompanyRequest;
use Maitiigor\RC\Requests\UpdateCompanyRequest;

use Maitiigor\RC\DataTables\CompanyDataTable;

use Maitiigor\FoundationCore\Controllers\BaseController;
use Maitiigor\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CompanyController extends BaseController
{
    /**
     * Display a listing of the Company.
     *
     * @param CompanyDataTable $companyDataTable
     * @return Response
     */
    public function index(Organization $org, CompanyDataTable $companyDataTable)
    {
        $current_user = Auth()->user();

        $cdv_companies = new \Maitiigor\FoundationCore\View\Components\CardDataView(Company::class, "nda-hrm-module::pages.companies.card_view_item");
        $cdv_companies->setDataQuery(['organization_id'=>$org->id])
                        //->addDataGroup('label','field','value')
                        //->setSearchFields(['field_to_search1','field_to_search2'])
                        //->addDataOrder('display_ordinal','DESC')
                        //->addDataOrder('id','DESC')
                        ->enableSearch(true)
                        ->enablePagination(true)
                        ->setPaginationLimit(20)
                        ->setSearchPlaceholder('Search Company');

        if (request()->expectsJson()){
            return $cdv_companies->render();
        }

        return view('nda-hrm-module::pages.companies.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList())
                    ->with('cdv_companies', $cdv_companies);

        /*
        return $companyDataTable->render('nda-hrm-module::pages.companies.index',[
            'current_user'=>$current_user,
            'months_list'=>BaseController::monthsList(),
            'states_list'=>BaseController::statesList()
        ]);
        */
    }

    /**
     * Show the form for creating a new Company.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('nda-hrm-module::pages.companies.create');
    }

    /**
     * Store a newly created Company in storage.
     *
     * @param CreateCompanyRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateCompanyRequest $request)
    {
        $input = $request->all();

        /** @var Company $company */
        $company = Company::create($input);

        CompanyCreated::dispatch($company);
        return redirect(route('lcc-platform-mgt.companies.index'));
    }

    /**
     * Display the specified Company.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var Company $company */
        $company = Company::find($id);

        if (empty($company)) {
            return redirect(route('lcc-platform-mgt.companies.index'));
        }

        return view('nda-hrm-module::pages.companies.show')
                            ->with('company', $company)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Show the form for editing the specified Company.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var Company $company */
        $company = Company::find($id);

        if (empty($company)) {
            return redirect(route('lcc-platform-mgt.companies.index'));
        }

        return view('nda-hrm-module::pages.companies.edit')
                            ->with('company', $company)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Update the specified Company in storage.
     *
     * @param  int              $id
     * @param UpdateCompanyRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateCompanyRequest $request)
    {
        /** @var Company $company */
        $company = Company::find($id);

        if (empty($company)) {
            return redirect(route('lcc-platform-mgt.companies.index'));
        }

        $company->fill($request->all());
        $company->save();
        
        CompanyUpdated::dispatch($company);
        return redirect(route('lcc-platform-mgt.companies.index'));
    }

    /**
     * Remove the specified Company from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var Company $company */
        $company = Company::find($id);

        if (empty($company)) {
            return redirect(route('lcc-platform-mgt.companies.index'));
        }

        $company->delete();

        CompanyDeleted::dispatch($company);
        return redirect(route('lcc-platform-mgt.companies.index'));
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
