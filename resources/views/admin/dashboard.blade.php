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

            <div class="col-lg-2 col-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="badge rounded p-2 bg-label-info mb-2">
                                       <span class="iconify me-1" data-icon="noto-v1:man-student"
                                             data-width="25"
                                             data-height="25"></span>
                        </div>
                        <h5 class="card-title mb-1">{{ \App\Models\User::count() }}</h5>
                        <p class="mb-0">عدد الطلاب</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-6">

                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="badge rounded p-2 bg-label-info mb-2">
                               <span class="iconify me-1" data-icon="streamline-freehand:coupon-percent"
                                     data-width="25"
                                     data-height="25"></span>
                        </div>
                        <h5 class="card-title mb-1">{{ \App\Models\ActivationCode::count() }}</h5>
                        <p class="mb-0">عدد اكواد التفعيل</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-6">

                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="badge rounded p-2 bg-label-success mb-2">
                                       <span class="iconify me-1" data-icon="streamline-freehand:coupon-percent"
                                             data-width="25"
                                             data-height="25"></span>
                        </div>
                        <h5 class="card-title mb-1">{{ \App\Models\ActivationCode::where('is_used', 1)->count() }}</h5>
                        <p class="mb-0">الاكواد المستخدمة</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-6">

                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="badge rounded p-2 bg-label-danger mb-2">
                                       <span class="iconify me-1" data-icon="streamline-freehand:coupon-percent"
                                             data-width="25"
                                             data-height="25"></span>
                        </div>
                        <h5 class="card-title mb-1">{{ \App\Models\ActivationCode::where('is_used', 0)->count() }}</h5>
                        <p class="mb-0">الاكواد الغير مستخدمة</p>
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
