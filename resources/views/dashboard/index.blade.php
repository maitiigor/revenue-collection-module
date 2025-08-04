@extends('layouts.app')

@section('page_title_buttons')
    @if(auth()->user()->hasAnyRole(['payroll-admin', 'payroll-manager', 'super-admin']))
        <a href="{{ route('payroll.periods.create') }}" class="btn btn-primary">
            <i class="mdi mdi-plus"></i> New Payroll Period
        </a>
    @endif
@stop

@section('title_postfix')
    Payroll Dashboard
@endsection

@section('page_title_suffix')
    Payroll Management Overview
@endsection

@push('page_css')
    <style>
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .widget-chart {
            min-height: 300px;
        }
        .payroll-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .currency-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
<div class="row" id="payroll-dashboard">
    <!-- Summary Statistics -->
    <div class="col-lg-6">
        <div class="card payroll-summary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-lg rounded-circle bg-white bg-opacity-25">
                        <span class="avatar-title">
                            <i class="mdi mdi-currency-ngn text-white font-size-24"></i>
                        </span>
                    </div>
                    <div class="flex-1 ms-3">
                        <h3 class="mb-1 currency-display" id="total-payroll-expense">Loading...</h3>
                        <p class="mb-0 opacity-75">Total Payroll Expense</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="row">
            <div class="col-md-6">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm rounded-circle bg-success">
                                <span class="avatar-title">
                                    <i class="mdi mdi-account-cash text-white font-size-20"></i>
                                </span>
                            </div>
                            <div class="flex-1 ms-3">
                                <h5 class="font-size-16 mb-1" id="employees-on-payroll">Loading...</h5>
                                <p class="text-muted mb-0">Employees on Payroll</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm rounded-circle bg-info">
                                <span class="avatar-title">
                                    <i class="mdi mdi-chart-line text-white font-size-20"></i>
                                </span>
                            </div>
                            <div class="flex-1 ms-3">
                                <h5 class="font-size-16 mb-1 currency-display" id="average-salary">Loading...</h5>
                                <p class="text-muted mb-0">Average Salary</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Month Summary -->
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle bg-primary">
                        <span class="avatar-title">
                            <i class="mdi mdi-calendar-month text-white font-size-20"></i>
                        </span>
                    </div>
                    <div class="flex-1 ms-3">
                        <h5 class="font-size-16 mb-1 currency-display" id="current-month-payroll">Loading...</h5>
                        <p class="text-muted mb-0">Current Month</p>
                        <small class="text-success" id="payroll-change">Loading...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle bg-success">
                        <span class="avatar-title">
                            <i class="mdi mdi-check-circle text-white font-size-20"></i>
                        </span>
                    </div>
                    <div class="flex-1 ms-3">
                        <h5 class="font-size-16 mb-1" id="completed-periods">Loading...</h5>
                        <p class="text-muted mb-0">Completed Periods</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle bg-warning">
                        <span class="avatar-title">
                            <i class="mdi mdi-clock-outline text-white font-size-20"></i>
                        </span>
                    </div>
                    <div class="flex-1 ms-3">
                        <h5 class="font-size-16 mb-1" id="pending-periods">Loading...</h5>
                        <p class="text-muted mb-0">Pending Periods</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Monthly Payroll Trends</h4>
                <div id="payrollTrendsChart" class="widget-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Payroll Breakdown (Current Month)</h4>
                <div id="payrollBreakdownChart" class="widget-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Salary Structure Distribution</h4>
                <div id="salaryStructureChart" class="widget-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Department Payroll Comparison</h4>
                <div id="departmentPayrollChart" class="widget-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Allowances vs Deductions</h4>
                <div id="allowancesDeductionsChart" class="widget-chart"></div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Recent Payroll Activities</h4>
                <div id="recent-activities">Loading...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
<script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadPayrollDashboardData();
});

function loadPayrollDashboardData() {
    const organizationId = '{{ $organization->id ?? '' }}';
    
    fetch(`/payroll/api/dashboard`)
        .then(response => response.json())
        .then(data => {
            updateSummaryStats(data.summary);
            renderCharts(data.charts);
            renderRecentActivities(data.recent_activities);
        })
        .catch(error => {
            console.error('Error loading Payroll dashboard data:', error);
        });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-NG', {
        style: 'currency',
        currency: 'NGN'
    }).format(amount || 0);
}

function updateSummaryStats(summary) {
    document.getElementById('total-payroll-expense').textContent = formatCurrency(summary.total_payroll_expense);
    document.getElementById('current-month-payroll').textContent = formatCurrency(summary.current_month_payroll);
    document.getElementById('employees-on-payroll').textContent = summary.employees_on_payroll || 0;
    document.getElementById('completed-periods').textContent = summary.completed_payroll_periods || 0;
    document.getElementById('pending-periods').textContent = summary.pending_payroll_periods || 0;
    document.getElementById('average-salary').textContent = formatCurrency(summary.average_salary);
    
    // Display payroll change percentage
    const changeElement = document.getElementById('payroll-change');
    const change = summary.payroll_change_percentage || 0;
    changeElement.textContent = `${change >= 0 ? '+' : ''}${change}% from last month`;
    changeElement.className = change >= 0 ? 'text-success' : 'text-danger';
}

function renderCharts(charts) {
    // Monthly Payroll Trends Chart
    if (charts.monthly_payroll_trends) {
        const trendsOptions = {
            chart: {
                type: 'line',
                height: 350
            },
            series: [{
                name: 'Total Payroll',
                data: charts.monthly_payroll_trends.map(item => item.total)
            }, {
                name: 'Employee Count',
                data: charts.monthly_payroll_trends.map(item => item.employee_count),
                yaxis: 1
            }],
            xaxis: {
                categories: charts.monthly_payroll_trends.map(item => item.month)
            },
            yaxis: [{
                title: {
                    text: 'Amount (₦)'
                }
            }, {
                opposite: true,
                title: {
                    text: 'Employee Count'
                }
            }],
            colors: ["#3980c0", "#51af98"]
        };
        const trendsChart = new ApexCharts(document.querySelector("#payrollTrendsChart"), trendsOptions);
        trendsChart.render();
    }

    // Payroll Breakdown Chart
    if (charts.payroll_breakdown) {
        const breakdown = charts.payroll_breakdown;
        const breakdownOptions = {
            chart: {
                type: 'donut',
                height: 300
            },
            series: [breakdown.basic_salary, breakdown.allowances, breakdown.bonuses],
            labels: ['Basic Salary', 'Allowances', 'Bonuses'],
            colors: ["#3980c0", "#51af98", "#4bafe1"],
            legend: {
                position: 'bottom'
            }
        };
        const breakdownChart = new ApexCharts(document.querySelector("#payrollBreakdownChart"), breakdownOptions);
        breakdownChart.render();
    }

    // Salary Structure Distribution Chart
    if (charts.salary_structure_distribution) {
        const structureOptions = {
            chart: {
                type: 'pie',
                height: 300
            },
            series: charts.salary_structure_distribution.map(item => item.value),
            labels: charts.salary_structure_distribution.map(item => item.label),
            colors: ["#3980c0", "#51af98", "#4bafe1", "#B4B4B5", "#f1f3f4"],
            legend: {
                position: 'bottom'
            }
        };
        const structureChart = new ApexCharts(document.querySelector("#salaryStructureChart"), structureOptions);
        structureChart.render();
    }

    // Department Payroll Comparison Chart
    if (charts.department_payroll_comparison) {
        const deptOptions = {
            chart: {
                type: 'bar',
                height: 300
            },
            series: [{
                name: 'Payroll Amount',
                data: charts.department_payroll_comparison.map(item => item.value)
            }],
            xaxis: {
                categories: charts.department_payroll_comparison.map(item => item.label)
            },
            colors: ["#3980c0"]
        };
        const deptChart = new ApexCharts(document.querySelector("#departmentPayrollChart"), deptOptions);
        deptChart.render();
    }

    // Allowances vs Deductions Chart
    if (charts.allowances_vs_deductions) {
        const allowDeductOptions = {
            chart: {
                type: 'bar',
                height: 300
            },
            series: [{
                name: 'Amount',
                data: [charts.allowances_vs_deductions.allowances, charts.allowances_vs_deductions.deductions]
            }],
            xaxis: {
                categories: ['Allowances', 'Deductions']
            },
            colors: ["#51af98", "#ff6b6b"]
        };
        const allowDeductChart = new ApexCharts(document.querySelector("#allowancesDeductionsChart"), allowDeductOptions);
        allowDeductChart.render();
    }
}

function renderRecentActivities(activities) {
    const container = document.getElementById('recent-activities');
    
    if (!activities || activities.length === 0) {
        container.innerHTML = '<p class="text-muted">No recent activities.</p>';
        return;
    }

    let html = '<div class="timeline">';
    activities.forEach(activity => {
        html += `
            <div class="timeline-item">
                <div class="timeline-marker bg-${activity.color}">
                    <i class="${activity.icon}"></i>
                </div>
                <div class="timeline-content">
                    <h6 class="timeline-title">${activity.title}</h6>
                    <p class="timeline-description">${activity.description}</p>
                    <small class="text-muted">${new Date(activity.date).toLocaleDateString()}</small>
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    container.innerHTML = html;
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 3rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -2.5rem;
    top: 2rem;
    width: 2px;
    height: calc(100% - 1rem);
    background-color: #e9ecef;
}

.timeline-marker {
    position: absolute;
    left: -3rem;
    top: 0.25rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
}

.timeline-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    border-left: 3px solid #dee2e6;
}

.timeline-title {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.timeline-description {
    margin-bottom: 0.5rem;
    color: #6c757d;
}
</style>
@endpush