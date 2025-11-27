@extends('layouts.admin.app')

@section('title')
    {{ translate('Dashboards') }}
@stop

@section('css')

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}"/>
    <link rel="stylesheet"
          href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}"/>
    <!-- END: Vendor CSS-->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}"/>

@stop

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row match-height">
            <div class="col-xl-4 mb-4 col-lg-5 col-12">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-7">
                            <div class="card-body text-nowrap">
                                <h5 class="card-title mb-0">{{translate('Welcome')}} {{auth('admin')->user()->name}}!
                                    🎉</h5>
                                <p class="mx-2">{{ auth('admin')->user()->job_title }}</p>
                                <a href="{{ route('admin.profile.index') }}"
                                   class="btn btn-primary waves-effect waves-light">{{translate('Profile')}}</a>
                            </div>
                        </div>
                        <div class="col-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="/assets/img/illustrations/card-advance-sale.png" height="140"
                                     alt="view sales">
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>


@endsection

@section('js')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-crm.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>


@stop
