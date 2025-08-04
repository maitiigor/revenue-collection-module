<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payroll Processing Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the enhanced payroll processing system
    |
    */

    'processing' => [
        /*
        |--------------------------------------------------------------------------
        | Batch Size
        |--------------------------------------------------------------------------
        |
        | Number of employees to process in a single batch. Smaller batches
        | are more stable but slower. Larger batches are faster but may
        | consume more memory.
        |
        */
        'batch_size' => env('PAYROLL_BATCH_SIZE', 50),

        /*
        |--------------------------------------------------------------------------
        | Timeout
        |--------------------------------------------------------------------------
        |
        | Maximum time in seconds to allow for processing a single batch
        | of employees. Increase for larger batches or complex calculations.
        |
        */
        'timeout' => env('PAYROLL_TIMEOUT', 300), // 5 minutes

        /*
        |--------------------------------------------------------------------------
        | Memory Limit
        |--------------------------------------------------------------------------
        |
        | Memory limit for payroll processing. Increase for larger organizations
        | or complex salary structures.
        |
        */
        'memory_limit' => env('PAYROLL_MEMORY_LIMIT', '512M'),

        /*
        |--------------------------------------------------------------------------
        | Queue Processing
        |--------------------------------------------------------------------------
        |
        | Enable queue processing for better performance and scalability.
        | Requires queue worker to be running.
        |
        */
        'use_queue' => env('PAYROLL_USE_QUEUE', false),

        /*
        |--------------------------------------------------------------------------
        | Queue Connection
        |--------------------------------------------------------------------------
        |
        | Queue connection to use for processing payroll jobs.
        |
        */
        'queue_connection' => env('PAYROLL_QUEUE_CONNECTION', 'default'),

        /*
        |--------------------------------------------------------------------------
        | Progress Cache TTL
        |--------------------------------------------------------------------------
        |
        | Time in seconds to keep progress information in cache.
        |
        */
        'progress_cache_ttl' => env('PAYROLL_PROGRESS_CACHE_TTL', 3600), // 1 hour
    ],

    'validation' => [
        /*
        |--------------------------------------------------------------------------
        | Validation Rules
        |--------------------------------------------------------------------------
        |
        | Configuration for payroll validation checks
        |
        */
        'required_fields' => [
            'employee' => ['first_name', 'last_name', 'employee_number'],
            'salary_assignment' => ['grade_level_step_id', 'effective_date'],
            'salary_structure_rate' => ['base_salary'],
        ],

        /*
        |--------------------------------------------------------------------------
        | Warning Thresholds
        |--------------------------------------------------------------------------
        |
        | Thresholds for generating warnings during validation
        |
        */
        'warnings' => [
            'missing_bank_info' => true,
            'zero_salary' => true,
            'negative_final_pay' => true,
            'high_deduction_percentage' => 80, // Warn if deductions > 80% of gross
        ],

        /*
        |--------------------------------------------------------------------------
        | Error Conditions
        |--------------------------------------------------------------------------
        |
        | Conditions that should cause processing to fail
        |
        */
        'errors' => [
            'missing_salary_assignment' => true,
            'missing_salary_structure_rate' => true,
            'invalid_grade_level_step' => true,
        ],
    ],

    'calculations' => [
        /*
        |--------------------------------------------------------------------------
        | Rounding Precision
        |--------------------------------------------------------------------------
        |
        | Number of decimal places for monetary calculations
        |
        */
        'precision' => 2,

        /*
        |--------------------------------------------------------------------------
        | Rounding Mode
        |--------------------------------------------------------------------------
        |
        | PHP rounding mode to use for calculations
        | PHP_ROUND_HALF_UP, PHP_ROUND_HALF_DOWN, PHP_ROUND_HALF_EVEN, PHP_ROUND_HALF_ODD
        |
        */
        'rounding_mode' => PHP_ROUND_HALF_UP,

        /*
        |--------------------------------------------------------------------------
        | Tax Calculations
        |--------------------------------------------------------------------------
        |
        | Configuration for tax calculations
        |
        */
        'tax' => [
            'annual_calculation' => true, // Calculate tax annually then divide by 12
            'graduated_rates' => true, // Use graduated tax rates
            'exempt_minimum' => 200000, // Minimum exempt amount annually
        ],
    ],

    'security' => [
        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        |
        | Enable audit trail for payroll processing activities
        |
        */
        'audit_enabled' => env('PAYROLL_AUDIT_ENABLED', true),

        /*
        |--------------------------------------------------------------------------
        | Backup Before Processing
        |--------------------------------------------------------------------------
        |
        | Automatically backup payroll data before processing
        |
        */
        'auto_backup' => env('PAYROLL_AUTO_BACKUP', true),

        /*
        |--------------------------------------------------------------------------
        | Access Control
        |--------------------------------------------------------------------------
        |
        | Additional security checks for payroll processing
        |
        */
        'require_approval' => env('PAYROLL_REQUIRE_APPROVAL', false),
        'require_two_factor' => env('PAYROLL_REQUIRE_2FA', false),
    ],

    'notifications' => [
        /*
        |--------------------------------------------------------------------------
        | Email Notifications
        |--------------------------------------------------------------------------
        |
        | Configuration for email notifications
        |
        */
        'enabled' => env('PAYROLL_NOTIFICATIONS_ENABLED', true),
        'send_to_employees' => env('PAYROLL_NOTIFY_EMPLOYEES', false),
        'send_to_admins' => env('PAYROLL_NOTIFY_ADMINS', true),
        
        /*
        |--------------------------------------------------------------------------
        | Notification Templates
        |--------------------------------------------------------------------------
        |
        | Email templates for different notifications
        |
        */
        'templates' => [
            'processing_started' => 'payroll::emails.processing-started',
            'processing_completed' => 'payroll::emails.processing-completed',
            'processing_failed' => 'payroll::emails.processing-failed',
            'employee_payslip' => 'payroll::emails.employee-payslip',
        ],
    ],

    'reports' => [
        /*
        |--------------------------------------------------------------------------
        | Export Formats
        |--------------------------------------------------------------------------
        |
        | Available formats for exporting payroll data
        |
        */
        'formats' => ['excel', 'pdf', 'csv'],
        
        /*
        |--------------------------------------------------------------------------
        | Default Export Format
        |--------------------------------------------------------------------------
        |
        | Default format for exporting payroll data
        |
        */
        'default_format' => 'excel',

        /*
        |--------------------------------------------------------------------------
        | Export Includes
        |--------------------------------------------------------------------------
        |
        | What to include in exports by default
        |
        */
        'include' => [
            'employee_details' => true,
            'allowances_breakdown' => true,
            'deductions_breakdown' => true,
            'bank_details' => false, // Security sensitive
            'tax_details' => true,
        ],
    ],

    'integration' => [
        /*
        |--------------------------------------------------------------------------
        | External Systems
        |--------------------------------------------------------------------------
        |
        | Configuration for integration with external systems
        |
        */
        'bank_integration' => [
            'enabled' => env('PAYROLL_BANK_INTEGRATION', false),
            'provider' => env('PAYROLL_BANK_PROVIDER', null),
            'auto_generate_files' => env('PAYROLL_AUTO_BANK_FILES', false),
        ],

        'accounting_integration' => [
            'enabled' => env('PAYROLL_ACCOUNTING_INTEGRATION', false),
            'provider' => env('PAYROLL_ACCOUNTING_PROVIDER', null),
            'auto_post_entries' => env('PAYROLL_AUTO_ACCOUNTING_POSTS', false),
        ],

        'hr_integration' => [
            'enabled' => true, // Always enabled for hr-core-module
            'sync_employee_data' => true,
            'sync_grade_changes' => true,
        ],
    ],
];