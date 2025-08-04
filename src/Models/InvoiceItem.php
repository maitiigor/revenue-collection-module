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
 * Class InvoiceItem
 * @package Maitiigor\RC\Models
 * @version August 4, 2025, 12:23 am UTC
 *
 * @property \Maitiigor\RC\Models\Organization $organization
 * @property \Maitiigor\RC\Models\Invoice $invoice
 * @property string $id
 * @property string $organization_id
 * @property string $invoice_id
 * @property string $title
 * @property string $description
 * @property number $quantity
 * @property number $unit_price
 * @property integer $total_amount
 * @property string $item_type
 */
class InvoiceItem extends Model
{
    use GuidId;
    use OrganizationalConstraint;
    
    use SoftDeletes;

    use HasFactory;

    public $table = 'rc_invoice_items';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'id',
        'organization_id',
        'invoice_id',
        'title',
        'description',
        'quantity',
        'unit_price',
        'total_amount',
        'item_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'integer',
        'item_type' => 'string'
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
    public function invoice()
    {
        return $this->hasOne(\Maitiigor\RC\Models\Invoice::class, 'invoice_id');
    }

}
