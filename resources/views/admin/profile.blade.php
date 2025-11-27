@extends('layouts.admin.app')

@section('title')
    {{ translate('Profile') }}
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css')}}"/>
@stop
@section('content')

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="fw-bold py-3 mb-4">{{ translate('Profile') }}</h4>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="bs-stepper vertical wizard-vertical-icons-example mt-2">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#account-details-vertical">
                            <button type="button" class="step-trigger">
                                      <span class="bs-stepper-circle">
                                    <i class="ti ti-user"></i>
                                      </span>
                                <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">{{ translate('Personal Information') }}</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#personal-info-vertical">
                            <button type="button" class="step-trigger">
                                  <span class="bs-stepper-circle">
                                    <i class="ti ti-brand-samsungpass"></i>
                                  </span>
                                <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">{{ translate('Change Password') }}</span>
                                    </span>
                            </button>
                        </div>

                    </div>
                    <div class="bs-stepper-content">
                        <!-- Account Details -->
                        <div id="account-details-vertical" class="content">
                            <div class="content-header mb-3">
                                <h6 class="mb-0">{{ translate('Personal Information') }}</h6>
                            </div>
                            <!-- form -->
                            <form class="validate-form mt-2"
                                  action="{{ route('admin.profile.updatePersonal') }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf


                                <div class="row">

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="account-name">{{ translate('Full name') }}</label>
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   id="account-name" name="name"
                                                   placeholder="{{ translate('Full name') }}"
                                                   value="{{ old('name', auth('admin')->user()->name) }}"/>
                                        </div>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="account-e-mail">{{ translate('E-mail') }}</label>
                                            <label
                                                class="form-control">{{ auth('admin')->user()->email }}</label>
                                        </div>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="account-company">{{ translate('Phone number') }}</label>
                                            <input type="text"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   id="account-company" name="phone"
                                                   placeholder="{{ translate('Phone number') }}"
                                                   value="{{ old('phone', auth('admin')->user()->phone) }}"/>
                                        </div>
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <button data-bs-toggle="modal" type="button"
                                                data-bs-target="#checkPasswordModal"
                                                class="btn btn-primary mt-2 mr-1">{{ translate('Save') }}</button>
                                        <button type="reset"
                                                class="btn btn-outline-secondary mt-2">{{ translate('Reset') }}</button>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="checkPasswordModal" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="exampleModalLongTitle">{{ translate('Please enter your password') }}</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class=" col-lg-12 col-sm-6">
                                                        <label for="Name"
                                                               class="mr-sm-2">{{ translate('Password') }}</label>
                                                        <input type="password" class="form-control"
                                                               id="basic-default-password"
                                                               placeholder="{{ translate('Password') }}"
                                                               name="password"
                                                               aria-describedby="basic-default-password"/>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">{{ translate('Cancel') }}
                                                </button>
                                                <button type="submit"
                                                        class="btn btn-primary">{{ translate('Save') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <!--/ form -->
                        </div>
                        <!-- Personal Info -->
                        <div id="personal-info-vertical" class="content">
                            <div class="content-header mb-3">
                                <h6 class="mb-0">{{ translate('Change Password') }}</h6>
                            </div>
                            <form class="validate-form" action="{{ route('admin.profile.updatePassword') }}"
                                  method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="account-old-password">{{ translate('Old Password') }}</label>
                                            <input type="password"
                                                   class="form-control @error('current_password') is-invalid @enderror"
                                                   id="account-old-password" name="current_password"
                                                   placeholder="{{ translate('Old Password') }}"/>


                                            @error('current_password')
                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="account-new-password">{{ translate('New Password') }}</label>
                                            <input type="password" id="account-new-password"
                                                   name="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   placeholder="{{ translate('New Password') }}"/>

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="account-retype-new-password">{{ translate('Confirm Password') }}</label>
                                            <input type="password"
                                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                                   id="account-retype-new-password"
                                                   name="password_confirmation"
                                                   placeholder="{{ translate('Confirm Password') }}"/>

                                            @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit"
                                                class="btn btn-primary mr-1 mt-1">{{ translate('Save') }}</button>
                                        <button type="reset"
                                                class="btn btn-outline-secondary mt-1">{{ translate('Reset') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Social Links -->

                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- / Content -->

@endsection

@section('js')

    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js')}}"></script>
    <script src="{{ asset('assets/js/form-wizard-icons.js')}}"></script>

    <script>

        function copy_text($id) {
            // Get the input element value
            var inputValue = $('#' + $id).val();

            // Create a temporary input element and append it to the body
            var tempInput = $('<input>');
            $('body').append(tempInput);

            // Set the value of the temporary input element to the input value
            tempInput.val(inputValue);

            // Select the content inside the temporary input element
            tempInput.select();

            // Execute the copy command
            document.execCommand('copy');

            // Remove the temporary input element
            tempInput.remove();

            // Log a message to indicate that the content has been copied
            console.log('Content copied to clipboard!');

            toastr.success('{{ translate('Copy..!') }}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@stop
