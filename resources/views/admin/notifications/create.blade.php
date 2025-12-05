@extends('layouts.admin.app')
@section('title')
    {{ translate('اضافة') }} {{ translate('الاشعار') }}
@stop
@section('css')

    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/bootstrap-select/bootstrap-select.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/select2/select2.css') }}"/>
@stop

@section('content')

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">{{ translate('اضافة') }} {{ translate('الاشعار') }}</h4>

        <div class="card">
            <div class="card-body">
                <x-alerts/>
                <form method="POST" id="notificationForm" action="javascript:void(0);"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="title"
                                           class="form-label">{{ translate('العنوان') }}
                                        <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="title" name="title" class="form-control"
                                           placeholder="{{ translate('العنوان') }}"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="description"
                                           class="form-label">{{ translate('المحتوى') }}
                                        <span
                                            class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control" cols="10"
                                              rows="5"
                                              placeholder="{{ translate('المحتوى') }}"
                                              required></textarea>
                                </div>

                                <div class="col-md-6" id="url">
                                    <label for="url" class="form-label">{{ translate('URL') }}</label>

                                    <input type="url" name="url" id="url" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4" id="dropZone">
                            <div class="form-group mt-2">
                                <label for="username">{{ translate('الصورة') }}</label>
                                <input type="file" name="image" id="image"
                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                       style="display:none;"/>
                                <button id="output_image" type="button"
                                        onclick="document.getElementById('image').click();"
                                        style=" width: 200px;
                                            height: 150px;
                                            border-radius: 10%;
                                            background-color: #0a1128;
                                            background-image: url({{ asset('storage/default.png') }});
                                            background-repeat: no-repeat;
                                            background-size: cover;
                                            background-position: center;
                                            "/>
                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                            <button type="submit"
                                    class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1 waves-effect waves-float waves-light">
                                {{ translate('حفظ') }}
                            </button>
                            <button type="reset"
                                    class="btn btn-outline-secondary waves-effect">{{ translate('تهيئة') }}
                            </button>
                        </div>
                    </div>
                </form>


            </div>
        </div>


    </div>
    <!-- / Content -->

@endsection

@section('js')

    <script src="{{ asset('dashboard/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>


    <script>
        $('#notificationForm').on('submit', function (e) {
            e.preventDefault();
            $(":submit").prop('disabled', true)

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.notifications.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    // $('#loading').show();
                },
                success: function (data) {
                    $(":submit").prop('disabled', false)
                    // $('#loading').hide();
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('تم حفظ الاشعار بنجاح') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.notifications.index')}}';
                        }, 2000);
                    }
                }, error: function () {
                    $(":submit").prop('disabled', false)
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            var dropZone = $('#dropZone');

            dropZone.on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
            }).on('drop', function (e) {
                var files = e.originalEvent.dataTransfer.files;
                handleFiles(files);
            });

            $('#image').on('change', function () {
                var files = $(this)[0].files;
                handleFiles(files);
            });

            function handleFiles(files) {
                var image = document.getElementById('output_image');
                var src = URL.createObjectURL(files[0]);
                $('#image')[0].files = files
                image.style.backgroundImage = 'url(' + src + ')';
            }

        });
    </script>

@stop
