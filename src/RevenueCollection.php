<?php
namespace Maitiigor\Payroll;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maitiigor\FoundationCore\Models\User;

class RevenueCollection
{

    public function get_menu_map()
    {

        $current_user = Auth::user();

        $fc_menu = [];
        if ($current_user != null) {

            $organization = $current_user->organization;

            //if (\FoundationCore::has_feature('case-mgt', $organization)) {




            // if ($current_user->hasAnyRole(['admin', 'payroll-admin', 'hr-admin', 'hr-staff', 'payroll-staff', 'finance-admin', 'finance-officer', 'payroll-processor'])) {
            //     $fc_menu['mnu_payroll_employees'] = [

            //         'id' => 'mnu_payroll_employees',
            //         'label' => 'Employees',
            //         'icon' => 'bx bx-user-pin',
            //         'path' => route('payroll.employees.index'),
            //         'route-selector' => 'payroll/employees',
            //         'is-parent' => true,
            //         'children' => [],

            //     ];

            //     $fc_menu['mnu_payroll_employee_finger_enrollment'] = [

            //         'id' => 'mnu_payroll_employee_finger_enrollment',
            //         'label' => 'Employee Biometric Enrollment',
            //         'icon' => 'bx bx-fingerprint',
            //         'path' => route('payroll.fingerprints.enrollment'),
            //         'route-selector' => 'payroll/fingerprints/enrollment',
            //         'is-parent' => true,
            //         'children' => [],

            //     ];


            //     if ($current_user->hasAnyRole(['admin', 'payroll-admin', 'payroll-staff', 'finance-admin', 'finance-officer', 'payroll-processor'])) {

            //         $fc_menu['mnu_payroll_gradeLevels'] = [

            //             'id' => 'mnu_payroll_gradeLevels',
            //             'label' => 'Salary Structure & Policy',
            //             'icon' => 'bx bx-layer',
            //             'path' => route('payroll.salaryStructures.index'),
            //             'route-selector' => 'payroll/salaryStructures',
            //             'is-parent' => true,
            //             'children' => [],

            //         ];

            //         $fc_menu['mnu_payroll_special_deductions'] = [

            //             'id' => 'mnu_payroll_special_deductions',
            //             'label' => 'Other Deduction Types',
            //             'icon' => 'bx bxs-no-entry',
            //             'path' => route('payroll.otherDeductionTypes.index'),
            //             'route-selector' => 'payroll/otherDeductionTypes',
            //             'is-parent' => true,
            //             'children' => [],

            //         ];


            //         $fc_menu['mnu_payroll_payrolls'] = [

            //             'id' => 'mnu_payroll_payrolls',
            //             'label' => 'Payroll Slip',
            //             'icon' => 'bx bx-money',
            //             'path' => route('payroll.payrollPeriods.index'),
            //             'route-selector' => 'payroll/payrollPeriods',
            //             'is-parent' => true,
            //             'children' => [],

            //         ];
            //     }
            // }

            // Finance & Payroll Section
            if ($current_user->hasAnyPermission(['finance.employees.view', 'finance.payroll.view', 'finance.allowances.view', 'finance.deductions.view', 'manage-payroll'])) {

                // Finance & Payroll title
                $fc_menu['mnu_finance_payroll'] = [
                    'id' => 'mnu_finance_payroll',
                    'label' => 'Finance & Payroll',
                    'icon' => '',
                    'path' => '#',
                    'route-selector' => '',
                    'is-parent' => true,
                    'children' => []
                ];

                // Payroll Dashboard
                if ($current_user->can('finance.dashboard.view')) {
                    $fc_menu['mnu_payroll_dashboard'] = [
                        'id' => 'mnu_payroll_dashboard',
                        'label' => 'Payroll Dashboard',
                        'icon' => 'bx bx-tachometer',
                        'path' => route('payroll.dashboard'),
                        'route-selector' => 'payroll/dashboard',
                        'is-parent' => false,
                        'children' => []
                    ];
                }

                // Employee Financial Data
                if ($current_user->can('finance.employees.view')) {
                    $fc_menu['mnu_employee_financial'] = [
                        'id' => 'mnu_employee_financial',
                        'label' => 'Employee Financial Data',
                        'icon' => 'bx bx-dollar-circle',
                        'path' => '#',
                        'route-selector' => '',
                        'is-parent' => false,
                        'children' => [
                            'mnu_payroll_employees' => [
                                'id' => 'mnu_payroll_employees',
                                'label' => 'Payroll Employees',
                                'icon' => 'bx bx-right-arrow-alt',
                                'path' => route('payroll.finance-employees.index'),
                                'route-selector' => 'payroll/finance-employees',
                                'is-parent' => false,
                                'children' => []
                            ],
                        ]
                    ];

                 
                }

              
            }
        }

        return $fc_menu;
        // }

    }

    public function get_fc_menu_map()
    {
        $current_user = Auth::user();
        if ($current_user != null) {

            $fc_menu = [];

            if ($current_user->hasRole('admin')) {
                $fc_menu = [
                    'mnu_fc_admin' => [
                        'id' => 'mnu_fc_admin',
                        'label' => 'Administration',
                        'icon' => 'bx bx-abacus',
                        'path' => '#',
                        'route-selector' => '',
                        'is-parent' => true,
                        'children' => [],
                    ],
                ];





            }


            return $fc_menu;
        }

        return [];

    }

    public function api_routes()
    {
        Route::name('payroll-api.')->prefix('payroll-api')->group(function () {


        });

    }

    public function api_public_routes()
    {


    }

    public function public_routes()
    {
        Route::name('payroll.')->prefix('payroll')->group(function () {


        });
    }

    public function routes()
    {
        Route::name('payroll.')->prefix('payroll')->group(function () {
            require __DIR__ . '/routes/web.php';
        });
    }

}
