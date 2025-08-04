<!-- Invoice Number Field -->
<div id="div-invoice_number" class="form-group">
    <label for="invoice_number" class="col-lg-3 col-form-label">Invoice Number</label>
    <div class="col-lg-9">
        {!! Form::text('invoice_number', null, ['id'=>'invoice_number', 'class' => 'form-control','maxlength' => 200]) !!}
    </div>
</div>

<!-- Start Amount Field -->
<div id="div-amount" class="form-group">
    <label for="amount" class="col-lg-3 col-form-label">Amount</label>
    <div class="col-lg-9">
        {!! Form::number('amount', null, ['id'=>'amount', 'class' => 'form-control','min' => -99999.99,'max' => 99999.99]) !!}
    </div>
</div>
<!-- End Amount Field -->

<!-- Start Amount Field -->
<div id="div-amount" class="form-group">
    <label for="amount" class="col-lg-3 col-form-label">Amount</label>
    <div class="col-lg-9">
        {!! Form::number('amount', null, ['id'=>'amount', 'class' => 'form-control','min' => -99999.99,'max' => 99999.99]) !!}
    </div>
</div>
<!-- End Amount Field -->

<!-- Invoice Date Field -->
<div id="div-invoice_date" class="form-group">
    <label for="invoice_date" class="col-lg-3 col-form-label">Invoice Date</label>
    <div class="col-lg-9">
        {!! Form::text('invoice_date', null, ['id'=>'invoice_date', 'class' => 'form-control']) !!}
    </div>
</div>

<!-- Invoice Due Date Field -->
<div id="div-invoice_due_date" class="form-group">
    <label for="invoice_due_date" class="col-lg-3 col-form-label">Invoice Due Date</label>
    <div class="col-lg-9">
        {!! Form::text('invoice_due_date', null, ['id'=>'invoice_due_date', 'class' => 'form-control']) !!}
    </div>
</div>