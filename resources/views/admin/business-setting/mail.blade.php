@extends('layouts.admin.app')

@section('title', translate('E-mail Settings'))

@section('css')

@stop

@section('content')
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">{{ translate('E-mail Settings') }}</h4>

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
                    <a class="nav-link active" href="{{route('admin.business-setting.views', ['tab'=> 'mail'])}}">
                        <span class="iconify me-1" data-icon="material-symbols:mail-outline"
                              data-width="25" data-height="25"></span>
                        {{ translate('E-mail Settings') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.business-setting.views', ['tab'=> 'recaptcha'])}}">
                        <span class="iconify me-1" data-icon="logos:recaptcha"
                              data-width="25" data-height="25"></span>
                        {{ translate('ReCaptcha Settings') }}
                    </a>
                </li>
            </ul>
            <!--/ User Pills -->

            <div class="card">
                @php($config=get_business_settings('mail_config'))
                <div class="card-body">

                    <form action="{{ route('admin.business-setting.mail-config') }}"
                          method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
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
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ translate('Sender Name') }}</label>
                                    <input type="text" class="form-control" name="name"
                                           value="{{ $config ? $config['name'] ?? '' : '' }}"
                                           placeholder="{{ translate('EX :') }} Alex">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ translate('Host') }}</label>
                                    <input type="text" class="form-control" name="host"
                                           value="{{ $config ? $config['host'] ?? '' : '' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ translate('Driver') }}</label>
                                    <input type="text" class="form-control" name="driver"
                                           value="{{ $config ? $config['driver'] ?? '' : '' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ translate('Port') }}</label>
                                    <input type="text" class="form-control" name="port"
                                           value="{{ $config ? $config['port'] ?? '' : '' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ translate('Username') }}</label>
                                    <input type="text" class="form-control" name="username"
                                           value="{{ $config ? $config['username'] ?? '' : '' }}"
                                           placeholder="{{ translate('Ex :') }} ex@yahoo.com">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ translate('Email ID') }}</label>
                                    <input type="text" class="form-control" name="email_id"
                                           value="{{ $config ? $config['email_id'] ?? '' : '' }}"
                                           placeholder="{{ translate('Ex :') }} ex@yahoo.com">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ translate('Encryption') }}</label>
                                    <input type="text" class="form-control" name="encryption"
                                           value="{{ $config ? $config['encryption'] ?? '' : '' }}"
                                           placeholder="{{ translate('Ex :') }} tls">
                                </div>
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
        <!-- / Content -->

@endsection

@section('js')


@stop
