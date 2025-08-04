

<div class="modal fade" id="mdl-complianceReport-modal" tabindex="-1" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="lbl-complianceReport-modal-title" class="modal-title">Compliance Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="div-complianceReport-modal-error" class="alert alert-danger" role="alert"></div>
                <form class="form-horizontal" id="frm-complianceReport-modal" role="form" method="POST" enctype="multipart/form-data" action="">
                    
                        <div class="col-lg-12 pe-2">
                            
                            @csrf
                            
                            <div class="offline-flag"><span class="offline-compliance_reports">You are currently offline</span></div>

                            <div id="spinner-compliance_reports" class="spinner-border text-primary" role="status"> 
                                <span class="visually-hidden">Loading...</span>
                            </div>

                            <input type="hidden" id="txt-complianceReport-primary-id" value="0" />
                            <div id="div-show-txt-complianceReport-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('scola-apply-module::pages.compliance_reports.show_fields')
                                    </div>
                                </div>
                            </div>
                            <div id="div-edit-txt-complianceReport-primary-id">
                                <div class="row">
                                    <div class="col-lg-12">
                                    @include('scola-apply-module::pages.compliance_reports.fields')
                                    </div>
                                </div>
                            </div>

                        </div>
                    
                </form>
            </div>

        
            <div class="modal-footer" id="div-save-mdl-complianceReport-modal">
                <button type="button" class="btn btn-primary" id="btn-save-mdl-complianceReport-modal" value="add">Save</button>
            </div>

        </div>
    </div>
</div>

@push('page_scripts')
<script type="text/javascript">
$(document).ready(function() {

    $('.offline-compliance_reports').hide();

    //Show Modal for New Entry
    $(document).on('click', ".btn-new-mdl-complianceReport-modal", function(e) {
        $('#div-complianceReport-modal-error').hide();
        $('#mdl-complianceReport-modal').modal('show');
        $('#frm-complianceReport-modal').trigger("reset");
        $('#txt-complianceReport-primary-id').val(0);

        $('#div-show-txt-complianceReport-primary-id').hide();
        $('#div-edit-txt-complianceReport-primary-id').show();

        $("#spinner-compliance_reports").hide();
        $("#div-save-mdl-complianceReport-modal").attr('disabled', false);
    });

    //Show Modal for View
    $(document).on('click', ".btn-show-mdl-complianceReport-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-compliance_reports').fadeIn(300);
            return;
        }else{
            $('.offline-compliance_reports').fadeOut(300);
        }

        $('#div-complianceReport-modal-error').hide();
        $('#mdl-complianceReport-modal').modal('show');
        $('#frm-complianceReport-modal').trigger("reset");

        $("#spinner-compliance_reports").show();
        $("#div-save-mdl-complianceReport-modal").attr('disabled', true);

        $('#div-show-txt-complianceReport-primary-id').show();
        $('#div-edit-txt-complianceReport-primary-id').hide();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('lcc-platform-mgt-api.compliance_reports.show','') }}/"+itemId).done(function( response ) {
			
			$('#txt-complianceReport-primary-id').val(response.data.id);
            		$('#spn_complianceReport_status').html(response.data.status);
		$('#spn_complianceReport_remarks').html(response.data.remarks);
		$('#spn_complianceReport_report_date').html(response.data.report_date);


            $("#spinner-compliance_reports").hide();
            $("#div-save-mdl-complianceReport-modal").attr('disabled', false);
        });
    });

    //Show Modal for Edit
    $(document).on('click', ".btn-edit-mdl-complianceReport-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        $('#div-complianceReport-modal-error').hide();
        $('#mdl-complianceReport-modal').modal('show');
        $('#frm-complianceReport-modal').trigger("reset");

        $("#spinner-compliance_reports").show();
        $("#div-save-mdl-complianceReport-modal").attr('disabled', true);

        $('#div-show-txt-complianceReport-primary-id').hide();
        $('#div-edit-txt-complianceReport-primary-id').show();
        let itemId = $(this).attr('data-val');

        $.get( "{{ route('lcc-platform-mgt-api.compliance_reports.show','') }}/"+itemId).done(function( response ) {     

			$('#txt-complianceReport-primary-id').val(response.data.id);
            		$('#status').val(response.data.status);
		$('#remarks').val(response.data.remarks);
		$('#report_date').val(response.data.report_date);


            $("#spinner-compliance_reports").hide();
            $("#div-save-mdl-complianceReport-modal").attr('disabled', false);
        });
    });

    //Delete action
    $(document).on('click', ".btn-delete-mdl-complianceReport-modal", function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});

        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-compliance_reports').fadeIn(300);
            return;
        }else{
            $('.offline-compliance_reports').fadeOut(300);
        }

        let itemId = $(this).attr('data-val');
        swal({
                title: "Are you sure you want to delete this ComplianceReport?",
                text: "You will not be able to recover this ComplianceReport if deleted.",
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
                        title: '<div id="spinner-compliance_reports" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
                        text: 'Deleting ComplianceReport.',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        html: true
                    }) 
                                        
                    let endPointUrl = "{{ route('lcc-platform-mgt-api.compliance_reports.destroy','') }}/"+itemId;

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
                                        text: "ComplianceReport deleted successfully",
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
    $('#btn-save-mdl-complianceReport-modal').click(function(e) {
        e.preventDefault();
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}});


        //check for internet status 
        if (!window.navigator.onLine) {
            $('.offline-compliance_reports').fadeIn(300);
            return;
        }else{
            $('.offline-compliance_reports').fadeOut(300);
        }

        $("#spinner-compliance_reports").show();
        $("#div-save-mdl-complianceReport-modal").attr('disabled', true);

        let actionType = "POST";
        let endPointUrl = "{{ route('lcc-platform-mgt-api.compliance_reports.store') }}";
        let primaryId = $('#txt-complianceReport-primary-id').val();
        
        let formData = new FormData();
        formData.append('_token', $('input[name="_token"]').val());

        if (primaryId != "0"){
            actionType = "PUT";
            endPointUrl = "{{ route('lcc-platform-mgt-api.compliance_reports.update','') }}/"+primaryId;
            formData.append('id', primaryId);
        }
        
        formData.append('_method', actionType);
        @if (isset($organization) && $organization!=null)
            formData.append('organization_id', '{{$organization->id}}');
        @endif
        // formData.append('', $('#').val());
        		if ($('#status').length){	formData.append('status',$('#status').val());	}
		if ($('#remarks').length){	formData.append('remarks',$('#remarks').val());	}
		if ($('#report_date').length){	formData.append('report_date',$('#report_date').val());	}


        {{-- 
        swal({
            title: '<div id="spinner-compliance_reports" class="spinner-border text-primary" role="status"> <span class="visually-hidden">  Processing...  </span> </div> <br><br> Please wait...',
            text: 'Saving ComplianceReport',
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
					$('#div-complianceReport-modal-error').html('');
					$('#div-complianceReport-modal-error').show();
                    
                    $.each(result.errors, function(key, value){
                        $('#div-complianceReport-modal-error').append('<li class="">'+value+'</li>');
                    });
                }else{
                    $('#div-complianceReport-modal-error').hide();
                    window.setTimeout( function(){

                        $('#div-complianceReport-modal-error').hide();

                        swal({
                                title: "Saved",
                                text: "ComplianceReport saved successfully",
                                type: "success"
                            },function(){
                                location.reload(true);
                        });

                    },20);
                }

                $("#spinner-compliance_reports").hide();
                $("#div-save-mdl-complianceReport-modal").attr('disabled', false);
                
            }, error: function(data){
                console.log(data);
                swal("Error", "Oops an error occurred. Please try again.", "error");

                $("#spinner-compliance_reports").hide();
                $("#div-save-mdl-complianceReport-modal").attr('disabled', false);

            }
        });
    });

});
</script>
@endpush
