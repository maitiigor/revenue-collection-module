<!-- Title Field -->
<div id="div_invoiceItem_title" class="col-lg-12">
    <p>
        {!! Form::label('title', 'Title:', ['class'=>'control-label']) !!} 
        <span id="spn_invoiceItem_title">
        @if (isset($invoiceItem->title) && empty($invoiceItem->title)==false)
            {!! $invoiceItem->title !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Description Field -->
<div id="div_invoiceItem_description" class="col-lg-12">
    <p>
        {!! Form::label('description', 'Description:', ['class'=>'control-label']) !!} 
        <span id="spn_invoiceItem_description">
        @if (isset($invoiceItem->description) && empty($invoiceItem->description)==false)
            {!! $invoiceItem->description !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Quantity Field -->
<div id="div_invoiceItem_quantity" class="col-lg-12">
    <p>
        {!! Form::label('quantity', 'Quantity:', ['class'=>'control-label']) !!} 
        <span id="spn_invoiceItem_quantity">
        @if (isset($invoiceItem->quantity) && empty($invoiceItem->quantity)==false)
            {!! $invoiceItem->quantity !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Unit Price Field -->
<div id="div_invoiceItem_unit_price" class="col-lg-12">
    <p>
        {!! Form::label('unit_price', 'Unit Price:', ['class'=>'control-label']) !!} 
        <span id="spn_invoiceItem_unit_price">
        @if (isset($invoiceItem->unit_price) && empty($invoiceItem->unit_price)==false)
            {!! $invoiceItem->unit_price !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Total Amount Field -->
<div id="div_invoiceItem_total_amount" class="col-lg-12">
    <p>
        {!! Form::label('total_amount', 'Total Amount:', ['class'=>'control-label']) !!} 
        <span id="spn_invoiceItem_total_amount">
        @if (isset($invoiceItem->total_amount) && empty($invoiceItem->total_amount)==false)
            {!! $invoiceItem->total_amount !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

<!-- Item Type Field -->
<div id="div_invoiceItem_item_type" class="col-lg-12">
    <p>
        {!! Form::label('item_type', 'Item Type:', ['class'=>'control-label']) !!} 
        <span id="spn_invoiceItem_item_type">
        @if (isset($invoiceItem->item_type) && empty($invoiceItem->item_type)==false)
            {!! $invoiceItem->item_type !!}
        @else
            N/A
        @endif
        </span>
    </p>
</div>

