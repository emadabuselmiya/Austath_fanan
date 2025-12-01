@extends('layouts.admin.app')
@section('title')
    {{ translate('اضافة درس جديد') }}
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/select2/select2.css') }}"/>

@stop

@section('content')

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">{{ translate('اضافة درس جديد') }}</h4>

        <form action="javascript:" method="post" id="lesson_form" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                    <span class="card-header-icon">
                                        <span class="iconify" data-icon="streamline:class-lesson"
                                              data-width="25" data-height="25"></span>
                                    </span>

                                <span>{{ translate('معلومات الدرس') }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('الاسم') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ $lesson->name }}"
                                               class="form-control" placeholder="{{ translate('اسم') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('الموضوع') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="subject_id" id="subject_id"
                                                class="form-control select2">
                                            <option value="">{{ translate('اختار الموضوع') }}</option>
                                            @php($subjects = \App\Models\Subject::get())
                                            @foreach($subjects as $subject)
                                                <option
                                                    value="{{ $subject->id }}" {{ $lesson->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('الوصف') }}<span
                                                class="text-danger">*</span></label>

                                        <textarea name="description" id="description" class="form-control"
                                                  cols="30" rows="5">{{ $lesson->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" id="dropZone">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                    <span class="card-header-icon">
                                        <span class="iconify" data-icon="material-symbols:image-outline" data-width="25"
                                              data-height="25"></span>
                                    </span>
                                <span>{{ translate('صورة') }} <small
                                        class="text-danger">({{ translate('Ratio 200x200') }})</small></span>
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <center class="my-auto">
                                    <img height="200"
                                         width="200" id="viewer"
                                         src="{{ $lesson->img_url }}"
                                         alt="image"/>
                                </center>

                                <input type="file" name="image" id="image"
                                       class="form-control"
                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            </div>
                        </div>


                    </div>
                </div>

                <div class="col-lg-12 mt-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                    <span class="card-header-icon">
                                        <span class="iconify" data-icon="ph:video"
                                              data-width="25" data-height="25"></span>
                                    </span>
                                <span> {{ translate('الفيديو') }}<span class="text-danger">*</span></span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <!-- عرض الفيديو بعد التحميل -->
                                <video id="viewer_video" width="auto" height="300" controls>
                                    <source src="{{ $lesson->video_url }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>

                                <!-- إدخال ملف الفيديو -->
                                <input type="file" name="video" id="video" class="form-control"
                                       accept=".mp4, .mov, .avi, .webm, .flv, .mkv, video/*">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-12 mt-3">
                    <div class="card">
                        <div class="card-footer justify-content-evenly">
                            <button type="submit"
                                    class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1 waves-effect waves-float waves-light">
                                {{ translate('حفظ') }}
                            </button>
                            <button type="reset"
                                    class="btn btn-outline-secondary waves-effect">{{ translate('تهيئة') }}
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>


    </div>
    <!-- / Content -->

@endsection


@section('js')
    <script src="{{ asset('dashboard/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('app-assets/js/tags-input.min.js') }}"></script>
    <script src="{{ asset('assets/tagsinput/bootstrap-tagsinput.js') }}"></script>

    <script>
        $('#lesson_form').on('submit', function (e) {
            e.preventDefault();
            $(":submit").prop('disabled', true)

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.lessons.store_copy', $lesson->id) }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $(":submit").prop('disabled', false)
                    $('#loading').hide();
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('تم نسخ الدرس بنجاح') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href =
                                '{{ route('admin.lessons.index')}}';
                        }, 2000);
                    }
                }, error: function () {
                    $(":submit").prop('disabled', false)
                }
            });
        });
    </script>

    <script>
        // جافا سكربت لتحميل الفيديو وعرضه
        document.getElementById('video').addEventListener('change', function (event) {
            var file = event.target.files[0];
            if (file) {
                var viewer = document.getElementById('viewer_video');
                var url = URL.createObjectURL(file);
                viewer.src = url;  // تغيير مصدر الفيديو
            }
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
                var image = document.getElementById('viewer');
                image.src = URL.createObjectURL(files[0]);
                $('#image')[0].files = files
            }

        });
    </script>

@stop
