@extends('layouts.master-without_nav')

@section('title')
    Home
@endsection
@php
$landing_page_background_image = null;
@endphp
@section('content')
   
    <div class="contaner py-2" style="background-color: #f">

        <div class="min-vh-100"
        style="background: url({{ $landing_page_background_image ? route('schoolmgt-attachment.show', $landing_page_background_image) : asset('assets/images/scola-bg.png') }}) no-repeat fixed bottom; background-size: cover">
        <div class="bg-overlay bg-light"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <img src="{{asset('images/uniabuja.png')}}" style="height: 200px" alt="institution logo">
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="row g-0
                        ">
                            <div class="col-md-4 ps-4 pt-4 text-center">
                                <img class="card-img rounded-circle" style="height: 120px;"  src="http://127.0.0.1:8080/assets/images/small/img-2.jpg" alt="Card image">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Initiate a Transcript Request
                                    </h5>
                                    <p class="card-text">You are advised to carefully follow the instructions while initiating your transcript request.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 px-4">
                            <a href="{{route('schoolmgtPayroll.start')}}" class="btn float-end mb-3 btn-primary"> <i class="fa fa-arrow-right me-1"></i> Continue</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-4 ps-4 pt-4 text-center">
                                <img class="card-img rounded-circle" style="height: 120px;"  src="http://127.0.0.1:8080/assets/images/small/img-2.jpg" alt="Card image">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Track Transcript
                                    </h5>
                                    <p class="card-text">Please ensure you provide the right transaction reference number.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 px-4">
                            <a href="javascript: void(0);" class="btn float-end mb-3 btn-primary"> <i class="fa fa-arrow-right me-1"></i> Continue</a>
                        </div>
                    </div>
                </div>          
            </div>
         
    </div>
        
    
    </div>
    
@endsection