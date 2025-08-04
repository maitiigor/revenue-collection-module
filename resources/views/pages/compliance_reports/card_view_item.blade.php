
<div class="col-12 col-md-12 col-sm-12">
    <div class="card">
        @php
            $detail_page_url = route('lcc-platform-mgt.complianceReports.show', $data_item->id);
        @endphp
        <div class="row g-0">
            <div class="col-xs-12 col-md-1 align-middle text-center p-2 mt-2">

                <div class="d-flex align-items-center">
                    @if ( $data_item->logo_image != null )
                        <a href='{{$detail_page_url}}'>
                            <img width="42" height="42" class="ms-2 img-fluid text-center rounded-circle p-1 border" src="{{ route('lcc-platform-mgt.get-dept-picture', $data_item->id) }}" />
                        </a>
                    @else
                        <div class="ms-auto fm-icon-box radius-10 bg-primary text-white text-center"> 
                            <a href='{{$detail_page_url}}'>
                                <i class="bx bx-hive"></i>
                            </a>
                        </div>
                    @endif
                </div>
                
                <div class="d-flex align-items-center">
                    {{-- <div><h4 class="card-title"><a href='{{$detail_page_url}}'>{{$data_item->id}}</a></h4></div> --}}
                    <div class="ms-auto"> 
                        <a data-toggle="tooltip" 
                            title="Edit" 
                            data-val='{{$data_item->id}}' 
                            class="btn-edit-mdl-complianceReport-modal" href="#">
                            <i class="bx bxs-edit"></i>
                        </a>
                        @if (Auth()->user()->hasAnyRole(['','admin']))
                        <a data-toggle="tooltip" 
                            title="Delete" 
                            data-val='{{$data_item->id}}' 
                            class="btn-delete-mdl-complianceReport-modal me-2" href="#">
                            <i class="bx bx-x"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-11">
                <div class="card-body">
                    <a href='{{$detail_page_url}}'>
                        <h3 class="h6 card-title mb-0">
                            {{ $data_item->title }} @if(empty($data_item->status)==false)- {!! strtoupper($data_item->status) !!}@endif
                        </h3>
                    </a>
                    @if (!empty($data_item->description))
                        <p class="card-text mb-0 small">
                            {{ $data_item->description }}
                        </p>
                    @endif
                    
                    <p class="card-text text-muted small">
                        Created: {{ \Carbon\Carbon::parse($data_item->created_at)->format('l jS F Y') }} - {!! \Carbon\Carbon::parse($data_item->created_at)->diffForHumans() !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>