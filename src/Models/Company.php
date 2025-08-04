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
 * Class Company
 * @package Maitiigor\RC\Models
 * @version August 4, 2025, 12:19 am UTC
 *
 * @property string $organization_id
 * @property string $name
 * @property string $short_name
 * @property string $status
 */
class Company extends Model
{
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'rc_companies';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'organization_id',
        'name',
        'short_name',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'display_ordinal' => 'integer',
        'name' => 'string',
        'short_name' => 'string',
        'rcc_number' => 'string',
        'irr_number' => 'string',
        'firs_vat_number' => 'string',
        'tin' => 'string',
        'is_verfied' => 'boolean',
        'contact_person' => 'string',
        'contact_number' => 'string',
        'email_address' => 'string',
        'website_url' => 'string',
        'status' => 'string'
    ];


    

}
