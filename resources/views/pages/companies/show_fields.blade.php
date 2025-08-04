<!-- Name Field -->
<div id="div_company_name" class="col-lg-12">
    <p>
        {!! Form::label('name', 'Name:', ['class'=>'control-label']) !!} 
        <span id="spn_company_name">
        @if (isset($company->name) && empty($company->name)==false)
            {!! $company->name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Short Name Field -->
<div id="div_company_short_name" class="col-lg-12">
    <p>
        {!! Form::label('short_name', 'Short Name:', ['class'=>'control-label']) !!} 
        <span id="spn_company_short_name">
        @if (isset($company->short_name) && empty($company->short_name)==false)
            {!! $company->short_name !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Rcc Number Field -->
<div id="div_company_rcc_number" class="col-lg-12">
    <p>
        {!! Form::label('rcc_number', 'Rcc Number:', ['class'=>'control-label']) !!} 
        <span id="spn_company_rcc_number">
        @if (isset($company->rcc_number) && empty($company->rcc_number)==false)
            {!! $company->rcc_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Irr Number Field -->
<div id="div_company_irr_number" class="col-lg-12">
    <p>
        {!! Form::label('irr_number', 'Irr Number:', ['class'=>'control-label']) !!} 
        <span id="spn_company_irr_number">
        @if (isset($company->irr_number) && empty($company->irr_number)==false)
            {!! $company->irr_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Firs Vat Number Field -->
<div id="div_company_firs_vat_number" class="col-lg-12">
    <p>
        {!! Form::label('firs_vat_number', 'Firs Vat Number:', ['class'=>'control-label']) !!} 
        <span id="spn_company_firs_vat_number">
        @if (isset($company->firs_vat_number) && empty($company->firs_vat_number)==false)
            {!! $company->firs_vat_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Status Field -->
<div id="div_company_status" class="col-lg-12">
    <p>
        {!! Form::label('status', 'Status:', ['class'=>'control-label']) !!} 
        <span id="spn_company_status">
        @if (isset($company->status) && empty($company->status)==false)
            {!! $company->status !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

