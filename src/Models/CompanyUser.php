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
 * Class CompanyUser
 * @package Maitiigor\RC\Models
 * @version August 4, 2025, 12:20 am UTC
 *
 * @property string $organization_id
 * @property string $company_id
 * @property string $user_id
 * @property string $role
 */
class CompanyUser extends Model
{
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'rc_company_users';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'organization_id',
        'company_id',
        'user_id',
        'role'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'role' => 'string'
    ];


    

}
