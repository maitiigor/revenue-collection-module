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
 * Class ComplianceReport
 * @package Maitiigor\RC\Models
 * @version August 4, 2025, 12:27 am UTC
 *
 * @property \Maitiigor\RC\Models\Organization $organization
 * @property \Maitiigor\RC\Models\Organization $organization1
 * @property \Maitiigor\RC\Models\Project $project
 * @property \Maitiigor\RC\Models\User $user
 * @property string $id
 * @property string $organization_id
 * @property string $project_id
 * @property string $inspector_id
 * @property string $status
 * @property string $remarks
 * @property string $report_date
 */
class ComplianceReport extends Model
{
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'rc_compliance_reports';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'organization_id',
        'project_id',
        'inspector_id',
        'status',
        'remarks',
        'report_date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'string',
        'remarks' => 'string',
        'report_date' => 'date'
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
    public function organization1()
    {
        return $this->hasOne(\Maitiigor\RC\Models\Organization::class, 'organization_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function project()
    {
        return $this->hasOne(\Maitiigor\RC\Models\Project::class, 'project_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function user()
    {
        return $this->hasOne(\Maitiigor\RC\Models\User::class, 'inspector_id', 'id');
    }

}
