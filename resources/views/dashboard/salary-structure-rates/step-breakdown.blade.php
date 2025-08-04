@extends('layouts.app')

@section('title_postfix')
    Step Breakdown - {{ $step->gradeLevel->level }} Step {{ $step->step_number }}
@stop

@section('page_title')
    Salary Breakdown
@stop

@section('page_title_suffix')
    <small class="text-muted">{{ $step->gradeLevel->level }} - Step {{ $step->step_number }}</small>
@stop

@section('app_css')
    @include('layouts.datatables_css')
    <style>
        .breakdown-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .breakdown-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 10px 10px 0 0;
        }
        
        .amount-large {
            font-size: 2rem;
            font-weight: bold;
        }
        
        .amount-medium {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .breakdown-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .breakdown-item:last-child {
            border-bottom: none;
        }
        
        .breakdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .rate-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        
        .summary-card {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .summary-card.deduction {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
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
    <span class="mx-2">•</span>
    <a href="{{ route('payroll.salary-structure-rates.setup', $step->gradeLevel->salaryStructure->id) }}">
        <i class="bx bx-cog"></i> {{ $step->gradeLevel->salaryStructure->name }}
    </a>
@stop

@section('page_title_buttons')
    @if(auth()->user()->can('finance.salary-structures.manage'))
        <button type="button" class="btn btn-sm btn-warning me-1" id="editRatesBtn">
            <i class="fas fa-edit me-1"></i>Edit Rates
        </button>
        <button type="button" class="btn btn-sm btn-info me-1" id="exportBreakdownBtn">
            <i class="fas fa-download me-1"></i>Export
        </button>
        <button type="button" class="btn btn-sm btn-success me-1" id="printBreakdownBtn">
            <i class="fas fa-print me-1"></i>Print
        </button>
    @endif
@stop

@section('content')

    <!-- Step Information Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card breakdown-card">
                <div class="breakdown-header text-center">
                    <h3 class="mb-2">{{ $step->gradeLevel->salaryStructure->name }}</h3>
                    <h4 class="mb-1">{{ $step->gradeLevel->level }} - Step {{ $step->step_number }}</h4>
                    @if($step->description)
                        <p class="mb-0 opacity-75">{{ $step->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">
        <div class="col">
            <div class="card radius-10 bg-primary">
                <div class="card-body text-center">
                    <div class="text-white">
                        <p class="mb-0">Base Salary</p>
                        <h4 class="my-1">{{ $breakdown['currency'] }} {{ number_format($breakdown['base_salary'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 bg-success">
                <div class="card-body text-center">
                    <div class="text-white">
                        <p class="mb-0">Total Allowances</p>
                        <h4 class="my-1">{{ $breakdown['currency'] }} {{ number_format($breakdown['total_allowances'], 2) }}</h4>
                        <small>({{ count($breakdown['allowances']) }} items)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 bg-warning">
                <div class="card-body text-center">
                    <div class="text-white">
                        <p class="mb-0">Total Deductions</p>
                        <h4 class="my-1">{{ $breakdown['currency'] }} {{ number_format($breakdown['total_deductions'], 2) }}</h4>
                        <small>({{ count($breakdown['deductions']) }} items)</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 bg-info">
                <div class="card-body text-center">
                    <div class="text-white">
                        <p class="mb-0">Net Salary</p>
                        <h4 class="my-1">{{ $breakdown['currency'] }} {{ number_format($breakdown['net_salary'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Breakdown -->
    <div class="row">
        <!-- Base Salary -->
        <div class="col-md-4 mb-4">
            <div class="card breakdown-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Base Salary
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="amount-large text-primary">
                        {{ $breakdown['currency'] }} {{ number_format($breakdown['base_salary'], 2) }}
                    </div>
                    <p class="text-muted mt-2">Monthly Base Salary</p>
                </div>
            </div>
        </div>

        <!-- Allowances -->
        <div class="col-md-4 mb-4">
            <div class="card breakdown-card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Allowances ({{ count($breakdown['allowances']) }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if(count($breakdown['allowances']) > 0)
                        @foreach($breakdown['allowances'] as $allowance)
                            <div class="breakdown-item">
                                <div>
                                    <strong>{{ $allowance['name'] }}</strong>
                                    <div class="mt-1">
                                        <span class="badge bg-{{ $allowance['type'] == 'percentage' ? 'info' : 'secondary' }} rate-badge">
                                            @if($allowance['type'] == 'percentage')
                                                {{ number_format($allowance['rate'], 2) }}%
                                            @else
                                                {{ $breakdown['currency'] }} {{ number_format($allowance['rate'], 2) }}
                                            @endif
                                        </span>
                                        @if($allowance['is_taxable'])
                                            <span class="badge bg-warning rate-badge">Taxable</span>
                                        @endif
                                        @if($allowance['is_earned'])
                                            <span class="badge bg-primary rate-badge">Earned</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-success fw-bold">
                                    +{{ $breakdown['currency'] }} {{ number_format($allowance['amount'], 2) }}
                                </div>
                            </div>
                        @endforeach
                        <div class="breakdown-item bg-light">
                            <strong>Total Allowances</strong>
                            <strong class="text-success amount-medium">
                                +{{ $breakdown['currency'] }} {{ number_format($breakdown['total_allowances'], 2) }}
                            </strong>
                        </div>
                    @else
                        <div class="text-center p-4 text-muted">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p>No allowances configured for this step</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Deductions -->
        <div class="col-md-4 mb-4">
            <div class="card breakdown-card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-minus-circle me-2"></i>
                        Deductions ({{ count($breakdown['deductions']) }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if(count($breakdown['deductions']) > 0)
                        @foreach($breakdown['deductions'] as $deduction)
                            <div class="breakdown-item">
                                <div>
                                    <strong>{{ $deduction['name'] }}</strong>
                                    <div class="mt-1">
                                        <span class="badge bg-{{ $deduction['type'] == 'percentage' ? 'info' : 'secondary' }} rate-badge">
                                            @if($deduction['type'] == 'percentage')
                                                {{ number_format($deduction['rate'], 2) }}%
                                            @else
                                                {{ $breakdown['currency'] }} {{ number_format($deduction['rate'], 2) }}
                                            @endif
                                        </span>
                                        @if($deduction['is_mandatory'])
                                            <span class="badge bg-danger rate-badge">Mandatory</span>
                                        @endif
                                        <span class="badge bg-dark rate-badge">{{ ucfirst($deduction['deduction_type']) }}</span>
                                    </div>
                                </div>
                                <div class="text-danger fw-bold">
                                    -{{ $breakdown['currency'] }} {{ number_format($deduction['amount'], 2) }}
                                </div>
                            </div>
                        @endforeach
                        <div class="breakdown-item bg-light">
                            <strong>Total Deductions</strong>
                            <strong class="text-danger amount-medium">
                                -{{ $breakdown['currency'] }} {{ number_format($breakdown['total_deductions'], 2) }}
                            </strong>
                        </div>
                    @else
                        <div class="text-center p-4 text-muted">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p>No deductions configured for this step</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Calculation Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card breakdown-card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        Salary Calculation Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="calculation-flow">
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span>Base Salary:</span>
                                    <strong class="text-primary">{{ $breakdown['currency'] }} {{ number_format($breakdown['base_salary'], 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span>Total Allowances:</span>
                                    <strong class="text-success">+ {{ $breakdown['currency'] }} {{ number_format($breakdown['total_allowances'], 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span><strong>Gross Salary:</strong></span>
                                    <strong class="text-info">{{ $breakdown['currency'] }} {{ number_format($breakdown['gross_salary'], 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <span>Total Deductions:</span>
                                    <strong class="text-danger">- {{ $breakdown['currency'] }} {{ number_format($breakdown['total_deductions'], 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-3 border-bottom border-2 bg-light rounded px-3">
                                    <span class="fs-5"><strong>NET SALARY:</strong></span>
                                    <strong class="text-success fs-4">{{ $breakdown['currency'] }} {{ number_format($breakdown['net_salary'], 2) }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-3">Salary Distribution</h6>
                                <div class="mb-3">
                                    @php
                                        $grossSalary = $breakdown['gross_salary'];
                                        $basePercentage = $grossSalary > 0 ? ($breakdown['base_salary'] / $grossSalary) * 100 : 0;
                                        $allowancePercentage = $grossSalary > 0 ? ($breakdown['total_allowances'] / $grossSalary) * 100 : 0;
                                        $deductionPercentage = $grossSalary > 0 ? ($breakdown['total_deductions'] / $grossSalary) * 100 : 0;
                                    @endphp
                                    
                                    <div class="progress mb-2" style="height: 25px;">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: {{ $basePercentage }}%" 
                                             title="Base Salary: {{ number_format($basePercentage, 1) }}%">
                                            {{ number_format($basePercentage, 1) }}%
                                        </div>
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $allowancePercentage }}%" 
                                             title="Allowances: {{ number_format($allowancePercentage, 1) }}%">
                                            {{ number_format($allowancePercentage, 1) }}%
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <small><i class="fas fa-square text-primary"></i> Base ({{ number_format($basePercentage, 1) }}%)</small>
                                        <small><i class="fas fa-square text-success"></i> Allowances ({{ number_format($allowancePercentage, 1) }}%)</small>
                                    </div>
                                </div>
                                
                                @if($breakdown['total_deductions'] > 0)
                                    <div class="alert alert-warning">
                                        <small><i class="fas fa-exclamation-triangle"></i> 
                                        {{ number_format($deductionPercentage, 1) }}% of gross salary is deducted</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('side-panel')
    <div class="card radius-5 border-top border-0 border-4 border-info">
        <div class="card-body">
            <div>
                <h5 class="card-title">Step Information</h5>
            </div>
            <p class="small">
                Detailed salary breakdown for {{ $step->gradeLevel->level }} Step {{ $step->step_number }}.
                @if($step->description)
                    {{ $step->description }}
                @endif
            </p>

            <div class="mt-3">
                <h6>Step Details:</h6>
                <ul class="small">
                    <li><strong>Structure:</strong> {{ $step->gradeLevel->salaryStructure->name }}</li>
                    <li><strong>Grade Level:</strong> {{ $step->gradeLevel->level }}</li>
                    <li><strong>Step Number:</strong> {{ $step->step_number }}</li>
                    <li><strong>Status:</strong> {{ $step->is_active ? 'Active' : 'Inactive' }}</li>
                </ul>
            </div>

            <div class="mt-3">
                <h6>Quick Actions:</h6>
                <div class="d-grid gap-2">
                    @if(auth()->user()->can('manage_payroll_rates'))
                        <button class="btn btn-outline-primary btn-sm" onclick="window.location='{{ route('payroll.salary-structure-rates.setup', $step->gradeLevel->salaryStructure->id) }}'">
                            <i class="fas fa-edit me-1"></i> Edit Rates
                        </button>
                    @endif
                    <button class="btn btn-outline-info btn-sm" id="refreshBreakdownBtn">
                        <i class="fas fa-sync me-1"></i> Refresh Data
                    </button>
                </div>
            </div>

            <div class="mt-3">
                <h6>Related Actions:</h6>
                <ul class="small">
                    <li>Compare with other steps</li>
                    <li>View historical changes</li>
                    <li>Export breakdown report</li>
                    <li>Generate payslip preview</li>
                </ul>
            </div>
        </div>
    </div>
@stop

@push('page_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    <script type="module">
        $(document).ready(function() {
            
            // Export breakdown functionality with options
            $('#exportBreakdownBtn').on('click', function() {
                // Show export options modal
                showExportOptions();
            });

            // Print breakdown functionality with enhanced styles
            $('#printBreakdownBtn').on('click', function() {
                printBreakdown();
            });

            // Edit rates functionality
            $('#editRatesBtn').on('click', function() {
                window.location = '{{ route('payroll.salary-structure-rates.setup', $step->gradeLevel->salaryStructure->id) }}';
            });

            // Refresh breakdown data
            $('#refreshBreakdownBtn').on('click', function() {
                Swal.fire({
                    title: 'Refreshing...',
                    text: 'Please wait while we refresh the data',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Add comprehensive print styles
            addPrintStyles();
            
            // Create export options modal
            createExportModal();
        });

        function showExportOptions() {
            Swal.fire({
                title: 'Export Salary Breakdown',
                html: `
                    <div class="text-start">
                        <h6 class="mb-3">Choose export format:</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-danger" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf me-2"></i>Export as PDF
                            </button>
                            <button class="btn btn-outline-success" onclick="exportToExcel()">
                                <i class="fas fa-file-excel me-2"></i>Export as Excel
                            </button>
                            <button class="btn btn-outline-primary" onclick="exportToCSV()">
                                <i class="fas fa-file-csv me-2"></i>Export as CSV
                            </button>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                width: '400px'
            });
        }

        function printBreakdown() {
            // Create a clean print version
            const printContent = createPrintableContent();
            
            // Open new window for printing
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Salary Breakdown - {{ $step->gradeLevel->level }} Step {{ $step->step_number }}</title>
                    <style>
                        ${getPrintStyles()}
                    </style>
                </head>
                <body>
                    ${printContent}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.focus();
            
            // Wait for content to load then print
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }

        function createPrintableContent() {
            const breakdown = @json($breakdown);
            const step = @json($step);
            
            return `
                <div class="print-container">
                    <div class="print-header">
                        <h1>Salary Breakdown Report</h1>
                        <h2>${step.grade_level.salary_structure.name}</h2>
                        <h3>${step.grade_level.level} - Step ${step.step_number}</h3>
                        <p class="print-date">Generated on: ${new Date().toLocaleDateString()}</p>
                    </div>

                    <div class="summary-section">
                        <h4>Summary</h4>
                        <table class="summary-table">
                            <tr>
                                <td>Base Salary:</td>
                                <td>${breakdown.currency} ${numberFormat(breakdown.base_salary)}</td>
                            </tr>
                            <tr>
                                <td>Total Allowances:</td>
                                <td class="positive">+${breakdown.currency} ${numberFormat(breakdown.total_allowances)}</td>
                            </tr>
                            <tr>
                                <td>Gross Salary:</td>
                                <td>${breakdown.currency} ${numberFormat(breakdown.gross_salary)}</td>
                            </tr>
                            <tr>
                                <td>Total Deductions:</td>
                                <td class="negative">-${breakdown.currency} ${numberFormat(breakdown.total_deductions)}</td>
                            </tr>
                            <tr class="total-row">
                                <td><strong>Net Salary:</strong></td>
                                <td><strong>${breakdown.currency} ${numberFormat(breakdown.net_salary)}</strong></td>
                            </tr>
                        </table>
                    </div>

                    ${breakdown.allowances.length > 0 ? `
                        <div class="detail-section">
                            <h4>Allowances Breakdown</h4>
                            <table class="detail-table">
                                <thead>
                                    <tr>
                                        <th>Allowance</th>
                                        <th>Type</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${breakdown.allowances.map(allowance => `
                                        <tr>
                                            <td>${allowance.name}</td>
                                            <td>${allowance.type}</td>
                                            <td>${allowance.type === 'percentage' ? allowance.rate + '%' : breakdown.currency + ' ' + numberFormat(allowance.rate)}</td>
                                            <td class="positive">+${breakdown.currency} ${numberFormat(allowance.amount)}</td>
                                            <td>
                                                ${allowance.is_taxable ? '<span class="badge">Taxable</span>' : ''}
                                                ${allowance.is_earned ? '<span class="badge">Earned</span>' : ''}
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    ` : ''}

                    ${breakdown.deductions.length > 0 ? `
                        <div class="detail-section">
                            <h4>Deductions Breakdown</h4>
                            <table class="detail-table">
                                <thead>
                                    <tr>
                                        <th>Deduction</th>
                                        <th>Type</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${breakdown.deductions.map(deduction => `
                                        <tr>
                                            <td>${deduction.name}</td>
                                            <td>${deduction.type}</td>
                                            <td>${deduction.type === 'percentage' ? deduction.rate + '%' : breakdown.currency + ' ' + numberFormat(deduction.rate)}</td>
                                            <td class="negative">-${breakdown.currency} ${numberFormat(deduction.amount)}</td>
                                            <td>
                                                ${deduction.is_mandatory ? '<span class="badge">Mandatory</span>' : ''}
                                                <span class="badge">${deduction.deduction_type}</span>
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    ` : ''}

                    <div class="print-footer">
                        <p>This is a computer-generated document. No signature required.</p>
                        <p>Generated by Payroll Management System on ${new Date().toLocaleString()}</p>
                    </div>
                </div>
            `;
        }

        function getPrintStyles() {
            return `
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 20px; 
                    color: #000; 
                    line-height: 1.4;
                }
                .print-container { max-width: 800px; margin: 0 auto; }
                .print-header { 
                    text-align: center; 
                    border-bottom: 2px solid #000; 
                    padding-bottom: 20px; 
                    margin-bottom: 30px; 
                }
                .print-header h1 { margin: 0; font-size: 24px; }
                .print-header h2 { margin: 5px 0; font-size: 18px; color: #666; }
                .print-header h3 { margin: 5px 0; font-size: 16px; }
                .print-date { margin: 10px 0 0 0; font-size: 12px; color: #888; }
                
                .summary-section, .detail-section { margin-bottom: 30px; }
                .summary-section h4, .detail-section h4 { 
                    border-bottom: 1px solid #ccc; 
                    padding-bottom: 5px; 
                    margin-bottom: 10px; 
                }
                
                .summary-table, .detail-table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-bottom: 20px; 
                }
                .summary-table td, .detail-table th, .detail-table td { 
                    padding: 8px 12px; 
                    border: 1px solid #ccc; 
                    text-align: left; 
                }
                .detail-table th { 
                    background-color: #f5f5f5; 
                    font-weight: bold; 
                }
                .summary-table td:last-child, .detail-table td:last-child { 
                    text-align: right; 
                }
                
                .total-row { 
                    background-color: #f0f0f0; 
                    font-weight: bold; 
                    border-top: 2px solid #000; 
                }
                .positive { color: #28a745; }
                .negative { color: #dc3545; }
                
                .badge { 
                    padding: 2px 6px; 
                    font-size: 10px; 
                    background-color: #e9ecef; 
                    border-radius: 3px; 
                    margin-right: 4px; 
                }
                
                .print-footer { 
                    margin-top: 40px; 
                    text-align: center; 
                    font-size: 12px; 
                    color: #666; 
                    border-top: 1px solid #ccc; 
                    padding-top: 20px; 
                }
                
                @media print {
                    body { margin: 0; }
                    .print-container { max-width: none; }
                    .summary-section, .detail-section { page-break-inside: avoid; }
                }
            `;
        }

        window.exportToPDF = function() {
            Swal.close();
            
            Swal.fire({
                title: 'Generating PDF...',
                text: 'Please wait while we create your PDF file',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create PDF using jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const breakdown = @json($breakdown);
            const step = @json($step);

            // Add title
            doc.setFontSize(20);
            doc.text('Salary Breakdown Report', 20, 30);
            
            doc.setFontSize(14);
            doc.text(`${step.grade_level.salary_structure.name}`, 20, 45);
            doc.text(`${step.grade_level.level} - Step ${step.step_number}`, 20, 55);
            
            doc.setFontSize(10);
            doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 20, 70);

            let yPosition = 90;

            // Summary section
            doc.setFontSize(12);
            doc.text('Summary', 20, yPosition);
            yPosition += 15;

            doc.setFontSize(10);
            const summaryData = [
                ['Base Salary:', `${breakdown.currency} ${numberFormat(breakdown.base_salary)}`],
                ['Total Allowances:', `+${breakdown.currency} ${numberFormat(breakdown.total_allowances)}`],
                ['Gross Salary:', `${breakdown.currency} ${numberFormat(breakdown.gross_salary)}`],
                ['Total Deductions:', `-${breakdown.currency} ${numberFormat(breakdown.total_deductions)}`],
                ['NET SALARY:', `${breakdown.currency} ${numberFormat(breakdown.net_salary)}`]
            ];

            summaryData.forEach((row, index) => {
                doc.text(row[0], 25, yPosition);
                doc.text(row[1], 120, yPosition);
                if (index === summaryData.length - 1) {
                    doc.setFont(undefined, 'bold');
                }
                yPosition += 10;
            });

            // Add allowances if any
            if (breakdown.allowances.length > 0) {
                yPosition += 10;
                doc.setFont(undefined, 'bold');
                doc.text('Allowances Breakdown', 20, yPosition);
                yPosition += 10;
                doc.setFont(undefined, 'normal');

                breakdown.allowances.forEach(allowance => {
                    if (yPosition > 270) {
                        doc.addPage();
                        yPosition = 20;
                    }
                    doc.text(`• ${allowance.name}`, 25, yPosition);
                    doc.text(`+${breakdown.currency} ${numberFormat(allowance.amount)}`, 120, yPosition);
                    yPosition += 8;
                });
            }

            // Add deductions if any
            if (breakdown.deductions.length > 0) {
                yPosition += 10;
                if (yPosition > 260) {
                    doc.addPage();
                    yPosition = 20;
                }
                doc.setFont(undefined, 'bold');
                doc.text('Deductions Breakdown', 20, yPosition);
                yPosition += 10;
                doc.setFont(undefined, 'normal');

                breakdown.deductions.forEach(deduction => {
                    if (yPosition > 270) {
                        doc.addPage();
                        yPosition = 20;
                    }
                    doc.text(`• ${deduction.name}`, 25, yPosition);
                    doc.text(`-${breakdown.currency} ${numberFormat(deduction.amount)}`, 120, yPosition);
                    yPosition += 8;
                });
            }

            const fileName = `salary-breakdown-${step.grade_level.level}-step-${step.step_number}.pdf`;
            doc.save(fileName);

            Swal.fire({
                title: 'Success!',
                text: 'PDF has been generated and downloaded',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        };

        window.exportToExcel = function() {
            Swal.close();
            
            const breakdown = @json($breakdown);
            const step = @json($step);

            // Create workbook
            const wb = XLSX.utils.book_new();

            // Summary sheet
            const summaryData = [
                ['Salary Breakdown Report'],
                [`${step.grade_level.salary_structure.name}`],
                [`${step.grade_level.level} - Step ${step.step_number}`],
                [`Generated on: ${new Date().toLocaleDateString()}`],
                [],
                ['Summary'],
                ['Item', 'Amount'],
                ['Base Salary', `${breakdown.currency} ${numberFormat(breakdown.base_salary)}`],
                ['Total Allowances', `${breakdown.currency} ${numberFormat(breakdown.total_allowances)}`],
                ['Gross Salary', `${breakdown.currency} ${numberFormat(breakdown.gross_salary)}`],
                ['Total Deductions', `${breakdown.currency} ${numberFormat(breakdown.total_deductions)}`],
                ['NET SALARY', `${breakdown.currency} ${numberFormat(breakdown.net_salary)}`]
            ];

            const summaryWs = XLSX.utils.aoa_to_sheet(summaryData);
            XLSX.utils.book_append_sheet(wb, summaryWs, 'Summary');

            // Allowances sheet
            if (breakdown.allowances.length > 0) {
                const allowanceData = [
                    ['Allowances Breakdown'],
                    [],
                    ['Name', 'Type', 'Rate', 'Amount', 'Taxable', 'Earned']
                ];
                
                breakdown.allowances.forEach(allowance => {
                    allowanceData.push([
                        allowance.name,
                        allowance.type,
                        allowance.type === 'percentage' ? `${allowance.rate}%` : `${breakdown.currency} ${numberFormat(allowance.rate)}`,
                        `${breakdown.currency} ${numberFormat(allowance.amount)}`,
                        allowance.is_taxable ? 'Yes' : 'No',
                        allowance.is_earned ? 'Yes' : 'No'
                    ]);
                });

                const allowanceWs = XLSX.utils.aoa_to_sheet(allowanceData);
                XLSX.utils.book_append_sheet(wb, allowanceWs, 'Allowances');
            }

            // Deductions sheet
            if (breakdown.deductions.length > 0) {
                const deductionData = [
                    ['Deductions Breakdown'],
                    [],
                    ['Name', 'Type', 'Rate', 'Amount', 'Deduction Type', 'Mandatory']
                ];
                
                breakdown.deductions.forEach(deduction => {
                    deductionData.push([
                        deduction.name,
                        deduction.type,
                        deduction.type === 'percentage' ? `${deduction.rate}%` : `${breakdown.currency} ${numberFormat(deduction.rate)}`,
                        `${breakdown.currency} ${numberFormat(deduction.amount)}`,
                        deduction.deduction_type,
                        deduction.is_mandatory ? 'Yes' : 'No'
                    ]);
                });

                const deductionWs = XLSX.utils.aoa_to_sheet(deductionData);
                XLSX.utils.book_append_sheet(wb, deductionWs, 'Deductions');
            }

            const fileName = `salary-breakdown-${step.grade_level.level}-step-${step.step_number}.xlsx`;
            XLSX.writeFile(wb, fileName);

            Swal.fire({
                title: 'Success!',
                text: 'Excel file has been generated and downloaded',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        };

        window.exportToCSV = function() {
            Swal.close();
            
            const breakdown = @json($breakdown);
            const step = @json($step);

            let csvContent = "data:text/csv;charset=utf-8,";
            
            // Header
            csvContent += `Salary Breakdown Report\n`;
            csvContent += `${step.grade_level.salary_structure.name}\n`;
            csvContent += `${step.grade_level.level} - Step ${step.step_number}\n`;
            csvContent += `Generated on: ${new Date().toLocaleDateString()}\n\n`;
            
            // Summary
            csvContent += "Summary\n";
            csvContent += "Item,Amount\n";
            csvContent += `Base Salary,${breakdown.currency} ${numberFormat(breakdown.base_salary)}\n`;
            csvContent += `Total Allowances,${breakdown.currency} ${numberFormat(breakdown.total_allowances)}\n`;
            csvContent += `Gross Salary,${breakdown.currency} ${numberFormat(breakdown.gross_salary)}\n`;
            csvContent += `Total Deductions,${breakdown.currency} ${numberFormat(breakdown.total_deductions)}\n`;
            csvContent += `NET SALARY,${breakdown.currency} ${numberFormat(breakdown.net_salary)}\n\n`;

            // Allowances
            if (breakdown.allowances.length > 0) {
                csvContent += "Allowances Breakdown\n";
                csvContent += "Name,Type,Rate,Amount,Taxable,Earned\n";
                breakdown.allowances.forEach(allowance => {
                    const rate = allowance.type === 'percentage' ? `${allowance.rate}%` : `${breakdown.currency} ${numberFormat(allowance.rate)}`;
                    csvContent += `${allowance.name},${allowance.type},${rate},${breakdown.currency} ${numberFormat(allowance.amount)},${allowance.is_taxable ? 'Yes' : 'No'},${allowance.is_earned ? 'Yes' : 'No'}\n`;
                });
                csvContent += "\n";
            }

            // Deductions
            if (breakdown.deductions.length > 0) {
                csvContent += "Deductions Breakdown\n";
                csvContent += "Name,Type,Rate,Amount,Deduction Type,Mandatory\n";
                breakdown.deductions.forEach(deduction => {
                    const rate = deduction.type === 'percentage' ? `${deduction.rate}%` : `${breakdown.currency} ${numberFormat(deduction.rate)}`;
                    csvContent += `${deduction.name},${deduction.type},${rate},${breakdown.currency} ${numberFormat(deduction.amount)},${deduction.deduction_type},${deduction.is_mandatory ? 'Yes' : 'No'}\n`;
                });
            }

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `salary-breakdown-${step.grade_level.level}-step-${step.step_number}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            Swal.fire({
                title: 'Success!',
                text: 'CSV file has been generated and downloaded',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        };

        function numberFormat(number) {
            return new Intl.NumberFormat().format(parseFloat(number).toFixed(2));
        }

        function addPrintStyles() {
            const printStyles = `
                <style media="print">
                    .btn, .side-panel, .page-title-buttons, .breadcrumb { display: none !important; }
                    .breakdown-card { break-inside: avoid; page-break-inside: avoid; }
                    .card { border: 1px solid #000 !important; margin-bottom: 10px !important; }
                    .bg-primary, .bg-success, .bg-warning, .bg-info, .bg-dark { 
                        background: #f8f9fa !important; 
                        color: #000 !important; 
                        -webkit-print-color-adjust: exact;
                    }
                    .text-white { color: #000 !important; }
                    body { font-size: 12px; }
                    .card-body { padding: 0.5rem !important; }
                    .breakdown-item { padding: 0.25rem 0.5rem !important; }
                    @page { margin: 1cm; }
                </style>
            `;
            $('head').append(printStyles);
        }

        function createExportModal() {
            // Modal is created dynamically via SweetAlert, no need for static modal
        }
    </script>
@endpush