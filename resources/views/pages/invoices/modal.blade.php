

<div class="modal fade" id="mdl-invoice-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-invoice-modal-title" class="modal-title">Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-invoice-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-invoice-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    
                        <div class="col-lg-12 pe-2">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-invoices">You are currently offline</span></div>

                            <div id="spinner-invoices" class="spinner-border text-primary" role="status"> 
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            <input type="hidden" id="txt-invoice-primary-id" value="0" />
                            <div id="div-show-txt-invoice-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('scola-apply-module::pages.invoices.show_fields')
                                    </div>
                                </div>
                            </div>
                            <div id="div-edit-txt-invoice-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('scola-apply-module::pages.invoices.fields')
                                    </div>
                                </div>
                            </div>

                        </div>
                    
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-invoice-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-invoice-modal" value="add">Save</button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-invoices').hide();

    //Show Modal for New Entry
    $(document).on('click', ".btn-new-mdl-invoice-modal", function(e) {
        $('#div-invoice-modal-error').hide();
        $('#mdl-invoice-modal').modal('show');
        $('#frm-invoice-modal').trigger("reset");
        $('#txt-invoice-primary-id').val(0);

        $('#div-show-txt-invoice-primary-id').hide();
        $('#div-edit-txt-invoice-primary-id').show();

        $("#spinner-invoices").hide();
        $("#div-save-mdl-invoice-modal").attr('disabled', false);
    });

    //Show Modal for View
    $(document).on('click', ".btn-show-mdl-invoice-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-invoices').fadeIn(300);
            return;
        }else{
            $('.offline-invoices').fadeOut(300);
        }

        $('#div-invoice-modal-error').hide();
        $('#mdl-invoice-modal').modal('show');
        $('#frm-invoice-modal').trigger("reset");

        $("#spinner-invoices").show();
        $("#div-save-mdl-invoice-modal").attr('disabled', true);

        $('#div-show-txt-invoice-primary-id').show();
        $('#div-edit-txt-invoice-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('lcc-platform-mgt-api.invoices.show','') }}/"+itemId).done(function( response ) {
			
			$('#txt-invoice-primary-id').val(response.data.id);
            		$('#spn_invoice_invoice_number').html(response.data.invoice_number);
		$('#spn_invoice_amount').html(response.data.amount);
		$('#spn_invoice_amount').html(response.data.amount);
		$('#spn_invoice_invoice_date').html(response.data.invoice_date);
		$('#spn_invoice_invoice_due_date').html(response.data.invoice_due_date);


            $("#spinner-invoices").hide();
            $("#div-save-mdl-invoice-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-mdl-invoice-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-invoice-modal-error').hide();
        $('#mdl-invoice-modal').modal('show');
        $('#frm-invoice-modal').trigger("reset");

        $("#spinner-invoices").show();
        $("#div-save-mdl-invoice-modal").attr('disabled', true);

        $('#div-show-txt-invoice-primary-id').hide();
        $('#div-edit-txt-invoice-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('lcc-platform-mgt-api.invoices.show','') }}/"+itemId).done(function( response ) {     

			$('#txt-invoice-primary-id').val(response.data.id);
            		$('#invoice_number').val(response.data.invoice_number);
		$('#amount').val(response.data.amount);
		$('#amount').val(response.data.amount);
		$('#invoice_date').val(response.data.invoice_date);
		$('#invoice_due_date').val(response.data.invoice_due_date);


            $("#spinner-invoices").hide();
            $("#div-save-mdl-invoice-modal").attr('disabled', false);
        });
    });

    //Delete action
    $(document).on('click', ".btn-delete-mdl-invoice-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-invoices').fadeIn(300);
            return;
        }else{
            $('.offline-invoices').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this Invoice?",
                text: "You will not be able to recover this Invoice if deleted.",
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
                        title: '<div id="spinner-invoices" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
                        text: 'Deleting Invoice.',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    }) 
                                        
                    let endPointUrl = "{{ route('lcc-platform-mgt-api.invoices.destroy','') }}/"+itemId;

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
                                        text: "Invoice deleted successfully",
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
    $('#btn-save-mdl-invoice-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});


        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-invoices').fadeIn(300);
            return;
        }else{
            $('.offline-invoices').fadeOut(300);
        }

        $("#spinner-invoices").show();
        $("#div-save-mdl-invoice-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('lcc-platform-mgt-api.invoices.store') }}";
        let primaryId = $('#txt-invoice-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('lcc-platform-mgt-api.invoices.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif
        // formData.append('', $('#').val());
        		if ($('#invoice_number').length){	formData.append('invoice_number',$('#invoice_number').val());	}
		if ($('#amount').length){	formData.append('amount',$('#amount').val());	}
		if ($('#amount').length){	formData.append('amount',$('#amount').val());	}
		if ($('#invoice_date').length){	formData.append('invoice_date',$('#invoice_date').val());	}
		if ($('#invoice_due_date').length){	formData.append('invoice_due_date',$('#invoice_due_date').val());	}


        {{-- 
        swal({
            title: '<div id="spinner-invoices" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
            text: 'Saving Invoice',
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
					$('#div-invoice-modal-error').html('');
					$('#div-invoice-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-invoice-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-invoice-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-invoice-modal-error').hide();

                        swal({
                                title: "Saved",
                                text: "Invoice saved successfully",
                                type: "success"
                            },function(){
                                location.reload(true);
                        });

                    },20);
                }

                $("#spinner-invoices").hide();
                $("#div-save-mdl-invoice-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-invoices").hide();
                $("#div-save-mdl-invoice-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush
