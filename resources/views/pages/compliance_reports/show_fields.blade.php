<!-- Status Field -->
<div id="div_complianceReport_status" class="col-lg-12">
    <p>
        {!! Form::label('status', 'Status:', ['class'=>'control-label']) !!} 
        <span id="spn_complianceReport_status">
        @if (isset($complianceReport->status) && empty($complianceReport->status)==false)
            {!! $complianceReport->status !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Remarks Field -->
<div id="div_complianceReport_remarks" class="col-lg-12">
    <p>
        {!! Form::label('remarks', 'Remarks:', ['class'=>'control-label']) !!} 
        <span id="spn_complianceReport_remarks">
        @if (isset($complianceReport->remarks) && empty($complianceReport->remarks)==false)
            {!! $complianceReport->remarks !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Report Date Field -->
<div id="div_complianceReport_report_date" class="col-lg-12">
    <p>
        {!! Form::label('report_date', 'Report Date:', ['class'=>'control-label']) !!} 
        <span id="spn_complianceReport_report_date">
        @if (isset($complianceReport->report_date) && empty($complianceReport->report_date)==false)
            {!! $complianceReport->report_date !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

