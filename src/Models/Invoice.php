<?php

namespace Maitiigor\RC\Models;

use Hash;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use Maitiigor\Workflow\Traits\Workable;
use Maitiigor\FoundationCore\Traits\GuidId;
use Maitiigor\FoundationCore\Traits\Pageable;
use Maitiigor\FoundationCore\Traits\Disable;
use Maitiigor\FoundationCore\Traits\Ratable;
use Maitiigor\FoundationCore\Traits\Taggable;
use Maitiigor\FoundationCore\Traits\Ledgerable;
use Maitiigor\FoundationCore\Traits\Attachable;
use Maitiigor\FoundationCore\Traits\Artifactable;
use Maitiigor\FoundationCore\Traits\OrganizationalConstraint;

use Eloquent as Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Invoice
 * @package Maitiigor\RC\Models
 * @version August 4, 2025, 12:22 am UTC
 *
 * @property \Maitiigor\RC\Models\Organization $organization
 * @property \Maitiigor\RC\Models\Project $project
 * @property \Maitiigor\RC\Models\User $user
 * @property \Maitiigor\RC\Models\Ledger $ledger
 * @property string $id
 * @property string $organization_id
 * @property string $invoice_number
 * @property number $amount
 * @property string $project_id
 * @property string $creator_user_id
 * @property string $revenue_ledger_id
 * @property number $amount
 * @property string $invoice_date
 * @property string $invoice_due_date
 */
class Invoice extends Model
{
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'rc_invoices';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'organization_id',
        'invoice_number',
        'amount',
        'project_id',
        'creator_user_id',
        'revenue_ledger_id',
        'amount',
        'invoice_date',
        'invoice_due_date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'invoice_number' => 'string',
        'amount' => 'decimal:2',
        'status' => 'string',
        'amount' => 'decimal:2'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function organization()
    {
        return $this->hasOne(\Maitiigor\RC\Models\Organization::class, 'organization_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function project()
    {
        return $this->hasOne(\Maitiigor\RC\Models\Project::class, 'project_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function user()
    {
        return $this->hasOne(\Maitiigor\RC\Models\User::class, 'creator_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function ledger()
    {
        return $this->hasOne(\Maitiigor\RC\Models\Ledger::class, 'revenue_ledger_id');
    }

}
