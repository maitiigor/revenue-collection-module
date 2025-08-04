

<div class="modal fade" id="mdl-companyUser-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-companyUser-modal-title" class="modal-title">Company User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-companyUser-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-companyUser-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    
                        <div class="col-lg-12 pe-2">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-company_users">You are currently offline</span></div>

                            <div id="spinner-company_users" class="spinner-border text-primary" role="status"> 
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            <input type="hidden" id="txt-companyUser-primary-id" value="0" />
                            <div id="div-show-txt-companyUser-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('scola-apply-module::pages.company_users.show_fields')
                                    </div>
                                </div>
                            </div>
                            <div id="div-edit-txt-companyUser-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('scola-apply-module::pages.company_users.fields')
                                    </div>
                                </div>
                            </div>

                        </div>
                    
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-companyUser-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-companyUser-modal" value="add">Save</button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-company_users').hide();

    //Show Modal for New Entry
    $(document).on('click', ".btn-new-mdl-companyUser-modal", function(e) {
        $('#div-companyUser-modal-error').hide();
        $('#mdl-companyUser-modal').modal('show');
        $('#frm-companyUser-modal').trigger("reset");
        $('#txt-companyUser-primary-id').val(0);

        $('#div-show-txt-companyUser-primary-id').hide();
        $('#div-edit-txt-companyUser-primary-id').show();

        $("#spinner-company_users").hide();
        $("#div-save-mdl-companyUser-modal").attr('disabled', false);
    });

    //Show Modal for View
    $(document).on('click', ".btn-show-mdl-companyUser-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-company_users').fadeIn(300);
            return;
        }else{
            $('.offline-company_users').fadeOut(300);
        }

        $('#div-companyUser-modal-error').hide();
        $('#mdl-companyUser-modal').modal('show');
        $('#frm-companyUser-modal').trigger("reset");

        $("#spinner-company_users").show();
        $("#div-save-mdl-companyUser-modal").attr('disabled', true);

        $('#div-show-txt-companyUser-primary-id').show();
        $('#div-edit-txt-companyUser-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('lcc-platform-mgt-api.company_users.show','') }}/"+itemId).done(function( response ) {
			
			$('#txt-companyUser-primary-id').val(response.data.id);
            

            $("#spinner-company_users").hide();
            $("#div-save-mdl-companyUser-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-mdl-companyUser-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-companyUser-modal-error').hide();
        $('#mdl-companyUser-modal').modal('show');
        $('#frm-companyUser-modal').trigger("reset");

        $("#spinner-company_users").show();
        $("#div-save-mdl-companyUser-modal").attr('disabled', true);

        $('#div-show-txt-companyUser-primary-id').hide();
        $('#div-edit-txt-companyUser-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('lcc-platform-mgt-api.company_users.show','') }}/"+itemId).done(function( response ) {     

			$('#txt-companyUser-primary-id').val(response.data.id);
            

            $("#spinner-company_users").hide();
            $("#div-save-mdl-companyUser-modal").attr('disabled', false);
        });
    });

    //Delete action
    $(document).on('click', ".btn-delete-mdl-companyUser-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-company_users').fadeIn(300);
            return;
        }else{
            $('.offline-company_users').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this CompanyUser?",
                text: "You will not be able to recover this CompanyUser if deleted.",
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
                        title: '<div id="spinner-company_users" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
                        text: 'Deleting CompanyUser.',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    }) 
                                        
                    let endPointUrl = "{{ route('lcc-platform-mgt-api.company_users.destroy','') }}/"+itemId;

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
                                        text: "CompanyUser deleted successfully",
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
    $('#btn-save-mdl-companyUser-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});


        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-company_users').fadeIn(300);
            return;
        }else{
            $('.offline-company_users').fadeOut(300);
        }

        $("#spinner-company_users").show();
        $("#div-save-mdl-companyUser-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('lcc-platform-mgt-api.company_users.store') }}";
        let primaryId = $('#txt-companyUser-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('lcc-platform-mgt-api.company_users.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif
        // formData.append('', $('#').val());
        

        {{-- 
        swal({
            title: '<div id="spinner-company_users" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
            text: 'Saving CompanyUser',
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
					$('#div-companyUser-modal-error').html('');
					$('#div-companyUser-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-companyUser-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-companyUser-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-companyUser-modal-error').hide();

                        swal({
                                title: "Saved",
                                text: "CompanyUser saved successfully",
                                type: "success"
                            },function(){
                                location.reload(true);
                        });

                    },20);
                }

                $("#spinner-company_users").hide();
                $("#div-save-mdl-companyUser-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-company_users").hide();
                $("#div-save-mdl-companyUser-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush
