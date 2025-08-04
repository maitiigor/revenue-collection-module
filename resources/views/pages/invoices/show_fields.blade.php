<!-- Invoice Number Field -->
<div id="div_invoice_invoice_number" class="col-lg-12">
    <p>
        {!! Form::label('invoice_number', 'Invoice Number:', ['class'=>'control-label']) !!} 
        <span id="spn_invoice_invoice_number">
        @if (isset($invoice->invoice_number) && empty($invoice->invoice_number)==false)
            {!! $invoice->invoice_number !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Amount Field -->
<div id="div_invoice_amount" class="col-lg-12">
    <p>
        {!! Form::label('amount', 'Amount:', ['class'=>'control-label']) !!} 
        <span id="spn_invoice_amount">
        @if (isset($invoice->amount) && empty($invoice->amount)==false)
            {!! $invoice->amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Amount Field -->
<div id="div_invoice_amount" class="col-lg-12">
    <p>
        {!! Form::label('amount', 'Amount:', ['class'=>'control-label']) !!} 
        <span id="spn_invoice_amount">
        @if (isset($invoice->amount) && empty($invoice->amount)==false)
            {!! $invoice->amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Invoice Date Field -->
<div id="div_invoice_invoice_date" class="col-lg-12">
    <p>
        {!! Form::label('invoice_date', 'Invoice Date:', ['class'=>'control-label']) !!} 
        <span id="spn_invoice_invoice_date">
        @if (isset($invoice->invoice_date) && empty($invoice->invoice_date)==false)
            {!! $invoice->invoice_date !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Invoice Due Date Field -->
<div id="div_invoice_invoice_due_date" class="col-lg-12">
    <p>
        {!! Form::label('invoice_due_date', 'Invoice Due Date:', ['class'=>'control-label']) !!} 
        <span id="spn_invoice_invoice_due_date">
        @if (isset($invoice->invoice_due_date) && empty($invoice->invoice_due_date)==false)
            {!! $invoice->invoice_due_date !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

