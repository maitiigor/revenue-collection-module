<?php

namespace Maitiigor\RC\Controllers\Models;

use Maitiigor\RC\Models\ComplianceReport;

use Maitiigor\RC\Events\ComplianceReportCreated;
use Maitiigor\RC\Events\ComplianceReportUpdated;
use Maitiigor\RC\Events\ComplianceReportDeleted;

use Maitiigor\RC\Requests\CreateComplianceReportRequest;
use Maitiigor\RC\Requests\UpdateComplianceReportRequest;

use Maitiigor\RC\DataTables\ComplianceReportDataTable;

use Maitiigor\FoundationCore\Controllers\BaseController;
use Maitiigor\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ComplianceReportController extends BaseController
{
    /**
     * Display a listing of the ComplianceReport.
     *
     * @param ComplianceReportDataTable $complianceReportDataTable
     * @return Response
     */
    public function index(Organization $org, ComplianceReportDataTable $complianceReportDataTable)
    {
        $current_user = Auth()->user();

        $cdv_compliance_reports = new \Maitiigor\FoundationCore\View\Components\CardDataView(ComplianceReport::class, "nda-hrm-module::pages.compliance_reports.card_view_item");
        $cdv_compliance_reports->setDataQuery(['organization_id'=>$org->id])
                        //->addDataGroup('label','field','value')
                        //->setSearchFields(['field_to_search1','field_to_search2'])
                        //->addDataOrder('display_ordinal','DESC')
                        //->addDataOrder('id','DESC')
                        ->enableSearch(true)
                        ->enablePagination(true)
                        ->setPaginationLimit(20)
                        ->setSearchPlaceholder('Search ComplianceReport');

        if (request()->expectsJson()){
            return $cdv_compliance_reports->render();
        }

        return view('nda-hrm-module::pages.compliance_reports.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList())
                    ->with('cdv_compliance_reports', $cdv_compliance_reports);

        /*
        return $complianceReportDataTable->render('nda-hrm-module::pages.compliance_reports.index',[
            'current_user'=>$current_user,
            'months_list'=>BaseController::monthsList(),
            'states_list'=>BaseController::statesList()
        ]);
        */
    }

    /**
     * Show the form for creating a new ComplianceReport.
     *
     * @return Response
     */
    public function create(Organization $org)
    {
        return view('nda-hrm-module::pages.compliance_reports.create');
    }

    /**
     * Store a newly created ComplianceReport in storage.
     *
     * @param CreateComplianceReportRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateComplianceReportRequest $request)
    {
        $input = $request->all();

        /** @var ComplianceReport $complianceReport */
        $complianceReport = ComplianceReport::create($input);

        ComplianceReportCreated::dispatch($complianceReport);
        return redirect(route('lcc-platform-mgt.complianceReports.index'));
    }

    /**
     * Display the specified ComplianceReport.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var ComplianceReport $complianceReport */
        $complianceReport = ComplianceReport::find($id);

        if (empty($complianceReport)) {
            return redirect(route('lcc-platform-mgt.complianceReports.index'));
        }

        return view('nda-hrm-module::pages.compliance_reports.show')
                            ->with('complianceReport', $complianceReport)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Show the form for editing the specified ComplianceReport.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        $current_user = Auth()->user();

        /** @var ComplianceReport $complianceReport */
        $complianceReport = ComplianceReport::find($id);

        if (empty($complianceReport)) {
            return redirect(route('lcc-platform-mgt.complianceReports.index'));
        }

        return view('nda-hrm-module::pages.compliance_reports.edit')
                            ->with('complianceReport', $complianceReport)
                            ->with('current_user', $current_user)
                            ->with('months_list', BaseController::monthsList())
                            ->with('states_list', BaseController::statesList());
    }

    /**
     * Update the specified ComplianceReport in storage.
     *
     * @param  int              $id
     * @param UpdateComplianceReportRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateComplianceReportRequest $request)
    {
        /** @var ComplianceReport $complianceReport */
        $complianceReport = ComplianceReport::find($id);

        if (empty($complianceReport)) {
            return redirect(route('lcc-platform-mgt.complianceReports.index'));
        }

        $complianceReport->fill($request->all());
        $complianceReport->save();
        
        ComplianceReportUpdated::dispatch($complianceReport);
        return redirect(route('lcc-platform-mgt.complianceReports.index'));
    }

    /**
     * Remove the specified ComplianceReport from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id)
    {
        /** @var ComplianceReport $complianceReport */
        $complianceReport = ComplianceReport::find($id);

        if (empty($complianceReport)) {
            return redirect(route('lcc-platform-mgt.complianceReports.index'));
        }

        $complianceReport->delete();

        ComplianceReportDeleted::dispatch($complianceReport);
        return redirect(route('lcc-platform-mgt.complianceReports.index'));
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
