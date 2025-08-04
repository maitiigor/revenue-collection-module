<?php

namespace Maitiigor\Payroll\Tests;

use Hash;
use Carbon\Carbon;
use Maitiigor\FoundationCore\Models\User;
use Maitiigor\FoundationCore\Models\Attachment;
use Maitiigor\FoundationCore\Models\Department;
use Maitiigor\FoundationCore\Models\Organization;
use Maitiigor\Payroll\ServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;
class TestCase extends BaseTestCase
{
        
    public function setup() : void
    {
        parent::setUp();
        $this->artisan('migrate', []);

        $this->test_org = Organization::firstOrCreate([
            'org' => 'app',
            'domain' => 'test',
            'full_url' => 'www.app.test',
            'subdomain' => 'sub',
            'is_local_default_organization' => true,
        ]);
        $this->test_org->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $this->test_org->save();

        $this->test_dept = Department::firstOrCreate([
            'key' => 'ict-admin',
            'long_name' => 'ICT Admin',
            'email' => 'ict-admin@app.com',
            'telephone' => '07085554141',
            'physical_location' => '2nd Floor, Room 20 - 28',
            'organization_id' => $this->test_org->id,
        ]);
        $this->test_dept->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $this->test_dept->save();

        $this->test_admin = User::where('email', 'admin@app.com')->first();
        
        if(is_null($this->test_admin))
        {
            $this->test_admin = User::firstOrCreate([
                'email' => 'admin@app.com',
                'telephone' => '07063321200',
                'password' => Hash::make('password'),
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'organization_id' => $this->test_org->id,
                'last_loggedin_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
            
        Sanctum::actingAs(
            $this->test_admin,
            ['*']
        );
    }


}
