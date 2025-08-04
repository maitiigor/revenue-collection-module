<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rc_invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->string('invoice_number', 200);
            $table->decimal('amount',12,2)->nullable();
            $table->string('status')->nullable();
            $table->foreignUuid('project_id')->nullable()->references('id')->on('rc_projects');
            $table->foreignUuid('creator_user_id')->nullable()->references('id')->on('fc_users');
            $table->foreignUuid('revenue_ledger_id')->nullable()->references('id')->on('fc_ledgers');
            $table->timestamp('invoice_date')->nullable();
            $table->timestamp('invoice_due_date')->nullable();
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
        Schema::drop('rc_invoices');
    }
};
