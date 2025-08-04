<?php

namespace Maitiigor\RC\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Maitiigor\FoundationCore\Models\Setting;

class RevenueCollectionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Current organization
        $org = \FoundationCore::current_organization();

        //Roles in this application with their roles.
        \FoundationCore::register_roles([

        ]);

        $app_settings = [
            // 'allow_broker_register_investor'=>['group_name'=>'DMO','display_type'=>'boolean','display_name'=>'Allow Broker Register Investor','display_ordinal'=>1],

        ];


        if (Schema::hasTable('fc_organizations') && Schema::hasTable('fc_settings')) {

            if ($org != null && \FoundationCore::has_feature('case-mgt', $org)) {

                foreach ($app_settings as $key => $setting) {
                    \FoundationCore::register_setting(
                        $org,
                        $key,
                        $setting['group_name'],
                        $setting['display_type'],
                        $setting['display_name'],
                        "payroll-module",
                        $setting['display_ordinal']
                    );
                }

                $setting_list = Setting::whereIn('key', array_keys($app_settings))->get();

                $app_setting_values = $setting_list->mapWithKeys(function ($item, $key) {
                    return [$item->key => $item->value];
                });



                // \FoundationCore::register_workable_model($org, [
                //   \Maitiigor\Payroll\Models\LawCase::class
                //]);


            }

        }


    }

}
