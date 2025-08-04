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
        Schema::create('rc_invoice_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->references('id')->on('fc_organizations');
            $table->foreignUuid('invoice_id')->references('id')->on('rc_invoices');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->decimal('quantity')->default(10, 2)->default(1);
            $table->decimal('unit_price')->default(10, 2)->default(0);
            $table->integer('total_amount')->default(0);
            $table->enum('item_type', ['base_tax', 'penalty', 'processing_fee', 'discount'])->default('base_tax');
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
        Schema::drop('rc_invoice_items');
    }
};
