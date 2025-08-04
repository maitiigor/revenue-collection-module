

<div class="modal fade" id="mdl-invoiceItem-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-invoiceItem-modal-title" class="modal-title">Invoice Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-invoiceItem-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-invoiceItem-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    
                        <div class="col-lg-12 pe-2">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-invoice_items">You are currently offline</span></div>

                            <div id="spinner-invoice_items" class="spinner-border text-primary" role="status"> 
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            <input type="hidden" id="txt-invoiceItem-primary-id" value="0" />
                            <div id="div-show-txt-invoiceItem-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('scola-apply-module::pages.invoice_items.show_fields')
                                    </div>
                                </div>
                            </div>
                            <div id="div-edit-txt-invoiceItem-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('scola-apply-module::pages.invoice_items.fields')
                                    </div>
                                </div>
                            </div>

                        </div>
                    
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-invoiceItem-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-invoiceItem-modal" value="add">Save</button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-invoice_items').hide();

    //Show Modal for New Entry
    $(document).on('click', ".btn-new-mdl-invoiceItem-modal", function(e) {
        $('#div-invoiceItem-modal-error').hide();
        $('#mdl-invoiceItem-modal').modal('show');
        $('#frm-invoiceItem-modal').trigger("reset");
        $('#txt-invoiceItem-primary-id').val(0);

        $('#div-show-txt-invoiceItem-primary-id').hide();
        $('#div-edit-txt-invoiceItem-primary-id').show();

        $("#spinner-invoice_items").hide();
        $("#div-save-mdl-invoiceItem-modal").attr('disabled', false);
    });

    //Show Modal for View
    $(document).on('click', ".btn-show-mdl-invoiceItem-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-invoice_items').fadeIn(300);
            return;
        }else{
            $('.offline-invoice_items').fadeOut(300);
        }

        $('#div-invoiceItem-modal-error').hide();
        $('#mdl-invoiceItem-modal').modal('show');
        $('#frm-invoiceItem-modal').trigger("reset");

        $("#spinner-invoice_items").show();
        $("#div-save-mdl-invoiceItem-modal").attr('disabled', true);

        $('#div-show-txt-invoiceItem-primary-id').show();
        $('#div-edit-txt-invoiceItem-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('lcc-platform-mgt-api.invoice_items.show','') }}/"+itemId).done(function( response ) {
			
			$('#txt-invoiceItem-primary-id').val(response.data.id);
            		$('#spn_invoiceItem_title').html(response.data.title);
		$('#spn_invoiceItem_description').html(response.data.description);
		$('#spn_invoiceItem_quantity').html(response.data.quantity);
		$('#spn_invoiceItem_unit_price').html(response.data.unit_price);
		$('#spn_invoiceItem_total_amount').html(response.data.total_amount);
		$('#spn_invoiceItem_item_type').html(response.data.item_type);


            $("#spinner-invoice_items").hide();
            $("#div-save-mdl-invoiceItem-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-mdl-invoiceItem-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-invoiceItem-modal-error').hide();
        $('#mdl-invoiceItem-modal').modal('show');
        $('#frm-invoiceItem-modal').trigger("reset");

        $("#spinner-invoice_items").show();
        $("#div-save-mdl-invoiceItem-modal").attr('disabled', true);

        $('#div-show-txt-invoiceItem-primary-id').hide();
        $('#div-edit-txt-invoiceItem-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('lcc-platform-mgt-api.invoice_items.show','') }}/"+itemId).done(function( response ) {     

			$('#txt-invoiceItem-primary-id').val(response.data.id);
            		$('#title').val(response.data.title);
		$('#description').val(response.data.description);
		$('#quantity').val(response.data.quantity);
		$('#unit_price').val(response.data.unit_price);
		$('#total_amount').val(response.data.total_amount);
		$('#item_type').val(response.data.item_type);


            $("#spinner-invoice_items").hide();
            $("#div-save-mdl-invoiceItem-modal").attr('disabled', false);
        });
    });

    //Delete action
    $(document).on('click', ".btn-delete-mdl-invoiceItem-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-invoice_items').fadeIn(300);
            return;
        }else{
            $('.offline-invoice_items').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this InvoiceItem?",
                text: "You will not be able to recover this InvoiceItem if deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {

                    swal({
                        title: '<div id="spinner-invoice_items" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
                        text: 'Deleting InvoiceItem.',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    }) 
                                        
                    let endPointUrl = "{{ route('lcc-platform-mgt-api.invoice_items.destroy','') }}/"+itemId;

                    let formData = new FormData();
                    formData.append('_token', $('input[name="_token"]').val());
                    formData.append('_method', 'DELETE');
                    
                    $.ajax({
                        url:endPointUrl,
                        type: "POST",
                        data: formData,
                        cache: false,
                        processData:false,
                        contentType: false,
                        dataType: 'json',
                        success: function(result){
                            if(result.errors){
                                console.log(result.errors)
                                swal("Error", "Oops an error occurred. Please try again.", "error");
                            }else{
                                swal({
                                        title: "Deleted",
                                        text: "InvoiceItem deleted successfully",
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: false
                                    },function(){
                                        location.reload(true);
                                });
                            }
                        },
                    });
                }
        });
    });

    //Save details
    $('#btn-save-mdl-invoiceItem-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});


        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-invoice_items').fadeIn(300);
            return;
        }else{
            $('.offline-invoice_items').fadeOut(300);
        }

        $("#spinner-invoice_items").show();
        $("#div-save-mdl-invoiceItem-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('lcc-platform-mgt-api.invoice_items.store') }}";
        let primaryId = $('#txt-invoiceItem-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('lcc-platform-mgt-api.invoice_items.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif
        // formData.append('', $('#').val());
        		if ($('#title').length){	formData.append('title',$('#title').val());	}
		if ($('#description').length){	formData.append('description',$('#description').val());	}
		if ($('#quantity').length){	formData.append('quantity',$('#quantity').val());	}
		if ($('#unit_price').length){	formData.append('unit_price',$('#unit_price').val());	}
		if ($('#total_amount').length){	formData.append('total_amount',$('#total_amount').val());	}
		if ($('#item_type').length){	formData.append('item_type',$('#item_type').val());	}


        {{-- 
        swal({
            title: '<div id="spinner-invoice_items" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
            text: 'Saving InvoiceItem',
            showConfirmButton: false,
            allowOutsideClick: false,
            html: true
        })
        --}}
        
        $.ajax({
            url:endPointUrl,
            type: "POST",
            data: formData,
            cache: false,
            processData:false,
            contentType: false,
            dataType: 'json',
            success: function(result){
                if(result.errors){
					$('#div-invoiceItem-modal-error').html('');
					$('#div-invoiceItem-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-invoiceItem-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-invoiceItem-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-invoiceItem-modal-error').hide();

                        swal({
                                title: "Saved",
                                text: "InvoiceItem saved successfully",
                                type: "success"
                            },function(){
                                location.reload(true);
                        });

                    },20);
                }

                $("#spinner-invoice_items").hide();
                $("#div-save-mdl-invoiceItem-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-invoice_items").hide();
                $("#div-save-mdl-invoiceItem-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush
