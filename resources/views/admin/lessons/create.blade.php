@extends('layouts.admin.app')
@section('title')
    {{ translate('اضافة منتج جديد') }}
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/select2/select2.css') }}"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        #sortable {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 60%;
        }

        #sortable li {
            margin: 0 5px 5px 5px;
            padding: 5px;
            font-size: 1.2em;
            height: 1.5em;
        }

        html > body #sortable li {
            height: 1.5em;
            line-height: 1.2em;
        }

        .ui-state-highlight {
            height: 1.5em;
            line-height: 1.2em;
        }

        #img_container {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .image {
            width: 150px;
            margin: 5px;
        }

        .hidden {
            position: absolute;
            display: none;
            left: -9999px;
        }

        .img_block {
            position: relative;
        }

        .img_label {
            display: block;
            cursor: pointer;
        }

        .plus {
            width: 100px;
            height: 100px;
            font-size: 50px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2060FF;
            border-style: dotted;
            border-color: blue;
        }

        .plus:after {
            content: '+';
        }

        .cross {
            width: 15px;
            height: 15px;
            font-size: 20px;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FF2060;
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }

        .cross:hover {
            background-color: rgba(255, 255, 255, 0.6);
        }

        .cross:after {
            content: 'x';
        }
    </style>

@stop

@section('content')

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">{{ translate('اضافة منتج جديد') }}</h4>

            <form action="javascript:" method="post" id="food_form" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <span class="card-header-icon">
                                        <span class="iconify" data-icon="material-symbols:fastfood-outline-rounded"
                                              data-width="25" data-height="25"></span>
                                    </span>

                                    <span>{{ translate('معلومات المنتج') }}</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="form-label">{{ translate('الاسم') }} {{ translate('بالعربي') }}
                                                <span class="text-danger">*</span></label>
                                            <input type="text" name="name_ar"
                                                   class="form-control"
                                                   placeholder="{{ translate('اسم المنتج') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="form-label">{{ translate('الاسم') }} {{ translate('بالانجليزي') }}</label>
                                            <input type="text" name="name_en"
                                                   class="form-control"
                                                   placeholder="{{ translate('اسم المنتج') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="form-label">{{ translate('الوصف') }} {{ translate('بالعربي') }}
                                                <span class="text-danger">*</span></label>

                                            <textarea name="description_ar" id="description_ar" class="form-control"
                                                      cols="30" rows="5"></textarea>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="form-label">{{ translate('الوصف') }} {{ translate('بالانجليزي') }}</label>

                                            <textarea name="description_en" id="description_en" class="form-control"
                                                      cols="30" rows="5"></textarea>
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
                                    <span>{{ translate('صورة المنتج') }} <small
                                            class="text-danger">({{ translate('Ratio 200x200') }})</small></span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <center class="my-auto">
                                        <img height="200"
                                             width="200" id="viewer"
                                             src="{{ asset('app-assets/images/user.png') }}"
                                             alt="delivery-man image"/>
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
                                        <span class="iconify" data-icon="material-symbols:dashboard-customize-outline"
                                              data-width="25" data-height="25"></span>
                                    </span>
                                    <span> {{ translate('تفاصيل المنتج') }}</span>
                                </h5>
                            </div>
                            @php($store = \Request::get('store_id') ? \App\Models\Store::where('id', \Request::get('store_id'))->first() : null)
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label"
                                                   for="exampleFormControlSelect1">{{ translate('المتجر') }}
                                                <span
                                                    class="form-label-secondary"></span></label>
                                            <select name="store_id" id="store_id"
                                                    data-placeholder="{{ translate('اختار المتجر') }}"
                                                    class="js-data-store-ajax form-control"
                                                    oninvalid="this.setCustomValidity('{{ translate('الرجاء تحديد المتجر') }}')">
                                                <option value="" disabled
                                                        selected>{{ translate('اختار المتجر') }}</option>

                                                @if ($store)
                                                    <option value="{{ $store->id }}" selected="selected">{{ $store->name }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-4" id="div_menu" style="display: {{ $store ? ($store->store_type_id!=3?'blank':'none') : 'blank' }}">
                                        <div class="form-group mb-0">
                                            <label class="form-label"
                                                   for="exampleFormControlInput1">{{ translate('القائمة') }}</label>
                                            <select name="menu_id" id="menu_id"
                                                    class="form-control select2">
                                                <option value="" selected
                                                        disabled>{{ translate('اختار القائمة') }}</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4" id="div_category" style="display: {{ $store ? ($store->store_type_id==3?'blank':'none') : 'none' }}">
                                        <div class="form-group mb-0">
                                            <label class="form-label" for="category_id">{{ translate('الفئة') }}</label>
                                            <select name="category_id" id="category_id"
                                                    class="form-control select2">
                                                <option value="">{{ translate('اختار الفئة') }}</option>
                                                @php($categories = \App\Models\Category::active()->where('type', 1)->get())
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <span class="card-header-icon">
                                        <span class="iconify" data-icon="ph:currency-circle-dollar-bold" data-width="25"
                                              data-height="25"></span>                                    </span>
                                    <span>{{ translate('مبلغ') }}</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    @if(auth()->guard('admin')->user()->can('purchase_price', App\Models\Product::class))

                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <label class="form-label"
                                                       for="purchase_price">{{ translate('سعر الشراء') }}</label>
                                                <input type="number" min="0" max="999999999999.99" id="purchase_price"
                                                       step="0.01" name="purchase_price" class="form-control"
                                                       placeholder="{{ translate('messages.Ex :') }} 100">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="form-label"
                                                   for="price">{{ translate('السعر') }}</label>
                                            <input type="number" min="0" max="999999999999.99" id="price"
                                                   step="0.01" value="1" name="price" class="form-control"
                                                   placeholder="{{ translate('messages.Ex :') }} 100" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-6 mt-4">

                                        <div class="form-control form-inline d-flex justify-content-between">
                                            <label class="form-label"
                                                   for="modern-twitter">{{ translate('عرض على المنتج') }}</label>
                                            <label class="switch">
                                                <input type="hidden" value="0" name="is_offer">
                                                <input type="checkbox" class="switch-input is-valid" name="is_offer"
                                                       value="1" onchange="$('#div_offer').toggle()">
                                                <span class="switch-toggle-slider">
                                          <span class="switch-on"></span>
                                          <span class="switch-off"></span>
                                        </span>
                                                <span class="switch-label"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mt-3" id="div_offer" style="display: none">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ translate('بيانات العرض') }}</h4>

                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="payment_method">{{translate('طريقة الدفع')}}
                                                <span class="input-label-secondary"></span></label>

                                            <select name="payment_method[]" id="payment_method"
                                                    class="select2 form-control"
                                                    multiple="true"
                                                    data-placeholder="{{translate('اختار طريقة الدفع')}}">
                                                <option value="cash_on_delivery">{{translate('دفع كاش')}}</option>
                                                <option value="lahza_payment">{{translate('دفع فيزا')}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label" for="area_ids">{{translate('مناطق التغطية')}}
                                                <span class="input-label-secondary"></span></label>

                                            <select name="area_ids[]" id="area_ids" class="select2 form-control"
                                                    multiple="true"
                                                    data-placeholder="{{translate('اختار المناطق')}}">
                                                <option value="all">{{translate('كل المناطق')}}</option>
                                                @php($areas = \App\Models\Area::all())
                                                @foreach($areas as $area)
                                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="date_from">{{translate("تاريخ البدء")}}</label>
                                            <input type="date" name="start_date" class="form-control" id="date_from">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <div class="form-group">
                                            <label class="input-label"
                                                   for="date_to">{{translate("تاريخ انتهاء الصلاحية")}}</label>
                                            <input type="date" name="expire_date" class="form-control" id="date_to">
                                        </div>
                                    </div>

                                </div>

                                <!-- customers edit account form ends -->

                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">{{ translate('اوقات التفعيل العرض') }}</h4>

                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-12 border-bottom">
                                        <div class="row">
                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <h5>{{translate('يوم الأحد')}}</h5>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 start_time0">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="start_time">{{translate("وقت البدء")}}</label>
                                                    <input id="start_time" type="time" name="start_time[0]"
                                                           class="form-control" value="00:00">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 expire_time0">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="expire_time">{{translate("وقت الانتهاء")}}</label>
                                                    <input id="expire_time" type="time" name="expire_time[0]"
                                                           class="form-control" value="23:59">
                                                </div>
                                            </div>

                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input is-valid"
                                                           name="disable_time[0]" value="1"
                                                           onchange="disableInput(0)" checked>
                                                    <span class="switch-toggle-slider">
                                          <span class="switch-on"></span>
                                          <span class="switch-off"></span>
                                        </span>
                                                    <span class="switch-label"></span>
                                                </label>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-12 border-bottom">
                                        <div class="row">
                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <h5>{{translate('يوم الاثنين')}}</h5>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 start_time1">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="start_time">{{translate("وقت البدء")}}</label>
                                                    <input id="start_time" type="time" name="start_time[1]"
                                                           class="form-control" value="00:00">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 expire_time1">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="expire_time">{{translate("وقت الانتهاء")}}</label>
                                                    <input id="expire_time" type="time" name="expire_time[1]"
                                                           class="form-control" value="23:59">
                                                </div>
                                            </div>
                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input is-valid" checked
                                                           name="disable_time[1]" value="1"
                                                           onchange="disableInput(1)">
                                                    <span class="switch-toggle-slider">
                                          <span class="switch-on"></span>
                                          <span class="switch-off"></span>
                                        </span>
                                                    <span class="switch-label"></span>
                                                </label>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-12 border-bottom">
                                        <div class="row">
                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <h5>{{translate('يوم الثلاثاء')}}</h5>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 start_time2">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="start_time">{{translate("وقت البدء")}}</label>
                                                    <input id="start_time" type="time" name="start_time[2]"
                                                           class="form-control" value="00:00">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 expire_time2">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="expire_time">{{translate("وقت الانتهاء")}}</label>
                                                    <input id="expire_time" type="time" name="expire_time[2]"
                                                           class="form-control" value="23:59">
                                                </div>
                                            </div>

                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input is-valid"
                                                           name="disable_time[2]" value="1"
                                                           checked onchange="disableInput(2)">
                                                    <span class="switch-toggle-slider">
                                          <span class="switch-on"></span>
                                          <span class="switch-off"></span>
                                        </span>
                                                    <span class="switch-label"></span>
                                                </label>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-12 border-bottom">
                                        <div class="row">
                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <h5>{{translate('يوم الأربعاء')}}</h5>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 start_time3">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="start_time">{{translate("وقت البدء")}}</label>
                                                    <input id="start_time" type="time" name="start_time[3]"
                                                           class="form-control" value="00:00">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 expire_time3">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="expire_time">{{translate("وقت الانتهاء")}}</label>
                                                    <input id="expire_time" type="time" name="expire_time[3]"
                                                           class="form-control" value="23:59">
                                                </div>
                                            </div>

                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input is-valid"
                                                           name="disable_time[3]" value="1"
                                                           checked onchange="disableInput(3)">
                                                    <span class="switch-toggle-slider">
                                          <span class="switch-on"></span>
                                          <span class="switch-off"></span>
                                        </span>
                                                    <span class="switch-label"></span>
                                                </label>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-12 border-bottom">
                                        <div class="row">
                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <h5>{{translate('يوم الخميس')}}</h5>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 start_time4">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="start_time">{{translate("وقت البدء")}}</label>
                                                    <input id="start_time" type="time" name="start_time[4]"
                                                           class="form-control" value="00:00">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 expire_time4">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="expire_time">{{translate("وقت الانتهاء")}}</label>
                                                    <input id="expire_time" type="time" name="expire_time[4]"
                                                           class="form-control" value="23:59">
                                                </div>
                                            </div>

                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input is-valid"
                                                           name="disable_time[4]" value="1"
                                                           checked onchange="disableInput(4)">
                                                    <span class="switch-toggle-slider">
                                          <span class="switch-on"></span>
                                          <span class="switch-off"></span>
                                        </span>
                                                    <span class="switch-label"></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-12 border-bottom">
                                        <div class="row">
                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <h5>{{translate('يوم الجمعة')}}</h5>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 start_time5">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="start_time">{{translate("وقت البدء")}}</label>
                                                    <input id="start_time" type="time" name="start_time[5]"
                                                           class="form-control" value="00:00">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 expire_time5">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="expire_time">{{translate("وقت الانتهاء")}}</label>
                                                    <input id="expire_time" type="time" name="expire_time[5]"
                                                           class="form-control" value="23:59">
                                                </div>
                                            </div>

                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input is-valid"
                                                           name="disable_time[5]" value="1"
                                                           checked onchange="disableInput(5)">
                                                    <span class="switch-toggle-slider">
                                          <span class="switch-on"></span>
                                          <span class="switch-off"></span>
                                        </span>
                                                    <span class="switch-label"></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-12 border-bottom">
                                        <div class="row">
                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <h5>{{translate('يوم السبت')}}</h5>
                                            </div>


                                            <div class="col-lg-3 col-sm-3 start_time6">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="start_time">{{translate("وقت البدء")}}</label>
                                                    <input id="start_time" type="time" name="start_time[6]"
                                                           class="form-control" value="00:00">
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-sm-3 expire_time6">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                           for="expire_time">{{translate("وقت الانتهاء")}}</label>
                                                    <input id="expire_time" type="time" name="expire_time[6]"
                                                           class="form-control" value="23:59">
                                                </div>
                                            </div>

                                            <div
                                                class="col-lg-2 col-sm-2 d-flex justify-content-center align-items-center">
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input is-valid"
                                                           name="disable_time[6]" value="1"
                                                           checked onchange="disableInput(6)">
                                                    <span class="switch-toggle-slider">
                                          <span class="switch-on"></span>
                                          <span class="switch-off"></span>
                                        </span>
                                                    <span class="switch-label"></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title">
                                            <span class="card-header-icon">
                                                <span class="iconify" data-icon="mdi:canvas" data-width="25"
                                                      data-height="25"></span>
                                            </span>
                                    <span>{{ translate('الاختلافات الغذائية') }}</span>
                                </h5>
                                <h5 class="card-title">
                                    <button type="button" onclick="copyVariationsForm()"
                                            class="btn btn-secondary">{{translate('نسخ السمات')}}</button>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="add_new_option">
                                        </div>
                                        <br>
                                        <div>
                                            <a class="btn btn-outline-success"
                                               id="add_new_option_button">{{ translate('إضافة شكل جديد') }}</a>
                                        </div>
                                        <br><br>
                                    </div>
                                </div>
                            </div>

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

    </div>
    <!-- Content wrapper -->

    <div id="copyVariationsModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('نسخ الاختلافات')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="copyVariationsForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ translate('يرجى اختيار المنتج') }}<span class="text-danger">*</span></label>
                            <select name="product" id="product"
                                    data-placeholder="{{ translate('اختار المنتج') }}"
                                    class="select2 form-control"
                                    oninvalid="this.setCustomValidity('{{ translate('الرجاء تحديد المنتج') }}')">
                                <option value="" disabled selected>{{ translate('اختار المنتج') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" type="reset" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">{{ translate('اغلاق') }}</button>
                        <button type="submit" class="btn btn-success mr-1 data-submit">{{ translate('نسخ') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('js')
    <script src="{{ asset('dashboard/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('app-assets/js/tags-input.min.js') }}"></script>
    <script src="{{ asset('assets/tagsinput/bootstrap-tagsinput.js') }}"></script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function () {
            $("#add_new_option").sortable({
                placeholder: "ui-state-highlight"
            });
            $("#add_new_option").disableSelection();
        });
    </script>

    <script>
        function radioGroup(key) {
            let inputs = document.querySelectorAll('.default_' + key);
            inputs.forEach(radio => {
                radio.addEventListener('click', function () {
                    // Uncheck all radios
                    inputs.forEach(r => r.checked = false);

                    // Check the clicked one
                    radio.checked = true;
                });
            });
        }

        function disableInput(id) {
            $('.start_time' + id).toggle();
            $('.expire_time' + id).toggle();
        }
    </script>

    <script>
        (function () {
            var fileInput = document.getElementById('file-input');
            var fileListDisplay = document.getElementById('file-list-display');

            var fileList = [];
            var renderFileList;


            fileInput.addEventListener('change', function (evnt) {
                fileList = [];
                for (var i = 0; i < fileInput.files.length; i++) {
                    console.log(fileInput.files)
                    fileList.push(fileInput.files[i]);
                }
                renderFileList();
            });

            renderFileList = function () {
                fileListDisplay.innerHTML = '';
                fileList.forEach(function (file, index) {
                    var input = event.target;
                    var dataURL = '';
                    const reader = new FileReader();
                    reader.onload = function () {
                        dataURL = reader.result;
                    };
                    var fileDisplayEl = document.createElement('img');
                    fileDisplayEl.setAttribute("src", dataURL);
                    fileDisplayEl.setAttribute("height", "100");
                    fileDisplayEl.setAttribute("width", "100");
                    fileDisplayEl.setAttribute("alt", file.name);
                    fileDisplayEl.innerHTML = (index + 1) + ': ' + file.name;
                    fileListDisplay.appendChild(fileDisplayEl);
                });
            };

        })();
    </script>

    <script>
        var container = document.getElementById('img_container');
        var placeholder = document.getElementById('placeholder');

        // utility function doing both createElement and setAttributes
        function create(elementName, attributes) {
            var elem = document.createElement(elementName);
            if (typeof attributes === 'object') {
                Object.keys(attributes).forEach(function (attributeName) {
                    elem.setAttribute(attributeName, attributes[attributeName]);
                });
            }
            return elem;
        }

        // load a file image as a data url and callback with this data url
        function loadImage(file, callback) {
            var reader = new FileReader();
            reader.onload = function () {
                var dataURL = this.result;
                callback(dataURL);
            };
            reader.readAsDataURL(file);
        }

        // self explainatory
        function createAndInsertNewImageBlock(id, dataURL) {
            var output = create('div', {'class': 'img_block'});

            // image label, linked to the file input through their for/id attributes
            var label = create('label', {'for': id, 'class': 'img_label'});
            var img = create('img', {'class': 'image', src: dataURL});
            label.appendChild(img);
            output.appendChild(label);

            // single file input triggered by the image label, it is hidden
            var input = create(
                'input',
                {
                    'type': 'file',
                    'class': 'hidden',
                    'name': 'gallery[]',
                    'accept': 'image/*',
                    id: id
                }
            );
            // load single data url on change and change the image src
            input.addEventListener('change', function () {
                loadImage(this.files.item(0), function (data) {
                    img.src = data;
                });
            });
            output.appendChild(input);

            // delete block button
            var cross = create('div', {'class': 'cross'});
            cross.addEventListener('click', function () {
                output.remove();
            });
            output.appendChild(cross);

            // insert new image block just before the '+' placeholder
            container.insertBefore(output, placeholder);
        }

        // handler for the onChange event of the placeholder's file input
        function openFiles(evt) {
            var files = evt.target.files;
            for (let i = 0; i < files.length; i++) {
                var file = files.item(i);
                loadImage(file, function (dataURL) {
                    var count = container.children.length;
                    // lame unique id generation for linking label to input
                    var id = 'img(' + count + '/' + (Date.now()).toString(16) + ')' + file.name;
                    createAndInsertNewImageBlock(id, dataURL);
                });
            }
            ;

        };
    </script>

    <script>
        var count = 0;
        // var countRow=0;
        $(document).ready(function () {
            $("#add_new_option_button").click(function (e) {
                count++;
                var add_option_view = `
        <div class="card view_new_option mb-2" >
            <div class="card-header">
                <h2 class="card-title" id=new_option_name_` + count + `> {{ translate('اضف جديد') }}</h2>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label" for="">{{ translate('الاسم') }} {{translate('بالعربي')}}</label>
                        <input required name="options[` + count + `][name][ar]" class="form-control" type="text" onkeyup="new_option_name(this.value,` + count + `)">
                    </div>
                     <div class="col-lg-2 col-md-6">
                        <label class="form-label" for="">{{ translate('الاسم') }} {{translate('بالانجليزي')}}</label>
                        <input required name="options[` + count + `][name][en]" class="form-control" type="text">
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <small class="text-light fw-semibold d-block">{{ translate('نوع الاختيار') }}</small>
                        <div class="d-flex justify-content-evenly">
                            <div class="form-check form-check-primary mt-3">
                              <input class="form-check-input" type="radio" value="multi" id="type` + count + `" checked
                                    name="options[` + count + `][type]" onchange="show_min_max(` + count + `)" />
                              <label class="form-check-label" id="type` + count + `"> {{ translate('متعدد') }} </label>
                            </div>

                             <div class="form-check form-check-secondary mt-3">
                              <input class="form-check-input" type="radio" value="single" id="type` + count + `"
                                    name="options[` + count + `][type]" onchange="hide_min_max(` + count + `)" />
                              <label class="form-check-label" id="type` + count + `"> {{ translate('أحادي') }} </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 col-lg-6">
                        <div class="row g-2">
                            <div class="col-sm-6 col-md-4">
                                <input type="hidden" name="options[` + count + `][min]" value="">
                                <label for="min_max1_` + count + `" class="form-label">{{ translate('الحد الأدنى') }}</label>
                                <input id="min_max1_` + count + `" required  name="options[` + count + `][min]" class="form-control" type="number" min="1">
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <input type="hidden" name="options[` + count + `][max]" value="">
                                <label for="min_max2_` + count + `" class="form-label">{{ translate('الحد الاقصى') }}</label>
                                <input id="min_max2_` + count + `"   required name="options[` + count + `][max]" class="form-control" type="number" min="1">
                            </div>

                            <div class="col-md-4">
                                <label class="d-md-block d-none">&nbsp;</label>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="form-check form-check-success">
                                          <input class="form-check-input" type="checkbox" id="options[` + count + `][required]" name="options[` + count + `][required]" />
                                          <label class="form-check-label" for="options[` + count + `][required]">{{ translate('مطلوب') }}</label>
                                        </div>

                                    <div>
                                        <button type="button" class="btn btn-danger btn-sm delete_input_button" onclick="removeOption(this)"
                                            title="{{ translate('حذف') }}">
                                            <span class="iconify" data-icon="ph:trash-bold" data-width="25" data-height="25"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="option_price_` + count + `" >
                    <div class="border rounded p-3 pb-0 mt-3">
                        <div  id="option_price_view_` + count + `">
                            <div class="row g-3 add_new_view_row_class mb-3">
                                <div class="col-md-3 col-sm-6">
                                    <label for="">{{ translate('اسم الخيار') }} {{translate('بالعربي')}}</label>
                                    <input class="form-control" required type="text" name="options[` + count + `][values][0][label][ar]" id="">
                                </div>
                                 <div class="col-md-3 col-sm-6">
                                    <label for="">{{ translate('اسم الخيار') }} {{translate('بالانجليزي')}}</label>
                                    <input class="form-control" required type="text" name="options[` + count + `][values][0][label][en]" id="">
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <label for="">{{ translate('سعر إضافي') }}</label>
                                    <input class="form-control" required type="number" min="0" step="0.01" name="options[` + count + `][values][0][optionPrice]" id="">
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-check form-check-primary mt-3">
                                      <input class="form-check-input default_` + count + `" type="radio" value="1" id="default_` + count + `"
                                            name="options[` + count + `][values][0][default]" onclick="radioGroup(` + count + `)" />
                                      <label class="form-check-label" for="default_` + count + `"> {{ translate('افتراضي') }} </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mr-1" id="add_new_button_` + count + `">
                            <button type="button" class="btn btn-outline-primary" onclick="add_new_row_button(` + count + `)" >{{ translate('إضافة خيار جديد') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

                $("#add_new_option").append(add_option_view);
            });
        })


        function show_min_max(data) {
            $('#min_max1_' + data).removeAttr("readonly");
            $('#min_max2_' + data).removeAttr("readonly");
            $('#min_max1_' + data).attr("required", "true");
            $('#min_max2_' + data).attr("required", "true");
            $('#min_max1_' + data).removeAttr("disabled");
            $('#min_max2_' + data).removeAttr("disabled");
        }

        function hide_min_max(data) {
            $('#min_max1_' + data).val(null).trigger('change');
            $('#min_max2_' + data).val(null).trigger('change');
            $('#min_max1_' + data).attr("required", "false");
            $('#min_max2_' + data).attr("required", "false");
            $('#min_max1_' + data).attr("disabled", "true");
            $('#min_max2_' + data).attr("disabled", "true");
        }

        function new_option_name(value, data) {
            $("#new_option_name_" + data).empty();
            $("#new_option_name_" + data).text(value)
            console.log(value);
        }

        function removeOption(e) {
            element = $(e);
            element.parents('.view_new_option').remove();
        }

        function deleteRow(e) {
            element = $(e);
            element.parents('.add_new_view_row_class').remove();
        }


        function add_new_row_button(data) {
            count = data;
            countRow = 1 + $('#option_price_view_' + data).children('.add_new_view_row_class').length;
            var add_new_row_view = `
                <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-sm-0">
                        <div class="col-md-3 col-sm-5">
                            <label for="">{{ translate('اسم الخيار') }} {{translate('بالعربي')}}</label>
                            <input class="form-control" required type="text" name="options[` + count + `][values][` + countRow + `][label][ar]" id="">
                        </div>
                        <div class="col-md-3 col-sm-5">
                            <label for="">{{ translate('اسم الخيار') }} {{translate('بالانجليزي')}}</label>
                            <input class="form-control" required type="text" name="options[` + count + `][values][` + countRow + `][label][en]" id="">
                        </div>
                        <div class="col-md-2 col-sm-5">
                            <label for="">{{ translate('سعر إضافي') }}</label>
                            <input class="form-control"  required type="number" min="0" step="0.01" name="options[` + count + `][values][` + countRow + `][optionPrice]" id="">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="form-check form-check-primary mt-3">
                              <input class="form-check-input  default_` + count + `" type="radio" value="1" id="default_` + count + `"
                                    name="options[` + count + `][values][` + countRow + `][default]" onclick="radioGroup(` + count + `)" />
                              <label class="form-check-label" for="default_` + count + `"> {{ translate('افتراضي') }} </label>
                            </div>
                        </div>
                        <div class="col-sm-1 max-sm-absolute">
                            <label class="d-none d-sm-block">&nbsp;</label>
                            <div class="mt-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)"
                                    title="{{ translate('حذف') }}">
                                            <span class="iconify" data-icon="ph:trash-bold" data-width="25" data-height="25"></span>
                                </button>
                            </div>
                    </div>
                </div>`;
            $('#option_price_view_' + data).append(add_new_row_view);

        }
    </script>

    <script>
        function copyVariationsForm() {
            var modal = $('#copyVariationsModal');
            modal.modal('show');
        }

        $('#copyVariationsForm').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.products.variant_combination') }}?count=' + count,
                data: $('#copyVariationsForm').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#loading').hide();
                    $('#add_new_option').append(data.view);
                    count = data.count;
                    $('#copyVariationsModal').modal('hide');

                    toastr.success('{{ translate('تم نسخ الاختلافات بنجاح') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

    </script>

    <script>
        function getStoreData(route, store_id, id) {
            $.get({
                url: route + store_id,
                dataType: 'json',
                success: function (data) {
                    console.log(data)
                },
            });
        }


        function getRequest(route, id) {
            $('#' + id).empty();

            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (key, value) {

                        if (id == 'product' || id == 'menu_id') {
                            $('#' + id).append($("<option></option>")
                                .attr("value", value.id)
                                .attr("selected", false)
                                .text(value.text));
                        } else {
                            $('#' + id).append($("<option></option>")
                                .attr("value", value.id)
                                .attr("selected", false)
                                .text(value.name));
                        }

                    });
                },
            });
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
            $('#image-viewer-section').show(1000);
        });
    </script>

    <script>

        @if(\Request::get('store_id'))
        getRequest('{{route('admin.stores.menus.get_menus')}}?store_id={{ \Request::get('store_id') }}', 'menu_id');
        getRequest('{{route('admin.products.get_products')}}?store_id={{ \Request::get('store_id') }}', 'product');
        @endif
        $('.js-data-store-ajax').select2({
            ajax: {
                url: '{{ route('admin.stores.get_stores') }}',
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

        $('.js-data-store-ajax').on('change', function () {
            var store_id = $(this).val();
            $.get({
                url: '{{route('admin.stores.get_store', '')}}/' + store_id,
                dataType: 'json',
                success: function (data) {
                    if (data.store_type_id == 3) {
                        $('#div_menu').hide();
                        $('#div_category').show();
                    } else {
                        $('#div_menu').show();
                        $('#div_category').hide();
                    }
                    getRequest('{{route('admin.stores.menus.get_menus')}}?store_id=' + data.id, 'menu_id');
                    getRequest('{{route('admin.products.get_products')}}?store_id=' + data.id, 'product');
                },
            });
        });
    </script>

    <script>
        $('#food_form').on('submit', function (e) {
            e.preventDefault();
            $(":submit").prop('disabled', true)

            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.products.store') }}',
                data: $('#food_form').serialize(),
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
                        toastr.success('{{ translate('تم اضافة المنتج بنجاح') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href =
                                '{{ \Request::get('store_id') ? route('admin.stores.preview', ['id'=>\Request::get('store_id'), 'tab'=> 'products']) : route('admin.products.index')}}';
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
                var image = document.getElementById('viewer');
                image.src = URL.createObjectURL(files[0]);
                $('#image')[0].files = files
            }

        });
    </script>

@stop
