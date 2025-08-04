<?php

use HRCore\Controllers\Dashboard\BirthdayController;
use Illuminate\Support\Facades\Route;
use Maitiigor\Payroll\Controllers\Dashboard\PayrollDashboardController;

/*
|--------------------------------------------------------------------------
| Payroll Module Routes
|--------------------------------------------------------------------------
|
| These routes are loaded within the Payroll.php routes() method
| which already provides the 'payroll.' name prefix and 'payroll' path prefix.
| Do not add additional prefixes or middleware groups here.
|
*/

// Check if Spatie Permission package is available
$usePermissions = class_exists(\Spatie\Permission\Models\Permission::class);

if ($usePermissions) {
    // Finance Employee Management (Role-based routes) - only if controller exists
    if (class_exists(\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class)) {
        Route::middleware(['permission:finance.employees.view'])->group(function () {
            Route::prefix('finance-employees')->name('finance-employees.')->group(function () {
                Route::get('/', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'index'])->name('index');
                Route::get('/{id}', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'show'])->name('show');
                Route::get('/{id}/edit-financial', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'editFinancial'])
                     ->middleware('permission:finance.employees.financial-edit')
                     ->name('edit-financial');
                Route::put('/{id}/update-financial', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'updateFinancial'])
                     ->middleware('permission:finance.employees.financial-edit')
                     ->name('update-financial');
                Route::get('/{id}/allowances-deductions', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'manageAllowancesDeductions'])
                     ->middleware('permission:finance.allowances.manage')
                     ->name('allowances-deductions');
                
                // Employee-Specific Allowances and Deductions Management
                Route::post('/{id}/add-allowance', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'addAllowance'])
                     ->middleware('permission:finance.allowances.manage')
                     ->name('add-allowance');
                Route::post('/{id}/add-deduction', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'addDeduction'])
                     ->middleware('permission:finance.deductions.manage')
                     ->name('add-deduction');
                Route::put('/{id}/update-excluded-allowances', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'updateExcludedAllowances'])
                     ->middleware('permission:finance.allowances.manage')
                     ->name('update-excluded-allowances');
                Route::delete('/{id}/remove-allowance/{allowanceId}', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'removeAllowance'])
                     ->middleware('permission:finance.allowances.manage')
                     ->name('remove-allowance');
                Route::delete('/{id}/remove-deduction/{deductionId}', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'removeDeduction'])
                     ->middleware('permission:finance.deductions.manage')
                     ->name('remove-deduction');
                
                // Bulk Financial Operations
                Route::post('/bulk-financial-update', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'bulkFinancialUpdate'])
                     ->middleware('permission:finance.employees.financial-edit')
                     ->name('bulk-financial-update');
                Route::get('/export-financial', [\Maitiigor\Payroll\Controllers\Dashboard\FinanceEmployeeController::class, 'exportFinancialData'])
                     ->middleware('permission:finance.reports.generate')
                     ->name('export-financial');

                     
            });
        });
    }

     // Dashboard Routes
     Route::get('/dashboard', [PayrollDashboardController::class, 'index'])->name('dashboard');
    
     // API Routes for Dashboard Data
     Route::get('/api/dashboard', [PayrollDashboardController::class, 'getDashboardData'])->name('api.dashboard');
     Route::get('/api/dashboard/widget', [PayrollDashboardController::class, 'getPayrollWidget'])->name('api.widget');

    // Legacy routes with role-based permissions
    Route::middleware(['permission:finance.salary-structures.view'])->group(function () {
        Route::resource('gradeLevels', \Maitiigor\Payroll\Controllers\Models\GradeLevelController::class);
        Route::resource('gradeLevelSteps', \Maitiigor\Payroll\Controllers\Models\GradeLevelStepController::class);
        Route::resource('salaryStructures', \Maitiigor\Payroll\Controllers\Models\SalaryStructureController::class);
    });

    Route::middleware(['permission:finance.employees.view'])->group(function () {
        Route::resource('employees', \Maitiigor\Payroll\Controllers\Models\EmployeeController::class);
        Route::get('employee/report', [\Maitiigor\Payroll\Controllers\Models\EmployeeController::class, 'showEmployeeReport'])->name('employee.report');
    });

    Route::middleware(['permission:finance.bank-details.view'])->group(function () {
        Route::resource('employeeBankDetails', \Maitiigor\Payroll\Controllers\Models\EmployeeBankDetailController::class);
    });

    Route::middleware(['permission:finance.allowances.view'])->group(function () {
        Route::resource('allowances', \Maitiigor\Payroll\Controllers\Models\AllowanceController::class);
        Route::resource('specialAllowances', \Maitiigor\Payroll\Controllers\Models\SpecialAllowanceController::class);
        Route::resource('allowanceTypes', \Maitiigor\Payroll\Controllers\Models\AllowanceTypeController::class);
    });

    Route::middleware(['permission:finance.bonuses.view'])->group(function () {
        Route::resource('bonuses', \Maitiigor\Payroll\Controllers\Models\BonusController::class);
        Route::resource('globalBonuses', \Maitiigor\Payroll\Controllers\Models\GlobalBonusController::class);
    });

    Route::middleware(['permission:finance.deductions.view'])->group(function () {
        Route::resource('deductions', \Maitiigor\Payroll\Controllers\Models\DeductionController::class);
        Route::resource('otherDeductionTypes', \Maitiigor\Payroll\Controllers\Models\OtherDeductionTypeController::class);
        Route::resource('otherDeductions', \Maitiigor\Payroll\Controllers\Models\OtherDeductionController::class);
        Route::resource('loanInstallments', \Maitiigor\Payroll\Controllers\Models\LoanInstallmentController::class);
        Route::resource('deductionTypes', \Maitiigor\Payroll\Controllers\Models\DeductionTypeController::class);
    });

    Route::middleware(['permission:finance.payroll.view'])->group(function () {
        Route::resource('payrollPeriods', \Maitiigor\Payroll\Controllers\Models\PayrollPeriodController::class);
        Route::get('payrollPeriod/{period}/export', [\Maitiigor\Payroll\Controllers\Models\PayrollPeriodController::class, 'exportPayroll'])
             ->middleware('permission:finance.reports.generate')
             ->name('payrollPeriod.export');
        Route::resource('payrolls', \Maitiigor\Payroll\Controllers\Models\PayrollController::class);
        Route::resource('payrollAllowances', \Maitiigor\Payroll\Controllers\Models\PayrollAllowanceController::class);
        Route::resource('payrollDeductions', \Maitiigor\Payroll\Controllers\Models\PayrollDeductionController::class);
        Route::resource('payrollBonuses', \Maitiigor\Payroll\Controllers\Models\PayrollBonusController::class);
        Route::resource('payrollGlobalBonuses', \Maitiigor\Payroll\Controllers\Models\PayrollGlobalBonusController::class);
    });

    // Enhanced Payroll Processing Routes
    Route::middleware(['permission:finance.payroll.process'])->group(function () {
        Route::prefix('processing')->name('processing.')->group(function () {
            Route::get('/', [\Maitiigor\Payroll\Controllers\Dashboard\PayrollProcessingController::class, 'index'])->name('index');
            Route::get('/setup/{payrollPeriod}', [\Maitiigor\Payroll\Controllers\Dashboard\PayrollProcessingController::class, 'setup'])->name('setup');
            Route::get('/view/{payrollPeriod}', [\Maitiigor\Payroll\Controllers\Dashboard\PayrollProcessingController::class, 'view'])->name('view');
            Route::post('/process/{payrollPeriod}', [\Maitiigor\Payroll\Controllers\Dashboard\PayrollProcessingController::class, 'processPayroll'])->name('process');
            Route::get('/progress/{payrollPeriod}', [\Maitiigor\Payroll\Controllers\Dashboard\PayrollProcessingController::class, 'getProgress'])->name('progress');
            Route::get('/export/{payrollPeriod}', [\Maitiigor\Payroll\Controllers\Dashboard\PayrollProcessingController::class, 'exportResults'])->name('export');
            Route::post('/validate/{payrollPeriod}', [\Maitiigor\Payroll\Controllers\Dashboard\PayrollProcessingController::class, 'validateResults'])->name('validate');
        });
    });

    // Salary Structure Rates Management
    Route::middleware(['permission:finance.salary-structures.manage'])->group(function () {
        Route::prefix('salary-structure-rates')->name('salary-structure-rates.')->group(function () {
            Route::get('/', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'index'])->name('index');
            Route::get('/setup/{salaryStructureId}', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'setup'])->name('setup');
            Route::get('/step-breakdown/{stepId}', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'stepBreakdown'])->name('step-breakdown');
            Route::get('/salary-breakdown/{stepId}', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'getSalaryBreakdown'])->name('salary-breakdown');
            Route::get('/rates-data', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'getRatesData'])->name('rates-data');
            
            // AJAX endpoints for rate management
            Route::post('/salary-rate', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'storeSalaryRate'])->name('store-salary-rate');
            Route::post('/allowance-rate', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'storeAllowanceRate'])->name('store-allowance-rate');
            Route::post('/deduction-rate', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'storeDeductionRate'])->name('store-deduction-rate');
            
            // Bulk operations
            Route::post('/bulk-update/{salaryStructureId}', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'bulkUpdate'])->name('bulk-update');
            
            // Import/Export
            Route::get('/export/{salaryStructureId}', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'export'])->name('export');
            Route::post('/import', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'import'])->name('import');
            
            // Rate management
            Route::delete('/rate', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'deleteRate'])->name('delete-rate');
        });
    });

    Route::middleware(['permission:finance.reports.view'])->group(function () {
        Route::get('reports', [\Maitiigor\Payroll\Controllers\Models\PayrollReportController::class, 'index'])
            ->name('reports.index');
        Route::get('reports/payroll', [\Maitiigor\Payroll\Controllers\Models\PayrollReportController::class, 'showPayrollReport'])
            ->name('reports.payroll');
    });



    // Bulk operations with permissions
    Route::middleware(['permission:finance.employees.financial-edit'])->group(function () {
        Route::post('employee/bulk-upload', [\Maitiigor\Payroll\Controllers\Models\EmployeeController::class, 'processBulkUpload'])->name('employee.bulk-upload');
    });

    Route::middleware(['permission:finance.deductions.manage'])->group(function () {
        Route::post('employee-deduction/bulk-upload', [\Maitiigor\Payroll\Controllers\Models\EmployeeController::class, 'processAdditionBulkUpload'])->name('employee-deduction.bulk-upload');
        Route::post('employee-deduction-removal/bulk-upload', [\Maitiigor\Payroll\Controllers\Models\EmployeeController::class, 'processRemoveBulkUpload'])->name('employee-deduction-removal.bulk-upload');
    });

    Route::middleware(['permission:finance.allowances.manage'])->group(function () {
        Route::post('allowance-exemption/bulk-upload', [\Maitiigor\Payroll\Controllers\Models\EmployeeController::class, 'processAllowanceExempt'])->name('allowance-exempt.bulk-upload');
    });

    // Fingerprint management
    Route::middleware(['permission:finance.employees.view'])->group(function () {
        Route::get('fingerprint/enrollment', [\Maitiigor\Payroll\Controllers\Models\FingerPrintController::class, 'index'])->name('fingerprints.enrollment');
        Route::post('fingerprint/enroll', [\Maitiigor\Payroll\Controllers\Models\FingerPrintController::class, 'enroll'])->name('fingerprint.enroll');
        Route::post('fingerprint/verify', [\Maitiigor\Payroll\Controllers\Models\FingerPrintController::class, 'verify'])->name('fingerprint.verify');
    });


    // employee payslip
    Route::get('employee/payslips', [\Maitiigor\Payroll\Controllers\Dashboard\EmployeePayrollController::class, 'index'])->name('employee.payslips');
    

} else {
    // Fallback routes without permissions (for when Spatie Permission is not available)
    Route::resource('gradeLevels', \Maitiigor\Payroll\Controllers\Models\GradeLevelController::class);
    Route::resource('gradeLevelSteps', \Maitiigor\Payroll\Controllers\Models\GradeLevelStepController::class);
    Route::resource('employees', \Maitiigor\Payroll\Controllers\Models\EmployeeController::class);
    Route::resource('employeeBankDetails', \Maitiigor\Payroll\Controllers\Models\EmployeeBankDetailController::class);
    Route::resource('allowances', \Maitiigor\Payroll\Controllers\Models\AllowanceController::class);
    Route::resource('bonuses', \Maitiigor\Payroll\Controllers\Models\BonusController::class);
    Route::resource('deductions', \Maitiigor\Payroll\Controllers\Models\DeductionController::class);
    Route::resource('globalBonuses', \Maitiigor\Payroll\Controllers\Models\GlobalBonusController::class);
    Route::resource('otherDeductionTypes', \Maitiigor\Payroll\Controllers\Models\OtherDeductionTypeController::class);
    Route::resource('otherDeductions', \Maitiigor\Payroll\Controllers\Models\OtherDeductionController::class);
    Route::resource('loanInstallments', \Maitiigor\Payroll\Controllers\Models\LoanInstallmentController::class);
    Route::resource('payrollPeriods', \Maitiigor\Payroll\Controllers\Models\PayrollPeriodController::class);
    Route::get('payrollPeriod/{period}/export', [\Maitiigor\Payroll\Controllers\Models\PayrollPeriodController::class, 'exportPayroll'])->name('payrollPeriod.export');
    Route::resource('payrolls', \Maitiigor\Payroll\Controllers\Models\PayrollController::class);
    Route::resource('payrollAllowances', \Maitiigor\Payroll\Controllers\Models\PayrollAllowanceController::class);
    Route::resource('payrollDeductions', \Maitiigor\Payroll\Controllers\Models\PayrollDeductionController::class);
    Route::resource('payrollBonuses', \Maitiigor\Payroll\Controllers\Models\PayrollBonusController::class);
    Route::resource('payrollGlobalBonuses', \Maitiigor\Payroll\Controllers\Models\PayrollGlobalBonusController::class);
    Route::resource('salaryStructures', \Maitiigor\Payroll\Controllers\Models\SalaryStructureController::class);
    Route::resource('specialAllowances', \Maitiigor\Payroll\Controllers\Models\SpecialAllowanceController::class);
    Route::resource('allowanceTypes', \Maitiigor\Payroll\Controllers\Models\AllowanceTypeController::class);
    Route::resource('deductionTypes', \Maitiigor\Payroll\Controllers\Models\DeductionTypeController::class);
    
    Route::get('employee/report', [\Maitiigor\Payroll\Controllers\Models\EmployeeController::class, 'showEmployeeReport'])->name('employee.report');
    Route::post('employee/bulk-upload', [\Maitiigor\Payroll\Controllers\Models\EmployeeController::class, 'processBulkUpload'])->name('employee.bulk-upload');
    Route::post('employee-deduction/bulk-upload', [\Maitiigor\Payroll\Controllers\Models\EmployeeController::class, 'processAdditionBulkUpload'])->name('employee-deduction.bulk-upload');
    Route::post('employee-deduction-removal/bulk-upload', [\Maitiigor\Payroll\Controllers\Models\EmployeeController::class, 'processRemoveBulkUpload'])->name('employee-deduction-removal.bulk-upload');
    Route::post('allowance-exemption/bulk-upload', [\Maitiigor\Payroll\Controllers\Models\EmployeeController::class, 'processAllowanceExempt'])->name('allowance-exempt.bulk-upload');
    
    Route::get('fingerprint/enrollment', [\Maitiigor\Payroll\Controllers\Models\FingerPrintController::class, 'index'])->name('fingerprints.enrollment');
    Route::post('fingerprint/enroll', [\Maitiigor\Payroll\Controllers\Models\FingerPrintController::class, 'enroll'])->name('fingerprint.enroll');
    Route::post('fingerprint/verify', [\Maitiigor\Payroll\Controllers\Models\FingerPrintController::class, 'verify'])->name('fingerprint.verify');
    
    // Salary Structure Rates Management (Fallback routes)
    Route::prefix('salary-structure-rates')->name('salary-structure-rates.')->group(function () {
        Route::get('/', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'index'])->name('index');
        Route::get('/setup/{salaryStructureId}', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'setup'])->name('setup');
        Route::get('/step-breakdown/{stepId}', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'stepBreakdown'])->name('step-breakdown');
        Route::get('/salary-breakdown/{stepId}', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'getSalaryBreakdown'])->name('salary-breakdown');
        Route::get('/rates-data', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'getRatesData'])->name('rates-data');
        Route::post('/salary-rate', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'storeSalaryRate'])->name('store-salary-rate');
        Route::post('/allowance-rate', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'storeAllowanceRate'])->name('store-allowance-rate');
        Route::post('/deduction-rate', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'storeDeductionRate'])->name('store-deduction-rate');
        Route::post('/bulk-update/{salaryStructureId}', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/export/{salaryStructureId}', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'export'])->name('export');
        Route::post('/import', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'import'])->name('import');
        Route::delete('/rate', [\Maitiigor\Payroll\Controllers\Dashboard\SalaryStructureRateController::class, 'deleteRate'])->name('delete-rate');
    });
}
