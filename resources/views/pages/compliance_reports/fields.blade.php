<!-- Status Field -->
<div id="div-status" class="form-group">
    <label for="status" class="col-lg-3 col-form-label">Status</label>
    <div class="col-lg-9">
        {!! Form::select('status', ], null, ['id'=>'status', 'class' => 'form-control custom-select']) !!}
    </div>
</div>



<!-- Remarks Field -->
<div id="div-remarks" class="form-group">
    <label for="remarks" class="col-lg-3 col-form-label">Remarks</label>
    <div class="col-lg-9">
        {!! Form::textarea('remarks', null, ['id'=>'remarks', 'rows'=>'3','class' => 'form-control','maxlength' => 2000]) !!}
    </div>
</div>

<!-- Start Report Date Field -->
<div id="div-report_date" class="form-group">
    <label for="report_date" class="col-lg-3 col-form-label">Report Date</label>
    <div class="col-lg-9">
        {!! Form::date('report_date', null, ['class' => 'form-control','id'=>'report_date']) !!}
    </div>
</div>
<!-- End Report Date Field -->