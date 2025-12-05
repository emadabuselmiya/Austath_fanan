@extends('layouts.admin.app')
@section('title')
    {{ translate('تعديل') }} {{ translate('الاشعار') }}
@stop
@section('css')

    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/bootstrap-select/bootstrap-select.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/select2/select2.css') }}"/>

@stop

@section('content')

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">{{ translate('تعديل') }} {{ translate('الاشعار') }}</h4>

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
                                               class="form-label">{{ translate('العنوان') }} {{ translate('بالعربي') }}
                                            <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="title" name="title" class="form-control"
                                               value="{{$notification->title}}"
                                               placeholder="{{ translate('العنوان') }} {{ translate('بالعربي') }}"
                                               required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="title_en"
                                               class="form-label">{{ translate('العنوان') }} {{ translate('بالانجليزي') }}
                                            <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="title_en" name="title_en" class="form-control"
                                               value="{{$notification->title_en}}"
                                               placeholder="{{ translate('العنوان') }} {{ translate('بالانجليزي') }}"
                                               required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="description"
                                               class="form-label">{{ translate('المحتوى') }} {{ translate('بالعربي') }}
                                            <span
                                                class="text-danger">*</span></label>
                                        <textarea name="description" id="description" class="form-control" cols="10"
                                                  rows="5"
                                                  placeholder="{{ translate('المحتوى') }} {{ translate('بالعربي') }}"
                                                  required>{{$notification->description}}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="description_en"
                                               class="form-label">{{ translate('المحتوى') }} {{ translate('بالانجليزي') }}
                                            <span
                                                class="text-danger">*</span></label>
                                        <textarea name="description_en" id="description_en" class="form-control"
                                                  cols="10" rows="5"
                                                  placeholder="{{ translate('المحتوى') }} {{ translate('بالانجليزي') }}"
                                                  required>{{$notification->description_en}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" id="dropZone">
                                <div class="form-group mt-2">
                                    <label for="username">{{ translate('الصورة') }}</label>
                                    <input type="file" name="image" id="image"
                                           style="display:none;"/>
                                    <button id="output_image" type="button"
                                            onclick="document.getElementById('image').click();"
                                            style=" width: 200px;
                                            height: 150px;
                                            border-radius: 10%;
                                            background-color: #0a1128;
                                            background-image: url({{ $notification->image_url }});
                                            background-repeat: no-repeat;
                                            background-size: cover;
                                            background-position: center;
                                            "/>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">

                            <div class="col-md-4">
                                <label for="city" class="form-label">{{ translate('المحافظة') }}<span
                                        class="text-danger">*</span></label>

                                <select id="city" name="city" class="form-control select2">
                                    <option value="all">{{translate('كل المحافظات')}}</option>
                                    @foreach(\App\Models\City::orderBy('name')->get() as $city)
                                        <option
                                            value="{{ $city->id }}" {{$notification->city_id == $city->id ? 'selected' : ''}}>{{ $city->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-md-4">
                                <label for="status" class="form-label">{{ translate('نوع الارسال') }}<span
                                        class="text-danger">*</span></label>

                                <select id="status" name="status" required class="form-control select2"
                                        onchange="schedule_at_change(this.value)">
                                    <option
                                        value="1" {{$notification->status == '1' ? 'selected' : ''}}>{{translate('ارسال فوري')}}</option>
                                    <option
                                        value="2" {{$notification->status == '2' ? 'selected' : ''}}>{{translate('ارسال مجدول')}}</option>

                                </select>

                            </div>

                            <div class="col-md-4" id="schedule_at_div"
                                 style="display: {{$notification->status == '2' ? 'block' : 'none'}}">
                                <label for="schedule_at" class="form-label">{{ translate('وقت الارسال') }}<span
                                        class="text-danger">*</span></label>

                                <input type="datetime-local" id="schedule_at" name="schedule_at"
                                       value="{{ $notification->schedule_at }}" class="form-control">

                            </div>

                            <div class="col-md-4">
                                <label for="target" class="form-label">{{ translate('ارسال الى') }}<span
                                        class="text-danger">*</span></label>

                                <select id="target" name="target[]" required onchange="notify_target_change(this.value)"
                                        multiple="multiple" class="form-control select2">
                                    <option
                                        value="customer" {{in_array('customer', $notification->target) ? 'selected' : ''}}>{{translate('الزبائن')}}</option>
                                    <option
                                        value="guest" {{in_array('guest', $notification->target) ? 'selected' : ''}}>{{translate('الزوار')}}</option>
                                    <option
                                        value="deliveryman" {{in_array('deliveryman', $notification->target) ? 'selected' : ''}}>{{translate('كباتن التوصيل')}}</option>
                                    {{--                                    <option value="restaurant">{{translate('messages.restaurant')}}</option>--}}
                                </select>

                            </div>
                            <div class="col-md-4" id="platform" style="display: {{in_array('customer', $notification->target) || in_array('guest', $notification->target) ? 'black' : 'none'}};">
                                <label for="platform" class="form-label">{{ translate('منصة') }}<span
                                        class="text-danger">*</span></label>

                                <select id="platform" name="platform" class="form-control select2">
                                    <option
                                        value="all" {{$notification->platform == '' ? 'selected' : ''}}>{{translate('كل منصات')}}</option>
                                    <option
                                        value="ios" {{$notification->platform == 'ios' ? 'selected' : ''}}>{{translate('ios')}}</option>
                                    <option
                                        value="android" {{$notification->platform == 'android' ? 'selected' : ''}}>{{translate('اندرويد')}}</option>
                                </select>

                            </div>
                            <div class="col-md-4" id="type"
                                 style="display: {{in_array('customer', $notification->target) || in_array('guest', $notification->target) ? 'black' : 'none'}};">
                                <label for="type" class="form-label">{{ translate('الهدف') }}</label>

                                <select id="type" name="type" required onchange="notify_type_change(this.value)"
                                        class="form-control select2">
                                    <option disabled selected>{{translate('اختار الهدف')}}</option>
                                    <option
                                        value="store" {{$notification->type == "store" ? 'selected' : ''}}>{{translate('المتجر')}}</option>
                                    <option
                                        value="market" {{$notification->type == "market" ? 'selected' : ''}}>{{translate('ماركت')}}</option>
                                    <option
                                        value="product" {{$notification->type == "product" ? 'selected' : ''}}>{{translate('المنتج')}}</option>
                                    <option
                                        value="category" {{$notification->type == "category" ? 'selected' : ''}}>{{translate('الفئات')}}</option>
                                    <option
                                        value="url" {{$notification->type == "url" ? 'selected' : ''}}>{{translate('url')}}</option>
                                    <option
                                        value="invite_friend" {{$notification->type == "invite_friend" ? 'selected' : ''}}>{{translate('صفحة دعوة صديق')}}</option>
                                    <option
                                        value="points" {{$notification->type == "points" ? 'selected' : ''}}>{{translate('صفحة نقاطي')}}</option>
{{--                                    <option value="wallet" {{$notification->type == "wallet" ? 'selected' : ''}}>{{translate('صفحة المحفظة')}}</option>--}}
                                </select>

                            </div>

                            <div class="col-md-4" id="type_dm"
                                 style="display: {{in_array('deliveryman', $notification->target) ? 'black' : 'none'}};">
                                <label for="type" class="form-label">{{ translate('الهدف') }}</label>

                                <select id="type" name="type" required onchange="notify_type_change(this.value)"
                                        class="form-control select2">
                                    <option disabled selected>{{translate('اختار الهدف')}}</option>
                                    <option
                                        value="all" {{$notification->type == "all" ? 'selected' : ''}}>{{translate('كل الكباتن')}}</option>
                                    <option
                                        value="dm_online" {{$notification->type == "dm_online" ? 'selected' : ''}}>{{translate('الكباتن المتصلين')}}</option>
                                    <option
                                        value="dm_offline" {{$notification->type == "dm_offline" ? 'selected' : ''}}>{{translate('الكباتن الغير متصلين')}}</option>
                                </select>

                            </div>

                            <div class="col-md-4" id="store"
                                 style="display:{{ $notification->type == 'store' ? 'black' : 'none' }};">
                                <label for="target" class="form-label">{{ translate('المتجر') }}<span
                                        class="text-danger">*</span></label>

                                <select name="store" class="select2 form-control"
                                        title="Select Store">
                                    <option selected disabled>{{ translate('اختار المتجر') }}</option>
                                    @php($stores = \App\Models\Store::active()->get())
                                    @foreach($stores as $store)
                                        <option
                                            value="{{ $store->id }}" {{$notification->store_id == $store->id ? 'selected' : ''}}>{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4" id="product"
                                 style="display:{{ $notification->type == 'product' ? 'black' : 'none' }};">
                                <label for="target" class="form-label">{{ translate('المنتج') }}<span
                                        class="text-danger">*</span></label>

                                <select name="product" class="select2 form-control"
                                        title="Select product">
                                    <option selected disabled>{{ translate('اختار المنتج') }}</option>
                                    @if( $notification->type == 'product' )
                                        @php($product = \App\Models\Product::where('id', $notification->product_id)->first())
                                        @if($product)
                                            <option value="{{ $product->id }}" selected>{{ $product->name }}</option>
                                        @endif
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-4" id="category"
                                 style="display:{{ $notification->type == 'category' ? 'black' : 'none' }};">
                                <label for="target" class="form-label">{{ translate('الفئات') }}<span
                                        class="text-danger">*</span></label>

                                <select name="category" class="select2 form-control"
                                        title="Select Category">
                                    <option selected disabled>{{ translate('اختار الفئات') }}</option>
                                    <option
                                        value="-2" {{ '-2' == $notification->category_id ? 'selected' : '' }}>{{ translate('عروض خاصة') }}</option>
                                    @php($categories = \App\Models\Category::active()->get())
                                    @foreach($categories as $category)
                                        <option
                                            value="{{ $category->id }}" {{ $category->id == $notification->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4" id="url"
                                 style="display:{{ $notification->type == 'url' ? 'black' : 'none' }};">
                                <label for="url" class="form-label">{{ translate('URL') }}</label>

                                <input type="url" name="url" id="url" class="form-control"
                                       value="{{$notification->url}}">
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

    </div>
    <!-- Content wrapper -->

@endsection

@section('js')

    <script src="{{ asset('dashboard/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('dashboard/js/forms-selects.js') }}"></script>


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
                url: '{{route('admin.notifications.update', $notification->id)}}',
                data: $('#notificationForm').serialize(),
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
                        toastr.success('{{ translate('تم تعديل الاشعار بنجاح') }}', {
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
        function notify_target_change(target_type) {
            if (target_type === 'customer' || target_type === 'guest') {
                $('#type_dm').hide();
                $('#type').show();
                $('#platform').show();
                $('#store').hide();
                $('#product').hide();
                $('#url').hide();
            } else if (target_type === 'deliveryman') {
                $('#type_dm').show();
                $('#platform').hide();
                $('#type').hide();
                $('#store').hide();
                $('#product').hide();
                $('#url').hide();
            } else {
                $('#type_dm').hide();
                $('#platform').hide();
                $('#type').hide();
                $('#store').hide();
                $('#product').hide();
                $('#url').hide();
            }
        }

        function schedule_at_change(type) {
            if (type == 2) {
                $('#schedule_at_div').show();
            } else {
                $('#schedule_at_div').hide();
            }
        }

        function notify_type_change(type) {
            if (type === 'store') {
                $('#store').show();
                $('#product').hide();
                $('#category').hide();
                $('#url').hide();
            } else if (type === 'product') {
                $('#store').hide();
                $('#category').hide();
                $('#product').show();
                $('#url').hide();
            } else if (type === 'category') {
                $('#store').hide();
                $('#category').show();
                $('#product').hide();
                $('#url').hide();
            } else if (type === 'url') {
                $('#store').hide();
                $('#category').hide();
                $('#product').hide();
                $('#url').show();
            } else {
                $('#store').hide();
                $('#category').hide();
                $('#product').hide();
                $('#url').hide();
            }
        }

        notify_type_change('{{ $notification->type }}');

        $('.js-product-ajax').select2({
            ajax: {
                url: '{{route('admin.products.get_products')}}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);
                    $request.then(success);
                    $request.fail(failure);
                    return $request;
                }
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
                var image = document.getElementById('output_image');
                var src = URL.createObjectURL(files[0]);
                $('#image')[0].files = files
                image.style.backgroundImage = 'url(' + src + ')';
            }

        });
    </script>

@stop
