@extends('layouts.admin.app')

@section('title')
    {{ translate('الاشعارات') }}
@stop

@section('css')

    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/fullcalendar/fullcalendar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/flatpickr/flatpickr.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/quill/editor.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/@form-validation/umd/styles/index.min.css') }}"/>

    <link rel="stylesheet" href="{{ asset('dashboard/vendor/css/pages/app-calendar.css') }}"/>

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/node-waves/node-waves.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/typeahead-js/typeahead.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}"/>
    <link rel="stylesheet"
          href="{{ asset('dashboard/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}"/>
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/libs/select2/select2.css') }}"/>
    <!-- END: Vendor CSS-->
@stop

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">{{ translate('الاشعارات') }}</h4>

        <!-- Ajax Sourced Server-side -->
        <div class="card mt-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="list-table table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('العنوان')}}</th>
                        <th>{{translate('المحتوى')}}</th>
                        <th>{{translate('الصورة')}}</th>
                        <th>{{translate('التاريخ')}}</th>
                        <th>{{translate('العمليات')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--/ Ajax Sourced Server-side -->

    </div>
    <!-- / Content -->


    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('حذف') }} {{ translate('الاشعار') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        {{ translate('هل انت متاكد من عملية الحذف؟') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" type="reset" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">{{ translate('اغلاق') }}</button>
                        <button type="submit" class="btn btn-danger mr-1 data-submit">{{ translate('حذف') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('dashboard/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/libs/fullcalendar/fullcalendar.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/libs/moment/moment.js') }}"></script>

    <!-- END: Page Vendor JS-->
    <script src="{{ asset('dashboard/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>


    <script>
        const status = {
            1: {title: '{{translate('فعال')}}', class: 'bg-label-success'},
            0: {title: '{{translate('غير فعال')}}', class: 'bg-label-danger'}
        };

        var dtTickerTable = $('.list-table');
        if (dtTickerTable.length) {
            dtTickerTable.DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: '{{ route('admin.notifications.index') }}'
                }, // JSON file to add data
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'title'},
                    {data: 'description'},
                    {data: 'image'},
                    {data: 'created_at'},
                ],
                order: [
                    [4, 'DESC']
                ],
                dom: '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                    '<"col-lg-12 col-xl-6" l>' +
                    '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                language: {
                    sLengthMenu: '{{ translate('عرض _MENU_') }}',
                    search: '{{ translate('بحث') }}',
                    zeroRecords: '{{ translate('لم يتم العثور على سجلات مطابقة') }}',
                    info: "{{ translate('عرض الصفحات _PAGE_ من _PAGES_ من _TOTAL_ عنصر') }}",
                    infoEmpty: '{{ translate('إظهار 0 صفحات') }}',
                    searchPlaceholder: '{{translate('بحث')}}..',
                    paginate: {
                        // remove previous & next text from pagination\
                        previous: '{{translate('السابق')}}',
                        next: '{{translate('التالي')}}'
                    }
                },
                // Buttons with Dropdown
                buttons: [
                    {
                        text: '<i class="tf-icons ti ti-circle-plus"></i>',
                        className: 'btn btn-secondary btn-sm btn-primary mx-1',
                        attr: {
                            "type": "button",
                            "onclick": "window.location='{{route('admin.notifications.create')}}'"
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],
            });
        }
    </script>

@stop
