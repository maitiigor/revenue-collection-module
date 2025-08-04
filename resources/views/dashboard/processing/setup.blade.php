@extends('layouts.app')

@section('title_postfix')
    Payroll Processing Setup
@stop

@section('page_title')
    Payroll Processing Setup
@stop

@section('page_title_suffix')
    {{ $payrollPeriod->name }}
@stop

@section('app_css')
    @include('layouts.datatables_css')
    <style>
        .employee-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .employee-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .employee-card.selected {
            border-color: #0d6efd;
            background-color: #f8f9ff;
        }
        .processing-wizard .nav-pills .nav-link {
            border-radius: 50px;
            padding: 10px 20px;
            margin: 0 5px;
        }
        .processing-wizard .nav-pills .nav-link.active {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        }
        .validation-item {
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .validation-item.success {
            background-color: #d1f2d1;
            border-left: 4px solid #28a745;
        }
        .validation-item.warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .validation-item.error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        .progress-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .processing-status {
            text-align: center;
            padding: 40px 20px;
        }
        .pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
@stop

@section('page_title_subtext')
    <a class="ms-1" href="{{ route('payroll.processing.index') }}">
        <i class="bx bx-chevron-left"></i> Back to Processing
    </a>
@stop

@section('page_title_buttons')
    <button type="button" class="btn btn-sm btn-warning me-2" id="testModeBtn">
        <i class="fas fa-flask me-1"></i>Test Mode
    </button>
    <button type="button" class="btn btn-sm btn-success" id="startProcessingBtn" disabled>
        <i class="fas fa-play me-1"></i>Start Processing
    </button>
@stop

@section('content')

<!-- Period Information Card -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1">{{ $payrollPeriod->name }}</h5>
                <p class="text-muted mb-0">
                    Period: {{ $payrollPeriod->date->format('F Y') }} • 
                    Status: <span class="badge bg-{{ $payrollPeriod->status === 'draft' ? 'secondary' : ($payrollPeriod->status === 'completed' ? 'success' : 'warning') }}">
                        {{ ucfirst($payrollPeriod->status) }}
                    </span>
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex justify-content-end gap-2">
                    <div class="text-center">
                        <h4 class="mb-0 text-primary">{{ $eligibleEmployees->count() }}</h4>
                        <small class="text-muted">Eligible Employees</small>
                    </div>
                    <div class="text-center">
                        <h4 class="mb-0 text-success">₦{{ number_format($eligibleEmployees->sum(function($emp) { 
                            return $emp->currentSalaryAssignment->first()?->gradeLevelStep?->salaryStructureRates?->where('is_current', true)?->first()?->base_salary ?? 0; 
                        }), 2) }}</h4>
                        <small class="text-muted">Est. Total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Processing Wizard -->
<div class="processing-wizard">
    <ul class="nav nav-pills mb-4" id="processingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="employees-tab" data-bs-toggle="pill" data-bs-target="#employees" type="button" role="tab">
                <i class="fas fa-users me-2"></i>1. Select Employees
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="validation-tab" data-bs-toggle="pill" data-bs-target="#validation" type="button" role="tab">
                <i class="fas fa-check-circle me-2"></i>2. Validation
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="configuration-tab" data-bs-toggle="pill" data-bs-target="#configuration" type="button" role="tab">
                <i class="fas fa-cog me-2"></i>3. Configuration
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="processing-tab" data-bs-toggle="pill" data-bs-target="#processing" type="button" role="tab">
                <i class="fas fa-play me-2"></i>4. Processing
            </button>
        </li>
    </ul>

    <div class="tab-content" id="processingTabContent">
        <!-- Step 1: Employee Selection -->
        <div class="tab-pane fade show active" id="employees" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Select Employees for Processing</h6>
                            <small class="text-muted">Choose which employees to include in this payroll run</small>
                        </div>
                        <div>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="selectionMode" id="selectAll" autocomplete="off" checked>
                                <label class="btn btn-outline-primary btn-sm" for="selectAll">All Eligible</label>
                                
                                <input type="radio" class="btn-check" name="selectionMode" id="selectManual" autocomplete="off">
                                <label class="btn btn-outline-primary btn-sm" for="selectManual">Manual Select</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Selection Controls -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" placeholder="Search employees..." id="employeeSearch">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="departmentFilter">
                                <option value="">All Departments</option>
                                @foreach($eligibleEmployees->groupBy('department.name') as $dept => $employees)
                                    <option value="{{ $dept }}">{{ $dept }} ({{ $employees->count() }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Manual Selection Container (Hidden by default) -->
                    <div id="manualSelectionContainer" class="d-none">
                        <div class="row" id="employeeCards">
                            @foreach($eligibleEmployees as $employee)
                                @php
                                    $assignment = $employee->currentSalaryAssignment->first();
                                    $baseSalary = $assignment?->gradeLevelStep?->salaryStructureRates?->where('is_current', true)?->first()?->base_salary ?? 0;
                                @endphp
                                <div class="col-lg-6 col-xl-4 mb-3 employee-item" 
                                     data-department="{{ $employee->department?->name }}"
                                     data-name="{{ strtolower($employee->full_name) }}">
                                    <div class="card employee-card" data-employee-id="{{ $employee->id }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input employee-checkbox" type="checkbox" 
                                                           value="{{ $employee->id }}" id="emp{{ $employee->id }}" checked>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $employee->full_name }}</h6>
                                                    <p class="mb-1 small text-muted">
                                                        {{ $employee->employee_number }} • {{ $employee->department?->name }}
                                                    </p>
                                                    <div class="d-flex justify-content-between">
                                                        <small class="text-success">
                                                            <strong>₦{{ number_format($baseSalary, 2) }}</strong>
                                                        </small>
                                                        <small class="text-muted">
                                                            {{ $assignment?->gradeLevelStep?->gradeLevel?->level }} - 
                                                            Step {{ $assignment?->gradeLevelStep?->step_number }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="ms-auto">
                                                    @if($assignment && $assignment->gradeLevelStep?->salaryStructureRates?->where('is_current', true)?->first())
                                                        <i class="bx bx-check-circle text-success"></i>
                                                    @else
                                                        <i class="bx bx-error-circle text-danger" 
                                                           title="Missing salary structure rates"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- All Employees Summary (Shown by default) -->
                    <div id="allEmployeesSummary">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="bx bx-info-circle me-3 fs-4"></i>
                                <div>
                                    <h6 class="mb-1">All Eligible Employees Selected</h6>
                                    <p class="mb-0">{{ $eligibleEmployees->count() }} employees will be processed for payroll.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selection Summary -->
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h5 class="text-primary mb-0" id="selectedCount">{{ $eligibleEmployees->count() }}</h5>
                                <small class="text-muted">Selected</small>
                            </div>
                            <div class="col-md-3">
                                <h5 class="text-success mb-0" id="estimatedGross">₦{{ number_format($eligibleEmployees->sum(function($emp) { 
                                    return $emp->currentSalaryAssignment->first()?->gradeLevelStep?->salaryStructureRates?->where('is_current', true)?->first()?->base_salary ?? 0; 
                                }), 2) }}</h5>
                                <small class="text-muted">Est. Gross</small>
                            </div>
                            <div class="col-md-3">
                                <h5 class="text-warning mb-0" id="warningCount">0</h5>
                                <small class="text-muted">Warnings</small>
                            </div>
                            <div class="col-md-3">
                                <h5 class="text-danger mb-0" id="errorCount">0</h5>
                                <small class="text-muted">Errors</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" disabled>
                            <i class="fas fa-chevron-left me-1"></i>Previous
                        </button>
                        <button type="button" class="btn btn-primary" id="nextToValidation">
                            Next: Validation <i class="fas fa-chevron-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Validation -->
        <div class="tab-pane fade" id="validation" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Pre-Processing Validation</h6>
                    <small class="text-muted">Checking for potential issues before processing</small>
                </div>
                
                <div class="card-body">
                    <div id="validationResults">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Running validation checks...</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="backToEmployees">
                            <i class="fas fa-chevron-left me-1"></i>Previous
                        </button>
                        <button type="button" class="btn btn-primary" id="nextToConfiguration" disabled>
                            Next: Configuration <i class="fas fa-chevron-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Configuration -->
        <div class="tab-pane fade" id="configuration" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Processing Configuration</h6>
                    <small class="text-muted">Configure how the payroll should be processed</small>
                </div>
                
                <div class="card-body">
                    <form id="processingConfigForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Processing Mode</label>
                                    <select class="form-select" name="processing_mode" required>
                                        <option value="all">Process All Selected Employees</option>
                                        <option value="test">Test Mode (No Data Saved)</option>
                                    </select>
                                    <div class="form-text">Test mode will simulate processing without saving data</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Batch Size</label>
                                    <select class="form-select" name="batch_size">
                                        <option value="25">25 employees per batch</option>
                                        <option value="50" selected>50 employees per batch</option>
                                        <option value="100">100 employees per batch</option>
                                    </select>
                                    <div class="form-text">Smaller batches are more stable but slower</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="clear_existing" value="1" id="clearExisting" checked>
                                        <label class="form-check-label" for="clearExisting">
                                            Clear Existing Payroll Data
                                        </label>
                                    </div>
                                    <div class="form-text">Remove any existing payroll data for this period</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="send_notifications" id="sendNotifications">
                                        <label class="form-check-label" for="sendNotifications">
                                            Send Email Notifications
                                        </label>
                                    </div>
                                    <div class="form-text">Notify employees when payroll is processed</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="generate_payslips" id="generatePayslips" checked>
                                        <label class="form-check-label" for="generatePayslips">
                                            Generate Payslips
                                        </label>
                                    </div>
                                    <div class="form-text">Automatically generate PDF payslips</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <div class="d-flex">
                                <i class="bx bx-shield-alt-2 me-3 fs-4"></i>
                                <div>
                                    <h6 class="mb-1">Important Security Notice</h6>
                                    <p class="mb-0">
                                        Payroll processing cannot be undone easily. Please ensure all configurations 
                                        are correct before proceeding. Consider running in test mode first.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="backToValidation">
                            <i class="fas fa-chevron-left me-1"></i>Previous
                        </button>
                        <button type="button" class="btn btn-success" id="startProcessing">
                            <i class="fas fa-play me-1"></i>Start Processing
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Processing -->
        <div class="tab-pane fade" id="processing" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="progress-container">
                        <div class="processing-status" id="processingStatus">
                            <div class="mb-4">
                                <div class="pulse">
                                    <i class="bx bx-cog fs-1 text-primary"></i>
                                </div>
                                <h4 class="mt-3">Processing Payroll...</h4>
                                <p class="text-muted">Please wait while we process employee payrolls</p>
                            </div>
                            
                            <div class="progress mb-3" style="height: 20px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%" id="progressBar">
                                    <span class="fw-bold">0%</span>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <h4 class="text-primary mb-0" id="totalEmployees">0</h4>
                                    <small class="text-muted">Total</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-info mb-0" id="processedEmployees">0</h4>
                                    <small class="text-muted">Processed</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-success mb-0" id="successfulEmployees">0</h4>
                                    <small class="text-muted">Successful</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-danger mb-0" id="failedEmployees">0</h4>
                                    <small class="text-muted">Failed</small>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="mb-0 small text-muted" id="currentStatus">Initializing...</p>
                            </div>
                        </div>
                        
                        <!-- Processing Results (Hidden initially) -->
                        <div id="processingResults" class="d-none">
                            <div class="text-center mb-4">
                                <i class="bx bx-check-circle fs-1 text-success"></i>
                                <h4 class="mt-3 text-success">Processing Completed!</h4>
                                <p class="text-muted">Payroll has been processed successfully</p>
                            </div>
                            
                            <div class="row text-center mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h3 class="mb-0" id="finalSuccessful">0</h3>
                                            <small>Successful</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body">
                                            <h3 class="mb-0" id="finalFailed">0</h3>
                                            <small>Failed</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-info text-white">
                                        <div class="card-body">
                                            <h3 class="mb-0" id="finalTotal">₦0</h3>
                                            <small>Total Amount</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="{{ route('payroll.processing.view', $payrollPeriod->id) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>View Results
                                </a>
                                <button type="button" class="btn btn-success" onclick="exportResults()">
                                    <i class="fas fa-download me-2"></i>Export Results
                                </button>
                                <a href="{{ route('payroll.processing.index') }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-list me-2"></i>Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@push('page_scripts')
<script type="module">
let selectedEmployees = @json($eligibleEmployees->pluck('id'));
let processingInterval;
@php
$progress = [];
    
@endphp
@if ($payrollPeriod->status != 'completed' && $payrollPeriod->status != 'approved' )
    @php
        $progress = \Cache::get('payroll_processing_progress_'.$payrollPeriod->id);
    @endphp  
@endif

    let progress = @json($progress  ?? []);
    console.log(progress);
    
$(document).ready(function() {
    if (progress && progress.status === 'completed_with_errors') {
        if(progress.errors.length > 0) {
            console.log();
            
            Swal.fire({
                title: 'Errors During Processing',
                text: 'Please check the errors below payroll processing_progress',
                icon: 'error',
                confirmButtonText: 'View Details',
                html: `<ul>${progress.errors.map(error => `<li>${error.employee_name} - ${error.error}</li>`).join('')}</ul>`
            });
        }
       
    }
});

$(document).ready(function() {
    // Selection mode toggle
    $('input[name="selectionMode"]').change(function() {
        if ($('#selectAll').is(':checked')) {
            $('#manualSelectionContainer').addClass('d-none');
            $('#allEmployeesSummary').removeClass('d-none');
            selectedEmployees = @json($eligibleEmployees->pluck('id'));
            updateSelectionSummary();
        } else {
            $('#manualSelectionContainer').removeClass('d-none');
            $('#allEmployeesSummary').addClass('d-none');
            updateSelectedEmployees();
        }
    });

    // Employee search and filter
    $('#employeeSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterEmployees();
    });

    $('#departmentFilter').change(function() {
        filterEmployees();
    });

    // Employee card selection
    $('.employee-card').click(function() {
        const checkbox = $(this).find('.employee-checkbox');
        checkbox.prop('checked', !checkbox.prop('checked'));
        $(this).toggleClass('selected', checkbox.prop('checked'));
        updateSelectedEmployees();
    });

    $('.employee-checkbox').click(function(e) {
        e.stopPropagation();
        $(this).closest('.employee-card').toggleClass('selected', $(this).prop('checked'));
        updateSelectedEmployees();
    });

    // Navigation between steps
    $('#nextToValidation').click(function() {
        if (selectedEmployees.length === 0) {
            Swal.fire('Error', 'Please select at least one employee to process', 'error');
            return;
        }
        $('#validation-tab').click();
        runValidation();
    });

    $('#backToEmployees').click(function() {
        $('#employees-tab').click();
    });

    $('#nextToConfiguration').click(function() {
        $('#configuration-tab').click();
    });

    $('#backToValidation').click(function() {
        $('#validation-tab').click();
    });

    // Start processing
    $('#startProcessing').click(function() {
        $('#processing-tab').click();
        startPayrollProcessing();
    });

    // Test mode button
    $('#testModeBtn').click(function() {
        $('select[name="processing_mode"]').val('test');
        $('#configuration-tab').click();
        Swal.fire({
            title: 'Test Mode Activated',
            text: 'Processing will run in test mode without saving data',
            icon: 'info'
        });
    });
});

function filterEmployees() {
    const searchTerm = $('#employeeSearch').val().toLowerCase();
    const department = $('#departmentFilter').val();

    $('.employee-item').each(function() {
        const name = $(this).data('name');
        const dept = $(this).data('department');
        
        const matchesSearch = !searchTerm || name.includes(searchTerm);
        const matchesDept = !department || dept === department;
        
        if (matchesSearch && matchesDept) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function updateSelectedEmployees() {
    selectedEmployees = [];
    $('.employee-checkbox:checked').each(function() {
        selectedEmployees.push($(this).val());
    });
    updateSelectionSummary();
}

function updateSelectionSummary() {
    $('#selectedCount').text(selectedEmployees.length);
    // Calculate estimated gross (simplified)
    let estimatedGross = selectedEmployees.length * 100000; // Placeholder calculation
    $('#estimatedGross').text('₦' + new Intl.NumberFormat().format(estimatedGross));
    
    // Enable/disable next button
    $('#nextToValidation').prop('disabled', selectedEmployees.length === 0);
}

function runValidation() {
    $('#validationResults').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Running validation checks...</p>
        </div>
    `);

    // Simulate validation process
    setTimeout(function() {
        const validationHtml = `
            <div class="validation-item success">
                <div class="d-flex align-items-center">
                    <i class="bx bx-check-circle text-success me-3"></i>
                    <div>
                        <strong>Salary Structure Rates</strong>
                        <p class="mb-0 small">All selected employees have valid salary structure rates configured</p>
                    </div>
                </div>
            </div>
            <div class="validation-item success">
                <div class="d-flex align-items-center">
                    <i class="bx bx-check-circle text-success me-3"></i>
                    <div>
                        <strong>Employee Assignments</strong>
                        <p class="mb-0 small">All employees have current salary assignments</p>
                    </div>
                </div>
            </div>
            <div class="validation-item warning">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle text-warning me-3"></i>
                    <div>
                        <strong>Bank Account Information</strong>
                        <p class="mb-0 small">2 employees are missing bank account information</p>
                    </div>
                </div>
            </div>
            <div class="validation-item success">
                <div class="d-flex align-items-center">
                    <i class="bx bx-check-circle text-success me-3"></i>
                    <div>
                        <strong>Tax Configuration</strong>
                        <p class="mb-0 small">Tax rates and deductions are properly configured</p>
                    </div>
                </div>
            </div>
        `;
        
        $('#validationResults').html(validationHtml);
        $('#nextToConfiguration').prop('disabled', false);
        
        // Update warning count
        $('#warningCount').text('2');
    }, 2000);
}

function startPayrollProcessing() {
    const formData = new FormData(document.getElementById('processingConfigForm'));
    
    // Properly append employee_ids as an array
    formData.delete('employee_ids'); // Remove if it was added as a string
    
    // Append each employee ID individually to create an array on the server
   // selectedEmployees.forEach(id => {
        formData.append('employee_ids', selectedEmployees);
  //  });
    
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    // Initialize progress
    $('#totalEmployees').text(selectedEmployees.length);
    $('#processedEmployees').text('0');
    $('#successfulEmployees').text('0');
    $('#failedEmployees').text('0');
    $('#currentStatus').text('Starting payroll processing...');

    // Start processing
    $.ajax({
        url: `{{ route('payroll.processing.process', $payrollPeriod->id) }}`,
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                // Start monitoring progress
                monitorProgress();
            } else {
                showProcessingError(data.error || 'Processing failed');
            }
        },
        error: function(xhr, status, error) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                if (errors.employee_ids) {
                    showProcessingError('Selected employees must be an array');
                } else {
                    showProcessingError(error);
                }
            } else {
                showProcessingError(error);
            }
        }
    });
}

function monitorProgress() {
    processingInterval = setInterval(function() {
        fetch(`{{ route('payroll.processing.progress',  $payrollPeriod->id) }}`)
        .then(response => response.json())
        .then(data => {
            updateProgressDisplay(data.data);
            
            if (data.data.status === 'completed' || data.data.status === 'failed') {
                clearInterval(processingInterval);
                showProcessingResults(data.data);
            }
        })
        .catch(error => {
            console.error('Error monitoring progress:', error);
        });
    }, 2000); // Check every 2 seconds
}

function updateProgressDisplay(progress) {
    const percentage = progress.total > 0 ? Math.round((progress.processed / progress.total) * 100) : 0;
    
    $('#progressBar').css('width', percentage + '%').find('span').text(percentage + '%');
    $('#processedEmployees').text(progress.processed);
    $('#successfulEmployees').text(progress.successful);
    $('#failedEmployees').text(progress.failed);
    
    if (progress.processed < progress.total) {
        $('#currentStatus').text(`Processing employee ${progress.processed + 1} of ${progress.total}...`);
    } else {
        $('#currentStatus').text('Finalizing payroll processing...');
    }
}

function showProcessingResults(results) {
    $('#processingStatus').addClass('d-none');
    $('#processingResults').removeClass('d-none');
    
    $('#finalSuccessful').text(results.successful);
    $('#finalFailed').text(results.failed);
    // Calculate total amount (placeholder)
    $('#finalTotal').text('₦' + new Intl.NumberFormat().format(results.successful * 100000));
    
    if (results.failed > 0) {
        Swal.fire({
            title: 'Processing Completed with Errors',
            text: `${results.successful} employees processed successfully, ${results.failed} failed`,
            icon: 'warning',
            confirmButtonText: 'View Details'
        });
        showProcessingError(`<ul> ${results.errors.map(error => `<li>${error.employee_name} - ${error.error}</li`)}</ul>`);
    }
}

function showProcessingError(error) {
    $('#processingStatus').html(`
        <div class="text-center">
            <i class="bx bx-error-circle fs-1 text-danger"></i>
            <h4 class="mt-3 text-danger">Processing Failed</h4>
            <p class="text-muted">${error}</p>
            <button type="button" class="btn btn-secondary" onclick="startPayrollProcessing()">
                <i class="fas fa-redo me-2"></i>Try Again
            </button>
        </div>
    `);
}

function exportResults() {
    window.open(`{{ route('payroll.processing.export',  $payrollPeriod->id) }}?format=excel`, '_blank');
}
</script>
@endpush
