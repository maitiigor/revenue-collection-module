@extends('layouts.app')

@section('title_postfix')
    Payroll Results - {{ $payrollPeriod->name }}
@stop

@section('page_title')
    Payroll Results
@stop

@section('page_title_suffix')
    {{ $payrollPeriod->name }}
@stop

@section('app_css')
    @include('layouts.datatables_css')
    <style>
        .payroll-summary-card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .payroll-summary-card:hover {
            transform: translateY(-2px);
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .payroll-table {
            font-size: 0.8rem;
            white-space: nowrap;
        }
        .payroll-table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.5rem 0.25rem;
            vertical-align: middle;
        }
        .payroll-table td {
            padding: 0.5rem 0.25rem;
            vertical-align: middle;
        }
        .table-responsive {
            max-height: 80vh;
            overflow-y: auto;
        }
        .employee-row:hover {
            background-color: #f8f9ff;
        }
        .amount-cell {
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }
        .department-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .export-buttons .btn {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
    </style>
@stop

@section('page_title_subtext')
    <a class="ms-1" href="{{ route('payroll.processing.index', $organization->id) }}">
        <i class="bx bx-chevron-left"></i> Back to Processing
    </a>
@stop

@section('page_title_buttons')
    <div class="export-buttons">
        <button type="button" class="btn btn-sm btn-success" onclick="exportPayroll('excel')">
            <i class="fas fa-file-excel me-1"></i>Excel
        </button>
        <button type="button" class="btn btn-sm btn-danger" onclick="exportPayroll('pdf')">
            <i class="fas fa-file-pdf me-1"></i>PDF
        </button>
        <button type="button" class="btn btn-sm btn-info" onclick="exportPayroll('csv')">
            <i class="fas fa-file-csv me-1"></i>CSV
        </button>
        @if($payrollPeriod->status !== 'completed')
            <button type="button" class="btn btn-sm btn-warning" onclick="reprocessPayroll()">
                <i class="fas fa-redo me-1"></i>Reprocess
            </button>
        @endif
    </div>
@stop

@section('content')

<!-- Period Information -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1">{{ $payrollPeriod->name }}</h5>
                <p class="text-muted mb-0">
                    Period: {{ $payrollPeriod->date->format('F Y') }} • 
                    Status: <span class="badge bg-{{ $payrollPeriod->status === 'completed' ? 'success' : 'warning' }}">
                        {{ ucfirst($payrollPeriod->status) }}
                    </span>
                    @if($payrollPeriod->processed_at)
                        • Processed: {{ $payrollPeriod->processed_at->format('M d, Y g:i A') }}
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-end">
                <h6 class="text-muted">Total Employees Processed</h6>
                <h2 class="text-primary mb-0">{{ number_format($summary['total_employees']) }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 mb-3">
        <div class="card payroll-summary-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-rgba-white-15">
                        <i class="bx bx-money"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Total Gross</h6>
                        <h4 class="mb-0">₦{{ number_format($summary['total_gross'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 mb-3">
        <div class="card payroll-summary-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-rgba-white-15">
                        <i class="bx bx-plus-circle"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Allowances</h6>
                        <h4 class="mb-0">₦{{ number_format($summary['total_allowances'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 mb-3">
        <div class="card payroll-summary-card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-rgba-white-15">
                        <i class="bx bx-minus-circle"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Deductions</h6>
                        <h4 class="mb-0">₦{{ number_format($summary['total_deductions'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 mb-3">
        <div class="card payroll-summary-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-rgba-white-15">
                        <i class="bx bx-receipt"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Income Tax</h6>
                        <h4 class="mb-0">₦{{ number_format($summary['total_income_tax'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 mb-3">
        <div class="card payroll-summary-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-rgba-white-15">
                        <i class="bx bx-wallet"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">Net Pay</h6>
                        <h4 class="mb-0">₦{{ number_format($summary['total_net'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Allowance & Deduction Types Breakdown -->
@if(!empty($allowanceBreakdown) || !empty($deductionBreakdown))
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Allowance & Deduction Types Summary</h6>
    </div>
    <div class="card-body">
        <div class="row">
            @if(!empty($allowanceBreakdown))
            <div class="col-md-6">
                <h6 class="text-success">Allowance Types</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th class="text-end">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allowanceBreakdown as $column => $displayName)
                                @php
                                    $totalAmount = $payrolls->getCollection()->sum($column);
                                @endphp
                                @if($totalAmount > 0)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $displayName)) }}</td>
                                    <td class="text-end">₦{{ number_format($totalAmount, 2) }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            
            @if(!empty($deductionBreakdown))
            <div class="col-md-6">
                <h6 class="text-danger">Deduction Types</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th class="text-end">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deductionBreakdown as $column => $displayName)
                                @php
                                    $totalAmount = $payrolls->getCollection()->sum($column);
                                @endphp
                                @if($totalAmount > 0)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $displayName)) }}</td>
                                    <td class="text-end">₦{{ number_format($totalAmount, 2) }}</td>
                                </tr>
                                @endif
                            @endforeach
                            @if($summary['total_income_tax'] > 0)
                            <tr>
                                <td><strong>Income Tax (PAYE)</strong></td>
                                <td class="text-end"><strong>₦{{ number_format($summary['total_income_tax'], 2) }}</strong></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- Department Breakdown -->
@if($departmentBreakdown->isNotEmpty())
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Department Breakdown</h6>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($departmentBreakdown as $department => $data)
            <div class="col-md-4 mb-3">
                <div class="border rounded p-3">
                    <h6 class="mb-2">{{ $department ?: 'No Department' }}</h6>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Employees:</span>
                        <span class="fw-bold">{{ $data['count'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Gross:</span>
                        <span class="fw-bold">₦{{ number_format($data['total_gross'], 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Net:</span>
                        <span class="fw-bold text-success">₦{{ number_format($data['total_net'], 2) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Payroll Records -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0">Employee Payroll Records</h6>
                <small class="text-muted">Detailed breakdown of each employee's payroll</small>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-outline-primary me-2" id="toggleViewBtn">
                    <i class="bx bx-list me-1"></i>Detailed View
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary d-none" id="toggleDetailsBtn">
                    <i class="bx bx-show-alt me-1"></i>Show Details
                </button>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        @if($payrolls->isEmpty())
            <div class="text-center py-5">
                <i class="bx bx-info-circle display-4 text-muted"></i>
                <h5 class="mt-3">No Payroll Records Found</h5>
                <p class="text-muted">No payroll records were found for this period.</p>
            </div>
        @else
            <!-- Summary Table View -->
            <div class="table-responsive" id="summaryTable">
                <table class="table table-hover payroll-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th class="text-end">Basic Salary</th>
                            <th class="text-end">Total Allowances</th>
                            <th class="text-end">Total Deductions</th>
                            <th class="text-end">Income Tax</th>
                            <th class="text-end">Gross Pay</th>
                            <th class="text-end">Net Pay</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $payroll)
                        <tr class="employee-row">
                            <td>
                                <div>
                                    <strong>{{ $payroll->employee_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $payroll->staff_number }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge department-badge bg-light text-dark">
                                    {{ $payroll->department_name ?? 'No Department' }}
                                </span>
                            </td>
                            <td class="text-end amount-cell">₦{{ number_format($payroll->basic_salary, 2) }}</td>
                            <td class="text-end amount-cell text-success">₦{{ number_format($payroll->total_allowances, 2) }}</td>
                            <td class="text-end amount-cell text-danger">₦{{ number_format($payroll->total_deductions, 2) }}</td>
                            <td class="text-end amount-cell text-warning">₦{{ number_format($payroll->income_tax, 2) }}</td>
                            <td class="text-end amount-cell">₦{{ number_format($payroll->net_salary, 2) }}</td>
                            <td class="text-end amount-cell fw-bold">₦{{ number_format($payroll->final_pay, 2) }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-horizontal-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewPayslip({{ $payroll->id }})">
                                            <i class="bx bx-file me-2"></i>View Payslip</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="downloadPayslip({{ $payroll->id }})">
                                            <i class="bx bx-download me-2"></i>Download PDF</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="viewDetails({{ $payroll->id }})">
                                            <i class="bx bx-info-circle me-2"></i>View Details</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Detailed Breakdown Table View -->
            <div class="table-responsive d-none" id="detailedTable">
                <table class="table table-hover payroll-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Employee</th>
                            <th rowspan="2">Department</th>
                            <th rowspan="2" class="text-end">Basic Salary</th>
                            
                            @if(!empty($allowanceBreakdown))
                            <th colspan="{{ count($allowanceBreakdown) }}" class="text-center bg-success text-white">Allowances</th>
                            @endif
                            
                            @if(!empty($deductionBreakdown))
                            <th colspan="{{ count($deductionBreakdown) + 1 }}" class="text-center bg-danger text-white">Deductions</th>
                            @endif
                            
                            <th rowspan="2" class="text-end">Gross Pay</th>
                            <th rowspan="2" class="text-end">Net Pay</th>
                            <th rowspan="2" class="text-center">Actions</th>
                        </tr>
                        <tr>
                            @foreach($allowanceBreakdown as $column => $displayName)
                            <th class="text-end">{{ ucwords(str_replace('_', ' ', $displayName)) }}</th>
                            @endforeach
                            
                            @foreach($deductionBreakdown as $column => $displayName)
                            <th class="text-end">{{ ucwords(str_replace('_', ' ', $displayName)) }}</th>
                            @endforeach
                            <th class="text-end">Income Tax</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $payroll)
                        <tr class="employee-row" data-employee-id="{{ $payroll->employee_id }}">
                            <td>
                                <div>
                                    <strong>{{ $payroll->employee_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $payroll->staff_number }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge department-badge bg-light text-dark">
                                    {{ $payroll->department_name ?? 'No Department' }}
                                </span>
                            </td>
                            <td class="text-end amount-cell">₦{{ number_format($payroll->basic_salary, 2) }}</td>
                            
                            <!-- Individual Allowance Columns -->
                            @foreach($allowanceBreakdown as $column => $displayName)
                                @php
                                    $amount = $payroll->$column ?? 0;
                                @endphp
                                <td class="text-end amount-cell text-success">
                                    @if($amount > 0)
                                        ₦{{ number_format($amount, 2) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach
                            
                            <!-- Individual Deduction Columns -->
                            @foreach($deductionBreakdown as $column => $displayName)
                                @php
                                    $amount = $payroll->$column ?? 0;
                                @endphp
                                <td class="text-end amount-cell text-danger">
                                    @if($amount > 0)
                                        ₦{{ number_format($amount, 2) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach
                            
                            <!-- Income Tax Column -->
                            <td class="text-end amount-cell text-warning">
                                @if($payroll->income_tax > 0)
                                    ₦{{ number_format($payroll->income_tax, 2) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            
                            <td class="text-end amount-cell">₦{{ number_format($payroll->net_salary, 2) }}</td>
                            <td class="text-end amount-cell fw-bold">₦{{ number_format($payroll->final_pay, 2) }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-horizontal-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewPayslip({{ $payroll->id }})">
                                            <i class="bx bx-file me-2"></i>View Payslip</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="downloadPayslip({{ $payroll->id }})">
                                            <i class="bx bx-download me-2"></i>Download PDF</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#" onclick="viewDetails({{ $payroll->id }})">
                                            <i class="bx bx-info-circle me-2"></i>View Details</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Hidden details row -->
                        <tr class="details-row d-none" id="details-{{ $payroll->id }}">
                            <td colspan="{{ 6 + count($allowanceBreakdown) + count($deductionBreakdown) }}">
                                <div class="p-3 bg-light rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Allowances Breakdown</h6>
                                            @php
                                                $hasAllowances = false;
                                            @endphp
                                            <table class="table table-sm">
                                                @foreach($allowanceBreakdown as $column => $displayName)
                                                    @php
                                                        $amount = $payroll->$column ?? 0;
                                                        if ($amount > 0) $hasAllowances = true;
                                                    @endphp
                                                    @if($amount > 0)
                                                    <tr>
                                                        <td>{{ ucwords(str_replace('_', ' ', $displayName)) }}</td>
                                                        <td class="text-end">₦{{ number_format($amount, 2) }}</td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                @if(!$hasAllowances)
                                                <tr>
                                                    <td colspan="2" class="text-muted">No allowances</td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Deductions Breakdown</h6>
                                            @php
                                                $hasDeductions = false;
                                            @endphp
                                            <table class="table table-sm">
                                                @foreach($deductionBreakdown as $column => $displayName)
                                                    @php
                                                        $amount = $payroll->$column ?? 0;
                                                        if ($amount > 0) $hasDeductions = true;
                                                    @endphp
                                                    @if($amount > 0)
                                                    <tr>
                                                        <td>{{ ucwords(str_replace('_', ' ', $displayName)) }}</td>
                                                        <td class="text-end">₦{{ number_format($amount, 2) }}</td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                
                                                @if($payroll->income_tax > 0)
                                                <tr>
                                                    <td><strong>Income Tax (PAYE)</strong></td>
                                                    <td class="text-end"><strong>₦{{ number_format($payroll->income_tax, 2) }}</strong></td>
                                                </tr>
                                                @php $hasDeductions = true; @endphp
                                                @endif
                                                
                                                @if(!$hasDeductions)
                                                <tr>
                                                    <td colspan="2" class="text-muted">No deductions</td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $payrolls->links() }}
            </div>
        @endif
    </div>
</div>

@stop

@push('page_scripts')
<script type="module">
$(document).ready(function() {
    // Initialize: Summary view is shown by default, detailed view is hidden
    let isShowingSummary = true;
    
    // Toggle between summary and detailed view
    $('#toggleViewBtn').click(function() {
        if (isShowingSummary) {
            // Switch to detailed view
            $('#summaryTable').addClass('d-none');
            $('#detailedTable').removeClass('d-none');
            $(this).html('<i class="bx bx-table me-1"></i>Summary View');
            $('#toggleDetailsBtn').removeClass('d-none'); // Show details toggle for detailed view
            isShowingSummary = false;
        } else {
            // Switch to summary view
            $('#detailedTable').addClass('d-none');
            $('#summaryTable').removeClass('d-none');
            $(this).html('<i class="bx bx-list me-1"></i>Detailed View');
            $('#toggleDetailsBtn').addClass('d-none'); // Hide details toggle for summary view
            // Also hide any expanded details when switching back to summary
            $('.details-row').addClass('d-none');
            $('#toggleDetailsBtn').html('<i class="bx bx-show-alt me-1"></i>Show Details');
            isShowingSummary = true;
        }
    });

    // Toggle details button (only works in detailed view)
    $('#toggleDetailsBtn').click(function() {
        const isShowing = $(this).find('i').hasClass('bx-hide');
        
        if (isShowing) {
            $('.details-row').addClass('d-none');
            $(this).html('<i class="bx bx-show-alt me-1"></i>Show Details');
        } else {
            $('.details-row').removeClass('d-none');
            $(this).html('<i class="bx bx-hide me-1"></i>Hide Details');
        }
    });
});

function exportPayroll(format) {
    const url = `{{ route('payroll.processing.export', [$organization->id, $payrollPeriod->id]) }}?format=${format}`;
    window.open(url, '_blank');
}

function reprocessPayroll() {
    Swal.fire({
        title: 'Reprocess Payroll?',
        text: 'This will clear existing data and reprocess the payroll for this period.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Reprocess',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `{{ route('payroll.processing.setup', [$organization->id, $payrollPeriod->id]) }}`;
        }
    });
}

function viewPayslip(payrollId) {
    // Implementation for viewing payslip
    window.open(`/payroll/payslip/${payrollId}`, '_blank');
}

function downloadPayslip(payrollId) {
    // Implementation for downloading payslip PDF
    window.open(`/payroll/payslip/${payrollId}/download`, '_blank');
}

function viewDetails(payrollId) {
    const detailsRow = $(`#details-${payrollId}`);
    detailsRow.toggleClass('d-none');
}
</script>
@endpush