@extends('layouts.app')

@section('title_postfix')
    Salary Structure Rates
@stop

@section('page_title')
    Salary Structure Rates
@stop

@section('page_title_suffix')
    Management
@stop

@section('app_css')
    @include('layouts.datatables_css')
@stop

@section('page_title_subtext')
    <a class="ms-1" href="{{ route('dashboard') }}">
        <i class="bx bx-chevron-left"></i> Back to Dashboard
    </a>
@stop

@section('page_title_buttons')
    @if(auth()->user()->can('finance.salary-structures.manage'))
        <button type="button" class="btn btn-sm btn-success" id="bulkConfigureBtn">
            <i class="fas fa-sync-alt me-1"></i>Bulk Configure
        </button>
        <button type="button" class="btn btn-sm btn-info" id="importRatesBtn">
            <i class="fas fa-upload me-1"></i>Import Rates
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
                            <p class="mb-0 text-white">Total Structures</p>
                            <h4 class="my-1 text-white">{{ $salaryStructures->count() }}</h4>
                        </div>
                        <div class="text-white ms-auto font-35">
                            <i class="bx bx-sitemap"></i>
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
                            <p class="mb-0 text-white">Configured Structures</p>
                            <h4 class="my-1 text-white">{{ $salaryStructures->filter(function($s) { return $s->gradeLevels->isNotEmpty(); })->count() }}</h4>
                        </div>
                        <div class="text-white ms-auto font-35">
                            <i class="bx bx-check-circle"></i>
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
                            <p class="mb-0 text-white">Total Grade Levels</p>
                            <h4 class="my-1 text-white">{{ $salaryStructures->sum(function($s) { return $s->gradeLevels->count(); }) }}</h4>
                        </div>
                        <div class="text-white ms-auto font-35">
                            <i class="bx bx-layer"></i>
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
                            <p class="mb-0 text-white">Total Steps</p>
                            <h4 class="my-1 text-white">{{ $salaryStructures->sum(function($s) { return $s->gradeLevels->sum(function($l) { return $l->steps->count(); }); }) }}</h4>
                        </div>
                        <div class="text-white ms-auto font-35">
                            <i class="bx bx-list-ol"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Data Card -->
    <div class="card">
        <div class="card-body">
            <div class="d-lg-flex align-items-center mb-4 gap-3">
                <div class="position-relative">
                    <input type="text" class="form-control ps-5 radius-30" placeholder="Search Salary Structures..."> 
                    <span class="position-absolute top-50 product-show translate-middle-y">
                        <i class="bx bx-search"></i>
                    </span>
                </div>
                <div class="ms-auto">
                    @if(auth()->user()->can('manage_payroll_rates'))
                        <button type="button" class="btn btn-primary radius-30 mt-2 mt-lg-0" id="configureBulkBtn">
                            <i class="bx bxs-cog"></i>Configure All Rates
                        </button>
                    @endif
                </div>
            </div>

            @if($salaryStructures->isEmpty())
                <div class="alert alert-info text-center">
                    <div class="mb-3">
                        <i class="fas fa-info-circle fa-3x text-info"></i>
                    </div>
                    <h5><strong>No Salary Structures Found</strong></h5>
                    <p class="mb-3">You need to create salary structures and grade levels first before setting up rates.</p>
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Salary Structure
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <div class="row">
                        @foreach($salaryStructures as $structure)
                                @php
                                    $totalSteps = $structure->gradeLevels->sum(function($level) { 
                                        return $level->steps->count(); 
                                    });
                                    $ratesCount = 0;
                                    foreach($structure->gradeLevels as $level) {
                                        foreach($level->steps as $step) {
                                            if($step->salaryStructureRates()->where('is_current', true)->exists()) {
                                                $ratesCount++;
                                            }
                                        }
                                    }
                                    $progress = $totalSteps > 0 ? ($ratesCount / $totalSteps) * 100 : 0;
                                @endphp
                                
                                <div class="col-lg-6 col-xl-4 mb-4">
                                    <div class="card border h-100 structure-card" data-structure-id="{{ $structure->id }}">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="card-title mb-0 text-white">
                                                <i class="fas fa-building me-2"></i>
                                                {{ $structure->name }}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Statistics Row -->
                                            <div class="row text-center mb-3">
                                                <div class="col-4">
                                                    <div class="stat-item">
                                                        <h4 class="fw-bold text-primary mb-1">{{ $structure->gradeLevels->count() }}</h4>
                                                        <p class="text-muted small mb-0">Grade Levels</p>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="stat-item">
                                                        <h4 class="fw-bold text-success mb-1">{{ $totalSteps }}</h4>
                                                        <p class="text-muted small mb-0">Total Steps</p>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="stat-item">
                                                        <h4 class="fw-bold text-warning mb-1">{{ $ratesCount }}</h4>
                                                        <p class="text-muted small mb-0">Rates Set</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Progress Bar -->
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <small class="text-muted">Configuration Progress</small>
                                                    <small class="fw-bold">{{ number_format($progress, 1) }}%</small>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar 
                                                        @if($progress < 25) bg-danger
                                                        @elseif($progress < 50) bg-warning
                                                        @elseif($progress < 75) bg-info
                                                        @else bg-success
                                                        @endif" 
                                                         role="progressbar" 
                                                         style="width: {{ $progress }}%" 
                                                         aria-valuenow="{{ $progress }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Status Badge -->
                                            <div class="text-center mb-3">
                                                @if($progress == 100)
                                                    <span class="badge bg-success fs-6">
                                                        <i class="fas fa-check-circle me-1"></i>Complete
                                                    </span>
                                                @elseif($progress > 50)
                                                    <span class="badge bg-info fs-6">
                                                        <i class="fas fa-clock me-1"></i>In Progress
                                                    </span>
                                                @elseif($progress > 0)
                                                    <span class="badge bg-warning fs-6">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Started
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary fs-6">
                                                        <i class="fas fa-minus-circle me-1"></i>Not Started
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="text-center">
                                                <a href="{{ route('payroll.salary-structure-rates.setup', $structure->id) }}" 
                                                   class="btn btn-primary btn-sm me-2 setup-btn">
                                                    <i class="fas fa-cog me-1"></i>Setup Rates
                                                </a>
                                                
                                                @if($ratesCount > 0)
                                                    <div class="btn-group">
                                                        <a href="{{ route('payroll.salary-structure-rates.export', $structure->id) }}" 
                                                           class="btn btn-outline-success btn-sm">
                                                            <i class="fas fa-download me-1"></i>Export
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-outline-info btn-sm" 
                                                                onclick="previewStructure('{{ $structure->id }}')">
                                                            <i class="fas fa-eye me-1"></i>Preview
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>

                                            @if($structure->description)
                                                <div class="mt-3 pt-3 border-top">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        {{ $structure->description }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
        </div>
    </div>

@stop

@section('side-panel')
    <div class="card radius-5 border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div>
                <h5 class="card-title">Salary Structure Rates</h5>
            </div>
            <p class="small">
                Configure base salary rates, allowances, and deductions for all grade level steps. 
                This ensures consistency in payroll processing across the organization.
            </p>
            
            <div class="mt-3">
                <h6>Rate Types:</h6>
                <ul class="small">
                    <li><strong>Base Salary</strong> - Primary salary amount</li>
                    <li><strong>Standard Allowances</strong> - Regular allowances</li>
                    <li><strong>Standard Deductions</strong> - Regular deductions</li>
                    <li><strong>Effective Dates</strong> - Rate validity periods</li>
                </ul>
            </div>
            
            <div class="mt-3">
                <h6>Quick Actions:</h6>
                <ul class="small">
                    <li>Setup rates for individual steps</li>
                    <li>Bulk configure all rates</li>
                    <li>Import rates from CSV/Excel</li>
                    <li>Export rate configurations</li>
                </ul>
            </div>
        </div>
    </div>
@stop

<!-- Structure Preview Modal -->
<div class="modal fade" id="structurePreviewModal" tabindex="-1" aria-labelledby="structurePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="structurePreviewModalLabel">
                    <i class="fas fa-eye me-2"></i>Structure Preview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="structurePreviewContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading structure preview...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
<script type="module">
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Structure card hover effects
    $('.structure-card').on('mouseenter', function() {
        $(this).addClass('border-primary');
    }).on('mouseleave', function() {
        $(this).removeClass('border-primary');
    });
});

function previewStructure(structureId) {
    $('#structurePreviewModal').modal('show');
    
    // Reset content
    $('#structurePreviewContent').html(`
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading structure preview...</p>
        </div>
    `);
    
    // Load preview content via AJAX
    setTimeout(function() {
        $('#structurePreviewContent').html(`
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Structure preview functionality will be implemented to show detailed breakdown of all rates.
            </div>
        `);
    }, 1000);
}
</script>
@endpush