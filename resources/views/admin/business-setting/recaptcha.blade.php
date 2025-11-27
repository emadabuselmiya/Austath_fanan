@extends('layouts.admin.app')

@section('title', translate('ReCaptcha Settings'))

@section('css')

@stop

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4"> {{ translate('ReCaptcha Settings') }}</h4>

        <!-- User Pills -->
        <ul class="nav nav-pills flex-column flex-md-row mb-4">
            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.business-setting.views', ['tab'=> 'info'])}}">
                        <span class="iconify me-1" data-icon="material-symbols:info-outline-rounded"
                              data-width="25"
                              data-height="25"></span>
                    {{ translate('General Settings') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('admin.business-setting.views', ['tab'=> 'mail'])}}">
                        <span class="iconify me-1" data-icon="material-symbols:mail-outline"
                              data-width="25" data-height="25"></span>
                    {{ translate('E-mail Settings') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{route('admin.business-setting.views', ['tab'=> 'recaptcha'])}}">
                        <span class="iconify me-1" data-icon="logos:recaptcha"
                              data-width="25" data-height="25"></span>
                    {{ translate('ReCaptcha Settings') }}
                </a>
            </li>

        </ul>
        <!--/ User Pills -->

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    @php($config=get_business_settings('recaptcha'))
                    <form action="{{ route('admin.business-setting.recaptcha-update') }}"
                          method="post">
                        @csrf
                        <div class="card-body">
                            <h5 class="d-flex flex-wrap justify-content-between align-items-center text-uppercase">
                                <span>{{translate('ReCaptcha Settings')}}</span>
                                <div class="pl-2">
                                    <img src="{{ asset('assets/img/recaptcha.png') }}" alt="public" height="50">
                                </div>
                            </h5>

                            <div class="form-group">
                                <small class="text-light fw-semibold d-block">{{translate('Status')}}</small>
                                <div class="form-group form-inline d-flex justify-content-between">
                                    <div class="form-check form-check-success">
                                        <input class="form-check-input" type="radio" name="status" value="1"
                                               id="customRadioSuccess" {{ $config ? ($config['status'] ? 'checked' : '')  : '' }}>
                                        <label class="form-check-label"
                                               for="customRadioSuccess"> {{translate('Active')}} </label>
                                    </div>
                                    <div class="form-check form-check-danger">
                                        <input class="form-check-input" type="radio" name="status" value="0"
                                               id="customRadioDanger" {{ $config ? ($config['status'] ? '' : 'checked')  : '' }}>
                                        <label class="form-check-label"
                                               for="customRadioDanger"> {{translate('Inactive')}} </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Site Key</label>
                                <input type="text" class="form-control" name="site_key"
                                       value="{{ $config ? $config['site_key'] : '' }}"
                                       placeholder="6LdRxZMeAAAAAE9PRJOgJqCGDy9O2o-abXmZvtpw">
                            </div>


                            <div class="form-group">
                                <label class="form-label">Secret Key</label><br>
                                <input type="text" class="form-control" name="secret_key"
                                       value="{{ $config ? $config['secret_key'] : '' }}"
                                       placeholder="6LdRxZMeAAAAAE9PRJOgJqCGDy9O2o-abXmZvtpw">
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                {{ translate('Save') }}
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <!-- / Content -->

@endsection

@section('js')


@stop
