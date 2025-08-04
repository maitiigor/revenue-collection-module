<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rc_compliance_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->foreignUuid('project_id')->references('id')->on('rc_projects');
            $table->foreignUuid('inspector_id')->references('id')->on('fc_users');
            $table->enum('status', ['compliant', 'non_compliant', 'partial']);
            $table->text('remarks')->nullable();
            $table->date('report_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rc_compliance_reports');
    }
};
