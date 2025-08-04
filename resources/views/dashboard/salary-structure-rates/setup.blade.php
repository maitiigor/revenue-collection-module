@extends('layouts.app')

@section('title_postfix')
    Setup Rates - {{ $salaryStructure->name }}
@stop

@section('page_title')
    Setup Rates
@stop

@section('page_title_suffix')
    <small class="text-muted">{{ $salaryStructure->name }}</small>
@stop

@section('app_css')
    @include('layouts.datatables_css')
    <style>
        /* Override the problematic CSS that breaks Bootstrap accordion */
        .accordion .collapse,
        .accordion-collapse.collapse {
            visibility: visible !important;
        }


        .accordion .collapse.show {
            display: block !important;
            visibility: visible !important;
        }

        .accordion .collapse:not(.show) {
            display: none !important;
        }
    </style>
@stop

@section('app_js')
    @include('layouts.datatables_js')
@stop

@section('page_title_subtext')
    <a class="ms-1" href="{{ route('dashboard') }}">
        <i class="bx bx-chevron-left"></i> Back to Dashboard
    </a>
    <span class="mx-2">•</span>
    <a href="{{ route('payroll.salary-structure-rates.index') }}">
        <i class="bx bx-money-bill-alt"></i> Salary Structure Rates
    </a>
@stop

@section('page_title_buttons')
    @if (auth()->user()->can('finance.salary-structures.manage'))
        <button type="button" class="btn btn-sm btn-success me-1" id="bulkUpdateBtn">
            <i class="fas fa-sync-alt me-1"></i>Bulk Update All
        </button>
        <button type="button" class="btn btn-sm btn-info me-1" id="importBtn">
            <i class="fas fa-upload me-1"></i>Import Rates
        </button>
        <button type="button" class="btn btn-sm btn-warning me-1" id="exportBtn">
            <i class="fas fa-download me-1"></i>Export Rates
        </button>
    @endif
@stop

@section('content')

    <!-- Statistics Row -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 bg-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Grade Levels</p>
                            <h4 class="my-1 text-white">{{ $salaryStructure->gradeLevels->count() }}</h4>
                        </div>
                        <div class="text-white ms-auto font-35">
                            <i class="bx bx-layer"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 bg-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Total Steps</p>
                            <h4 class="my-1 text-white">
                                {{ $salaryStructure->gradeLevels->sum(function ($l) {return $l->steps->count();}) }}</h4>
                        </div>
                        <div class="text-white ms-auto font-35">
                            <i class="bx bx-list-ol"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 bg-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Configured Rates</p>
                            @php
                                $configuredCount = 0;
                                foreach ($salaryStructure->gradeLevels as $level) {
                                    foreach ($level->steps as $step) {
                                        if (isset($existingRates['salary'][$step->id])) {
                                            $configuredCount++;
                                        }
                                    }
                                }
                            @endphp
                            <h4 class="my-1 text-white">{{ $configuredCount }}</h4>
                        </div>
                        <div class="text-white ms-auto font-35">
                            <i class="bx bx-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 bg-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">Completion</p>
                            @php
                                $totalSteps = $salaryStructure->gradeLevels->sum(function ($l) {
                                    return $l->steps->count();
                                });
                                $progress = $totalSteps > 0 ? round(($configuredCount / $totalSteps) * 100, 1) : 0;
                            @endphp
                            <h4 class="my-1 text-white">{{ $progress }}%</h4>
                        </div>
                        <div class="text-white ms-auto font-35">
                            <i class="bx bx-chart-pie"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grade Levels and Steps -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-layer-group me-2"></i>
                        Grade Levels Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="gradeLevelsAccordion">
                        @foreach ($salaryStructure->gradeLevels as $index => $gradeLevel)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $gradeLevel->id }}">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $gradeLevel->id }}"
                                        aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $gradeLevel->id }}">
                                        <div class="d-flex align-items-center w-100 me-3">
                                            <div class="flex-grow-1">
                                                <strong>{{ $gradeLevel->level }}</strong>
                                                <small class="text-muted ms-2">({{ $gradeLevel->steps->count() }}
                                                    steps)</small>
                                            </div>
                                            <div>
                                                @php
                                                    $configuredSteps = 0;
                                                    foreach ($gradeLevel->steps as $step) {
                                                        if (isset($existingRates['salary'][$step->id])) {
                                                            $configuredSteps++;
                                                        }
                                                    }
                                                    $progress =
                                                        $gradeLevel->steps->count() > 0
                                                            ? ($configuredSteps / $gradeLevel->steps->count()) * 100
                                                            : 0;
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $progress == 100 ? 'success' : ($progress > 0 ? 'warning' : 'secondary') }}">
                                                    {{ $configuredSteps }}/{{ $gradeLevel->steps->count() }} configured
                                                </span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $gradeLevel->id }}"
                                    class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $gradeLevel->id }}" data-bs-parent="#gradeLevelsAccordion">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Step</th>
                                                        <th>Base Salary</th>
                                                        <th>Allowances</th>
                                                        <th>Deductions</th>
                                                        <th>Net Salary</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($gradeLevel->steps as $step)
                                                        @php
                                                            $salaryRate = $existingRates['salary'][$step->id] ?? null;
                                                            $allowanceRates =
                                                                $existingRates['allowances'][$step->id] ?? collect();
                                                            $deductionRates =
                                                                $existingRates['deductions'][$step->id] ?? collect();

                                                            $baseSalary = $salaryRate ? $salaryRate->base_salary : 0;
                                                            $totalAllowances = $allowanceRates->sum(function (
                                                                $allowance,
                                                            ) use ($baseSalary) {
                                                                return $allowance->calculateAmount($baseSalary);
                                                            });
                                                            $grossSalary = $baseSalary + $totalAllowances;
                                                            $totalDeductions = $deductionRates->sum(function (
                                                                $deduction,
                                                            ) use ($baseSalary, $grossSalary) {
                                                                return $deduction->calculateAmount(
                                                                    $baseSalary,
                                                                    $grossSalary,
                                                                );
                                                            });
                                                            $netSalary = $grossSalary - $totalDeductions;
                                                        @endphp
                                                        <tr class="step-row" data-step-id="{{ $step->id }}">
                                                            <td>
                                                                <strong>Step {{ $step->step_number }}</strong>
                                                                @if ($step->description)
                                                                    <br><small
                                                                        class="text-muted">{{ $step->description }}</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($salaryRate)
                                                                    <span class="text-success fw-bold">NGN
                                                                        {{ number_format($baseSalary, 2) }}</span>
                                                                    <br><small class="text-muted">Effective:
                                                                        {{ $salaryRate->effective_date->format('Y-m-d') }}</small>
                                                                @else
                                                                    <span class="text-danger"><i
                                                                            class="fas fa-times me-1"></i>Not Set</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($allowanceRates->count() > 0)
                                                                    <span class="text-info fw-bold">NGN
                                                                        {{ number_format($totalAllowances, 2) }}</span>
                                                                    <br><small
                                                                        class="text-muted">{{ $allowanceRates->count() }}
                                                                        allowance(s)</small>
                                                                @else
                                                                    <span class="text-muted">None</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($deductionRates->count() > 0)
                                                                    <span class="text-warning fw-bold">NGN
                                                                        {{ number_format($totalDeductions, 2) }}</span>
                                                                    <br><small
                                                                        class="text-muted">{{ $deductionRates->count() }}
                                                                        deduction(s)</small>
                                                                @else
                                                                    <span class="text-muted">None</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($salaryRate)
                                                                    <span class="text-primary fw-bold">NGN
                                                                        {{ number_format($netSalary, 2) }}</span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($salaryRate)
                                                                    <span class="badge bg-success">
                                                                        <i class="fas fa-check me-1"></i>Configured
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-danger">
                                                                        <i class="fas fa-times me-1"></i>Pending
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="btn-group" role="group">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-primary setup-salary-btn"
                                                                        data-step-id="{{ $step->id }}"
                                                                        data-step-name="{{ $gradeLevel->level }} - Step {{ $step->step_number }}">
                                                                        <i class="fas fa-money-bill-alt"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-success setup-allowances-btn"
                                                                        data-step-id="{{ $step->id }}"
                                                                        data-step-name="{{ $gradeLevel->level }} - Step {{ $step->step_number }}">
                                                                        <i class="fas fa-plus-circle"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-warning setup-deductions-btn"
                                                                        data-step-id="{{ $step->id }}"
                                                                        data-step-name="{{ $gradeLevel->level }} - Step {{ $step->step_number }}">
                                                                        <i class="fas fa-minus-circle"></i>
                                                                    </button>
                                                                    <a href="{{ route('payroll.salary-structure-rates.step-breakdown', $step->id) }}"
                                                                        class="btn btn-sm btn-outline-info">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Setup Salary Modal -->
    <div class="modal fade" id="setupSalaryModal" tabindex="-1" aria-labelledby="setupSalaryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setupSalaryModalLabel">
                        <i class="fas fa-money-bill-alt me-2"></i>Setup Base Salary
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="salaryForm">
                    <div class="modal-body">
                        <input type="hidden" id="salaryStepId" name="grade_level_step_id">

                        <div class="mb-3">
                            <label for="salaryAmount" class="form-label">Base Salary Amount (NGN)</label>
                            <input type="number" class="form-control" id="salaryAmount" name="base_salary"
                                step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label for="salaryEffectiveDate" class="form-label">Effective Date</label>
                            <input type="date" class="form-control" id="salaryEffectiveDate" name="effective_date"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="salaryCurrency" class="form-label">Currency</label>
                            <select class="form-control" id="salaryCurrency" name="currency" required>
                                <option value="NGN">NGN (Nigerian Naira)</option>
                                <option value="USD">USD (US Dollar)</option>
                                <option value="EUR">EUR (Euro)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="salaryNotes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="salaryNotes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Salary Rate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Setup Allowances Modal -->
    <div class="modal fade" id="setupAllowancesModal" tabindex="-1" aria-labelledby="setupAllowancesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setupAllowancesModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Setup Allowances
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="allowanceForm">
                    <div class="modal-body">
                        <input type="hidden" id="allowanceStepId" name="grade_level_step_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="allowanceName" class="form-label">Allowance Name</label>
                                    <select class="form-control" id="allowanceName" name="name" required>
                                        <option value="">Select or type allowance name</option>
                                        <option value="Transport Allowance">Transport Allowance</option>
                                        <option value="Housing Allowance">Housing Allowance</option>
                                        <option value="Medical Allowance">Medical Allowance</option>
                                        <option value="Meal Allowance">Meal Allowance</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="allowanceType" class="form-label">Calculation Type</label>
                                    <select class="form-control" id="allowanceType" name="is_percentage" required>
                                        <option value="0">Fixed Amount</option>
                                        <option value="1">Percentage of Base Salary</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6" id="allowanceAmountField">
                                <div class="mb-3">
                                    <label for="allowanceAmount" class="form-label">Amount (NGN)</label>
                                    <input type="number" class="form-control" id="allowanceAmount" name="amount"
                                        step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6" id="allowancePercentageField" style="display: none;">
                                <div class="mb-3">
                                    <label for="allowancePercentage" class="form-label">Percentage (%)</label>
                                    <input type="number" class="form-control" id="allowancePercentage"
                                        name="percentage" step="0.01" min="0" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="isTaxable" name="is_taxable"
                                            value="1">
                                        <label class="form-check-label" for="isTaxable">
                                            Taxable Allowance
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="isEarned"
                                            name="is_earned_allowance" value="1">
                                        <label class="form-check-label" for="isEarned">
                                            Earned Allowance
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="allowanceEffectiveDate" class="form-label">Effective Date</label>
                            <input type="date" class="form-control" id="allowanceEffectiveDate" name="effective_date"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Allowance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Setup Deductions Modal -->
    <div class="modal fade" id="setupDeductionsModal" tabindex="-1" aria-labelledby="setupDeductionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setupDeductionsModalLabel">
                        <i class="fas fa-minus-circle me-2"></i>Setup Deductions
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deductionForm">
                    <div class="modal-body">
                        <input type="hidden" id="deductionStepId" name="grade_level_step_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deductionName" class="form-label">Deduction Name</label>
                                    <select class="form-control" id="deductionName" name="name" required>
                                        <option value="">Select or type deduction name</option>
                                        <option value="PAYE Tax">PAYE Tax</option>
                                        <option value="Pension Contribution">Pension Contribution</option>
                                        <option value="National Health Insurance">National Health Insurance</option>
                                        <option value="Union Dues">Union Dues</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deductionType" class="form-label">Deduction Type</label>
                                    <select class="form-control" id="deductionType" name="deduction_type" required>
                                        <option value="standard">Standard</option>
                                        <option value="statutory">Statutory</option>
                                        <option value="voluntary">Voluntary</option>
                                        <option value="tax">Tax</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deductionCalcType" class="form-label">Calculation Type</label>
                                    <select class="form-control" id="deductionCalcType" name="is_percentage" required>
                                        <option value="0">Fixed Amount</option>
                                        <option value="1">Percentage</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="isMandatory"
                                            name="is_mandatory" value="1">
                                        <label class="form-check-label" for="isMandatory">
                                            Mandatory Deduction
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6" id="deductionAmountField">
                                <div class="mb-3">
                                    <label for="deductionAmount" class="form-label">Amount (NGN)</label>
                                    <input type="number" class="form-control" id="deductionAmount" name="amount"
                                        step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6" id="deductionPercentageField" style="display: none;">
                                <div class="mb-3">
                                    <label for="deductionPercentage" class="form-label">Percentage (%)</label>
                                    <input type="number" class="form-control" id="deductionPercentage"
                                        name="percentage" step="0.01" min="0" max="100">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deductionEffectiveDate" class="form-label">Effective Date</label>
                            <input type="date" class="form-control" id="deductionEffectiveDate" name="effective_date"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Add Deduction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('side-panel')
    <div class="card radius-5 border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div>
                <h5 class="card-title">{{ $salaryStructure->name }}</h5>
            </div>
            <p class="small">
                Configure salary rates, allowances, and deductions for each grade level step.
                {{ $salaryStructure->description ?? 'Complete rate setup ensures accurate payroll processing.' }}
            </p>

            <div class="mt-3">
                <h6>Structure Overview:</h6>
                <ul class="small">
                    <li><strong>Code:</strong> {{ $salaryStructure->code ?? 'N/A' }}</li>
                    <li><strong>Grade Levels:</strong> {{ $salaryStructure->gradeLevels->count() }}</li>
                    <li><strong>Total Steps:</strong>
                        {{ $salaryStructure->gradeLevels->sum(function ($l) {return $l->steps->count();}) }}</li>
                    <li><strong>Status:</strong> {{ $salaryStructure->is_active ? 'Active' : 'Inactive' }}</li>
                </ul>
            </div>

            <div class="mt-3">
                <h6>Configuration Process:</h6>
                <ul class="small">
                    <li>Set base salary for each step</li>
                    <li>Configure standard allowances</li>
                    <li>Setup required deductions</li>
                    <li>Verify calculation accuracy</li>
                </ul>
            </div>
        </div>
    </div>
@stop


@push('page_scripts')
    <script type="module">
        $(document).ready(function() {
            // Set default effective date to today
            const today = new Date().toISOString().split('T')[0];
            $('#salaryEffectiveDate, #allowanceEffectiveDate, #deductionEffectiveDate').val(today);

            // Setup Salary Button Click
            $('.setup-salary-btn').on('click', function() {
                const stepId = $(this).data('step-id');
                const stepName = $(this).data('step-name');

                $('#salaryStepId').val(stepId);
                $('#setupSalaryModalLabel').text('Setup Base Salary - ' + stepName);
                $('#setupSalaryModal').modal('show');
            });

            // Setup Allowances Button Click
            $('.setup-allowances-btn').on('click', function() {
                const stepId = $(this).data('step-id');
                const stepName = $(this).data('step-name');

                $('#allowanceStepId').val(stepId);
                $('#setupAllowancesModalLabel').text('Setup Allowances - ' + stepName);
                $('#setupAllowancesModal').modal('show');
            });

            // Setup Deductions Button Click
            $('.setup-deductions-btn').on('click', function() {
                const stepId = $(this).data('step-id');
                const stepName = $(this).data('step-name');

                $('#deductionStepId').val(stepId);
                $('#setupDeductionsModalLabel').text('Setup Deductions - ' + stepName);
                $('#setupDeductionsModal').modal('show');
            });

            // Allowance Type Change
            $('#allowanceType').on('change', function() {
                if ($(this).val() === '1') {
                    $('#allowanceAmountField').hide();
                    $('#allowancePercentageField').show();
                    $('#allowanceAmount').removeAttr('required');
                    $('#allowancePercentage').attr('required', 'required');
                } else {
                    $('#allowanceAmountField').show();
                    $('#allowancePercentageField').hide();
                    $('#allowanceAmount').attr('required', 'required');
                    $('#allowancePercentage').removeAttr('required');
                }
            });

            // Deduction Type Change
            $('#deductionCalcType').on('change', function() {
                if ($(this).val() === '1') {
                    $('#deductionAmountField').hide();
                    $('#deductionPercentageField').show();
                    $('#deductionAmount').removeAttr('required');
                    $('#deductionPercentage').attr('required', 'required');
                } else {
                    $('#deductionAmountField').show();
                    $('#deductionPercentageField').hide();
                    $('#deductionAmount').attr('required', 'required');
                    $('#deductionPercentage').removeAttr('required');
                }
            });

            // Salary Form Submit
            $('#salaryForm').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('payroll.salary-structure-rates.store-salary-rate') }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            $('#setupSalaryModal').modal('hide');
                            location.reload();
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            let errorMessages = [];
                            Object.keys(errors).forEach(key => {
                                errors[key].forEach(error => {
                                    errorMessages.push(error);
                                });
                            });
                            Swal.fire({
                                title: 'Validation Errors!',
                                html: errorMessages.join('<br>'),
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while saving the salary rate.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });

            // Allowance Form Submit
            $('#allowanceForm').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('payroll.salary-structure-rates.store-allowance-rate') }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            $('#setupAllowancesModal').modal('hide');
                            location.reload();
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            let errorMessages = [];
                            Object.keys(errors).forEach(key => {
                                errors[key].forEach(error => {
                                    errorMessages.push(error);
                                });
                            });
                            Swal.fire({
                                title: 'Validation Errors!',
                                html: errorMessages.join('<br>'),
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while saving the allowance rate.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });

            // Deduction Form Submit
            $('#deductionForm').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('payroll.salary-structure-rates.store-deduction-rate') }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            $('#setupDeductionsModal').modal('hide');
                            location.reload();
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            let errorMessages = [];
                            Object.keys(errors).forEach(key => {
                                errors[key].forEach(error => {
                                    errorMessages.push(error);
                                });
                            });
                            Swal.fire({
                                title: 'Validation Errors!',
                                html: errorMessages.join('<br>'),
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while saving the deduction rate.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
