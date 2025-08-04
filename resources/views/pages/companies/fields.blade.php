<!-- Name Field -->
<div id="div-name" class="form-group">
    <label for="name" class="col-lg-3 col-form-label">Name</label>
    <div class="col-lg-9">
        {!! Form::text('name', null, ['id'=>'name', 'class' => 'form-control']) !!}
    </div>
</div>

<!-- Short Name Field -->
<div id="div-short_name" class="form-group">
    <label for="short_name" class="col-lg-3 col-form-label">Short Name</label>
    <div class="col-lg-9">
        {!! Form::text('short_name', null, ['id'=>'short_name', 'class' => 'form-control']) !!}
    </div>
</div>

<!-- Status Field -->
<div id="div-status" class="form-group">
    <label for="status" class="col-lg-3 col-form-label">Status</label>
    <div class="col-lg-9">
        {!! Form::text('status', null, ['id'=>'status', 'class' => 'form-control']) !!}
    </div>
</div>