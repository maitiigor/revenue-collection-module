<!-- Title Field -->
<div id="div-title" class="form-group">
    <label for="title" class="col-lg-3 col-form-label">Title</label>
    <div class="col-lg-9">
        {!! Form::text('title', null, ['id'=>'title', 'class' => 'form-control','maxlength' => 200]) !!}
    </div>
</div>

<!-- Description Field -->
<div id="div-description" class="form-group">
    <label for="description" class="col-lg-3 col-form-label">Description</label>
    <div class="col-lg-9">
        {!! Form::text('description', null, ['id'=>'description', 'class' => 'form-control','maxlength' => 2000]) !!}
    </div>
</div>

<!-- Start Quantity Field -->
<div id="div-quantity" class="form-group">
    <label for="quantity" class="col-lg-3 col-form-label">Quantity</label>
    <div class="col-lg-9">
        {!! Form::number('quantity', null, ['id'=>'quantity', 'class' => 'form-control','min' => 1]) !!}
    </div>
</div>
<!-- End Quantity Field -->

<!-- Start Unit Price Field -->
<div id="div-unit_price" class="form-group">
    <label for="unit_price" class="col-lg-3 col-form-label">Unit Price</label>
    <div class="col-lg-9">
        {!! Form::number('unit_price', null, ['id'=>'unit_price', 'class' => 'form-control','min' => 0]) !!}
    </div>
</div>
<!-- End Unit Price Field -->

<!-- Start Total Amount Field -->
<div id="div-total_amount" class="form-group">
    <label for="total_amount" class="col-lg-3 col-form-label">Total Amount</label>
    <div class="col-lg-9">
        {!! Form::number('total_amount', null, ['id'=>'total_amount', 'class' => 'form-control','min' => 0]) !!}
    </div>
</div>
<!-- End Total Amount Field -->

<!-- Item Type Field -->
<div id="div-item_type" class="form-group">
    <label for="item_type" class="col-lg-3 col-form-label">Item Type</label>
    <div class="col-lg-9">
        {!! Form::select('item_type', ], null, ['id'=>'item_type', 'class' => 'form-control custom-select']) !!}
    </div>
</div>

