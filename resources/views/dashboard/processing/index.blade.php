@extends('layouts.app')

@section('title_postfix')
    Payroll Processing
@stop

@section('page_title')
    Payroll Processing
@stop

@section('page_title_suffix')
    Dashboard
@stop

@section('app_css')
    @include('layouts.datatables_css')
    <style>
        .processing-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .processing-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .processing-card.completed {
            border-left-color: #28a745;
        }

        .processing-card.processing {
            border-left-color: #ffc107;
        }

        .processing-card.failed {
            border-left-color: #dc3545;
        }

        .processing-card.draft {
            border-left-color: #6c757d;
        }

        .stats-widget {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }

        .progress-ring {
            width: 60px;
            height: 60px;
        }

        .progress-ring circle {
            fill: none;
            stroke-width: 4;
            stroke-linecap: round;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }

        .alert-processing {
            background: linear-gradient(45deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
            border: none;
            color: #721c24;
        }
    </style>
@stop

@section('page_title_subtext')
    <a class="ms-1" href="{{ route('dashboard') }}">
        <i class="bx bx-chevron-left"></i> Back to Dashboard
    </a>
@stop

@section('page_title_buttons')
    @if (auth()->user()->can('finance.payroll.process'))
        <button type="button" class="btn btn-sm btn-success me-2" id="createPeriodBtn">
            <i class="fas fa-plus me-1"></i>New Period
        </button>
        <button type="button" class="btn btn-sm btn-info" id="settingsBtn">
            <i class="fas fa-cog me-1"></i>Settings
        </button>
    @endif
@stop

@section('content')

    <!-- Statistics Row -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">
        <div class="col">
            <div class="card radius-10 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0">Active Employees</p>
                            <h4 class="my-1">{{ number_format($activeEmployees) }}</h4>
                            <p class="mb-0 font-13">Ready for processing</p>
                        </div>
                        <div class="ms-auto font-35">
                            <i class="bx bx-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0">Completed Periods</p>
                            <h4 class="my-1">{{ $payrollPeriods->where('status', 'completed')->count() }}</h4>
                            <p class="mb-0 font-13">Successfully processed</p>
                        </div>
                        <div class="ms-auto font-35">
                            <i class="bx bx-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0">Salary Structures</p>
                            <h4 class="my-1">{{ $salaryStructures->count() }}</h4>
                            <p class="mb-0 font-13">Configured structures</p>
                        </div>
                        <div class="ms-auto font-35">
                            <i class="bx bx-sitemap"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0">This Month</p>
                            <h4 class="my-1">
                                @php
                                    $thisMonth = $payrollPeriods->where('date', '>=', now()->startOfMonth())->first();
                                    echo $thisMonth ? '₦' . number_format($thisMonth->total_net, 2) : '₦0.00';
                                @endphp
                            </h4>
                            <p class="mb-0 font-13">Total net payroll</p>
                        </div>
                        <div class="ms-auto font-35">
                            <i class="bx bx-money"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Status Alert -->
    @if ($recentProcessing)
        <div class="alert alert-processing alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div>
                    <h6 class="mb-1">Payroll Processing in Progress</h6>
                    <p class="mb-0">{{ $recentProcessing['status'] ?? 'Processing payroll for current period...' }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h5 class="mb-0">Payroll Periods</h5>
                    <small class="text-muted">Manage and process payroll for different periods</small>
                </div>
                <div class="ms-auto">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="viewMode" id="cardView" autocomplete="off" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="cardView">
                            <i class="bx bx-grid-alt"></i>
                        </label>

                        <input type="radio" class="btn-check" name="viewMode" id="listView" autocomplete="off">
                        <label class="btn btn-outline-secondary btn-sm" for="listView">
                            <i class="bx bx-list-ul"></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if ($payrollPeriods->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bx bx-calendar-x display-4 text-muted"></i>
                    </div>
                    <h5>No Payroll Periods Found</h5>
                    <p class="text-muted mb-4">Create your first payroll period to start processing employee salaries.</p>
                    <button type="button" class="btn btn-primary" id="createFirstPeriodBtn">
                        <i class="fas fa-plus me-2"></i>Create First Period
                    </button>
                </div>
            @else
                <!-- Card View -->
                <div id="cardViewContainer" class="row">
                    @foreach ($payrollPeriods as $period)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card processing-card h-100 {{ strtolower($period->status) }}">
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ $period->name }}</h6>
                                        <span
                                            class="badge 
                                    @if ($period->status === 'completed') bg-success
                                    @elseif($period->status === 'processing') bg-warning
                                    @elseif($period->status === 'failed') bg-danger
                                    @else bg-secondary @endif">
                                            {{ ucfirst($period->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <h5 class="text-primary mb-0">{{ $period->total_employees }}</h5>
                                            <small class="text-muted">Employees</small>
                                        </div>
                                        <div class="col-4">
                                            <h5 class="text-success mb-0">
                                                ₦{{ number_format($period->total_gross / 1000, 0) }}K</h5>
                                            <small class="text-muted">Gross</small>
                                        </div>
                                        <div class="col-4">
                                            <h5 class="text-info mb-0">₦{{ number_format($period->total_net / 1000, 0) }}K
                                            </h5>
                                            <small class="text-muted">Net</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between small">
                                            <span>Period:</span>
                                            <span class="fw-bold">{{ $period->date->format('M Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between small">
                                            <span>Created:</span>
                                            <span>{{ $period->created_at->format('M d, Y') }}</span>
                                        </div>
                                        @if ($period->processed_at)
                                            <div class="d-flex justify-content-between small">
                                                <span>Processed:</span>
                                                <span>{{ $period->processed_at->format('M d, Y g:i A') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Progress Bar for Processing -->
                                    @if ($period->status === 'processing')
                                        <div class="progress mb-3" style="height: 8px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                                                role="progressbar" style="width: 45%"></div>
                                        </div>
                                        <small class="text-muted">Processing... Please wait</small>
                                    @endif
                                </div>

                                <div class="card-footer border-0 pt-0">
                                    <div class="d-grid gap-2">
                                        @if ($period->status === 'draft' || $period->status === 'failed' || $period->status === null)
                                            <a href="{{ route('payroll.processing.setup',  $period->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-play me-1"></i>Process Payroll
                                            </a>
                                        @elseif($period->status === 'processing')
                                            <button class="btn btn-warning btn-sm" disabled>
                                                <i class="fas fa-spinner fa-spin me-1"></i>Processing...
                                            </button>
                                        @else
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('payroll.processing.view', $period->id) }}"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                                <button class="btn btn-outline-success btn-sm"
                                                    onclick="exportPayroll('{{ $period->id }}')">
                                                    <i class="fas fa-download me-1"></i>Export
                                                </button>
                                                <button class="btn btn-outline-info btn-sm"
                                                    onclick="reprocessPayroll('{{ $period->id }}')">
                                                    <i class="fas fa-redo me-1"></i>Reprocess
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- List View (Hidden by default) -->
                <div id="listViewContainer" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Status</th>
                                    <th>Employees</th>
                                    <th>Gross Amount</th>
                                    <th>Net Amount</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payrollPeriods as $period)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $period->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $period->date->format('F Y') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge 
                                        @if ($period->status === 'completed') bg-success
                                        @elseif($period->status === 'processing') bg-warning
                                        @elseif($period->status === 'failed') bg-danger
                                        @else bg-secondary @endif">
                                                {{ ucfirst($period->status) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($period->total_employees) }}</td>
                                        <td>₦{{ number_format($period->total_gross, 2) }}</td>
                                        <td>₦{{ number_format($period->total_net, 2) }}</td>
                                        <td>{{ $period->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if ($period->status === 'draft' || $period->status === 'failed')
                                                    <a href="{{ route('payroll.processing.setup', [$organization->id, $period->id]) }}"
                                                        class="btn btn-primary">Process</a>
                                                @else
                                                    <a href="{{ route('payroll.processing.view', $period->id) }}"
                                                        class="btn btn-outline-primary">View</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="{{ route('payroll.salary-structure-rates.index') }}"
                                    class="btn btn-outline-primary">
                                    <i class="fas fa-cog me-2"></i>Manage Salary Rates
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-info" id="employeeStatusBtn">
                                    <i class="fas fa-users me-2"></i>Employee Status
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-success" id="payrollReportsBtn">
                                    <i class="fas fa-chart-bar me-2"></i>Payroll Reports
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-warning" id="auditTrailBtn">
                                    <i class="fas fa-history me-2"></i>Audit Trail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Processing Tips</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Pro Tips</h6>
                        <ul class="mb-0 small">
                            <li>Always backup before processing</li>
                            <li>Use test mode for validation</li>
                            <li>Check employee assignments</li>
                            <li>Verify salary structure rates</li>
                            <li>Review results before approval</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('page_scripts')
    <script type="text/javascript">
        function exportPayroll(periodId) {
            Swal.fire({
                title: 'Export Payroll Data',
                text: 'Choose export format',
                showCancelButton: true,
                confirmButtonText: 'Excel',
                cancelButtonText: 'PDF',
                showDenyButton: true,
                denyButtonText: 'CSV'
            }).then((result) => {
                let format = 'excel';
                if (result.isDenied) format = 'csv';
                if (result.dismiss === Swal.DismissReason.cancel) format = 'pdf';

                if (result.isConfirmed || result.isDenied || result.dismiss === Swal.DismissReason.cancel) {
                    window.open(`/payroll/processing/export/${periodId}?format=${format}`, '_blank');
                }
            });
        }

        function reprocessPayroll(periodId) {
            Swal.fire({
                title: 'Reprocess Payroll?',
                text: 'This will clear existing data and reprocess the payroll for this period.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Reprocess',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/payroll/processing/setup/${periodId}`;
                }
            });
        }
    </script>
    <script type="module">
        $(document).ready(function() {
            // View mode toggle
            $('input[name="viewMode"]').change(function() {
                if ($('#cardView').is(':checked')) {
                    $('#cardViewContainer').removeClass('d-none');
                    $('#listViewContainer').addClass('d-none');
                } else {
                    $('#cardViewContainer').addClass('d-none');
                    $('#listViewContainer').removeClass('d-none');
                }
            });

            // Create period button
            $('#createPeriodBtn, #createFirstPeriodBtn').click(function() {
                Swal.fire({
                    title: 'Create New Payroll Period',
                    html: `
                <form id="createPeriodForm">
                    <div class="mb-3 text-start">
                        <label class="form-label">Period Name</label>
                        <input type="text" class="form-control" name="name" placeholder="e.g., January 2024 Payroll">
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Period Date</label>
                        <input type="month" class="form-control" name="date" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Description (Optional)</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Additional notes..."></textarea>
                    </div>
                </form>
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Create Period',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const form = document.getElementById('createPeriodForm');
                        const formData = new FormData(form);
                        formData.append('_method', 'POST');
                        formData.append('status', 'draft');
                        formData.append('organization_id', '{{ $organization->id }}');

                        $.ajax({
                            url: '{{ route('payroll-api.payroll_periods.store') }}',
                            type: 'POST',
                            data: formData,
                            cache: false,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(data) {
                                if (data.errors) {
                                    let errorMessages = [];
                                    Object.keys(data.errors).forEach(key => {
                                        data.errors[key].forEach(error => {
                                            errorMessages.push(
                                                error);
                                        });
                                    });
                                    Swal.showValidationMessage(errorMessages.join(
                                            '<br>') ||
                                        'Failed to create period');
                                } else {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Payroll period created successfully',
                                        icon: 'success'
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function(error) {
                                Swal.showValidationMessage(error.responseText);
                            }
                        });
                    }
                });
            });

            // Settings button
            $('#settingsBtn').click(function() {
                Swal.fire({
                    title: 'Processing Settings',
                    html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Batch Size</label>
                        <input type="number" class="form-control" value="50" min="1" max="200">
                        <small class="text-muted">Number of employees to process at once</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Currency</label>
                        <select class="form-control">
                            <option value="NGN">Nigerian Naira (₦)</option>
                            <option value="USD">US Dollar ($)</option>
                            <option value="GBP">British Pound (£)</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="autoBackup">
                        <label class="form-check-label" for="autoBackup">
                            Auto backup before processing
                        </label>
                    </div>
                </div>
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Save Settings',
                    cancelButtonText: 'Cancel'
                });
            });

            // Auto-refresh processing status
            setInterval(function() {
                $('.processing-card.processing').each(function() {
                    // Check processing status and update UI
                    // This would make AJAX calls to check progress
                });
            }, 10000); // Check every 10 seconds
        });
    </script>
@endpush
