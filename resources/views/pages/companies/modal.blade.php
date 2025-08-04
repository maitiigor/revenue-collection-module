

<div class="modal fade" id="mdl-company-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-company-modal-title" class="modal-title">Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-company-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-company-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    
                        <div class="col-lg-12 pe-2">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-companies">You are currently offline</span></div>

                            <div id="spinner-companies" class="spinner-border text-primary" role="status"> 
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            <input type="hidden" id="txt-company-primary-id" value="0" />
                            <div id="div-show-txt-company-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('scola-apply-module::pages.companies.show_fields')
                                    </div>
                                </div>
                            </div>
                            <div id="div-edit-txt-company-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('scola-apply-module::pages.companies.fields')
                                    </div>
                                </div>
                            </div>

                        </div>
                    
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-company-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-company-modal" value="add">Save</button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-companies').hide();

    //Show Modal for New Entry
    $(document).on('click', ".btn-new-mdl-company-modal", function(e) {
        $('#div-company-modal-error').hide();
        $('#mdl-company-modal').modal('show');
        $('#frm-company-modal').trigger("reset");
        $('#txt-company-primary-id').val(0);

        $('#div-show-txt-company-primary-id').hide();
        $('#div-edit-txt-company-primary-id').show();

        $("#spinner-companies").hide();
        $("#div-save-mdl-company-modal").attr('disabled', false);
    });

    //Show Modal for View
    $(document).on('click', ".btn-show-mdl-company-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-companies').fadeIn(300);
            return;
        }else{
            $('.offline-companies').fadeOut(300);
        }

        $('#div-company-modal-error').hide();
        $('#mdl-company-modal').modal('show');
        $('#frm-company-modal').trigger("reset");

        $("#spinner-companies").show();
        $("#div-save-mdl-company-modal").attr('disabled', true);

        $('#div-show-txt-company-primary-id').show();
        $('#div-edit-txt-company-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('lcc-platform-mgt-api.companies.show','') }}/"+itemId).done(function( response ) {
			
			$('#txt-company-primary-id').val(response.data.id);
            		$('#spn_company_name').html(response.data.name);
		$('#spn_company_short_name').html(response.data.short_name);
		$('#spn_company_rcc_number').html(response.data.rcc_number);
		$('#spn_company_irr_number').html(response.data.irr_number);
		$('#spn_company_firs_vat_number').html(response.data.firs_vat_number);
		$('#spn_company_status').html(response.data.status);


            $("#spinner-companies").hide();
            $("#div-save-mdl-company-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-mdl-company-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-company-modal-error').hide();
        $('#mdl-company-modal').modal('show');
        $('#frm-company-modal').trigger("reset");

        $("#spinner-companies").show();
        $("#div-save-mdl-company-modal").attr('disabled', true);

        $('#div-show-txt-company-primary-id').hide();
        $('#div-edit-txt-company-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('lcc-platform-mgt-api.companies.show','') }}/"+itemId).done(function( response ) {     

			$('#txt-company-primary-id').val(response.data.id);
            		$('#name').val(response.data.name);
		$('#short_name').val(response.data.short_name);
		$('#rcc_number').val(response.data.rcc_number);
		$('#irr_number').val(response.data.irr_number);
		$('#firs_vat_number').val(response.data.firs_vat_number);
		$('#status').val(response.data.status);


            $("#spinner-companies").hide();
            $("#div-save-mdl-company-modal").attr('disabled', false);
        });
    });

    //Delete action
    $(document).on('click', ".btn-delete-mdl-company-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-companies').fadeIn(300);
            return;
        }else{
            $('.offline-companies').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this Company?",
                text: "You will not be able to recover this Company if deleted.",
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
                        title: '<div id="spinner-companies" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
                        text: 'Deleting Company.',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    }) 
                                        
                    let endPointUrl = "{{ route('lcc-platform-mgt-api.companies.destroy','') }}/"+itemId;

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
                                        text: "Company deleted successfully",
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
    $('#btn-save-mdl-company-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});


        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-companies').fadeIn(300);
            return;
        }else{
            $('.offline-companies').fadeOut(300);
        }

        $("#spinner-companies").show();
        $("#div-save-mdl-company-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('lcc-platform-mgt-api.companies.store') }}";
        let primaryId = $('#txt-company-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('lcc-platform-mgt-api.companies.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif
        // formData.append('', $('#').val());
        		if ($('#name').length){	formData.append('name',$('#name').val());	}
		if ($('#short_name').length){	formData.append('short_name',$('#short_name').val());	}
		if ($('#rcc_number').length){	formData.append('rcc_number',$('#rcc_number').val());	}
		if ($('#irr_number').length){	formData.append('irr_number',$('#irr_number').val());	}
		if ($('#firs_vat_number').length){	formData.append('firs_vat_number',$('#firs_vat_number').val());	}
		if ($('#status').length){	formData.append('status',$('#status').val());	}


        {{-- 
        swal({
            title: '<div id="spinner-companies" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
            text: 'Saving Company',
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
					$('#div-company-modal-error').html('');
					$('#div-company-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-company-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-company-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-company-modal-error').hide();

                        swal({
                                title: "Saved",
                                text: "Company saved successfully",
                                type: "success"
                            },function(){
                                location.reload(true);
                        });

                    },20);
                }

                $("#spinner-companies").hide();
                $("#div-save-mdl-company-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-companies").hide();
                $("#div-save-mdl-company-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush
