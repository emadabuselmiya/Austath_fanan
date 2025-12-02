@extends('layouts.admin.app')

@section('title')
    {{ translate('اكواد التفعيل') }}
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
@stop

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-2">{{ translate('اكواد التفعيل') }}</h4>

        <!-- users filter start -->
        <div class="card">
            <form>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">{{ translate('حالة الحساب') }}</label>
                            <select name="is_used" id="is_used" class="form-control select2">
                                <option value="">{{ translate('جميع الحالات') }}</option>
                                <option value="1" class="text-capitalize">{{translate('مستخدم')}}</option>
                                <option value="0" class="text-capitalize">{{translate('غير مستخدم')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" name="filter" id="filter"
                            class="btn btn-primary">{{ translate('بحث') }}</button>
                    <button type="reset"
                            class="btn btn-secondary">{{ translate('تهيئة') }}</button>
                </div>
            </form>
        </div>
        <!-- users filter end -->

        <div class="app-ecommerce-category mt-3">
            <!-- Category List Table -->
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="list-table table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('الكود')}}</th>
                            <th>{{translate("الطالب")}}</th>
                            <th>{{translate("المادة")}}</th>
                            <th>{{translate("تاريخ التفعيل")}}</th>
                            <th>{{translate("تاريخ الانتهاء")}}</th>
                            <th>{{translate("حالة الاستخدام")}}</th>
                            <th>{{translate("العمليات")}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/ Content -->


    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('حذف') }} {{ translate('الكود') }}</h5>
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
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <script>

        var dtTickerTable = $('.list-table');
        if (dtTickerTable.length) {
            dtTickerTable.DataTable({
                "processing": true,
                "serverSide": true,
                "lengthMenu": [
                    [10, 25, 50, 100, 1000000],
                    [10, 25, 50, 100, "All"]
                ],
                ajax: {
                    url: '{{ route('admin.codes.index') }}'
                }, // JSON file to add data
                columns: [
                    {data: 'id'},
                    {data: 'code'},
                    {data: 'user_id'},
                    {data: 'course', sortable: false},
                    {data: 'active_at', sortable: false},
                    {data: 'expired_at'},
                    {data: 'is_used'},
                    {data: 'actions', sortable: false},
                ],
                order: [
                    [0, 'asc']
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
                    sLengthMenu: '{{ translate('Show _MENU_') }}',
                    search: '{{ translate('Search') }}',
                    zeroRecords: '{{ translate('No matching records found') }}',
                    info: "{{ translate('Showing _PAGE_ to _PAGES_ of _TOTAL_ entries') }}",
                    infoEmpty: '{{ translate('Show 0 pages') }}',
                    searchPlaceholder: '{{translate('Search')}}..',
                    paginate: {
                        // remove previous & next text from pagination\
                        previous: '{{translate('Previous')}}',
                        next: '{{translate('Next')}}'
                    }
                },
                // Buttons with Dropdown
                buttons: [
                    {
                        extend: 'collection',
                        className: 'btn btn-label-secondary dropdown-toggle mx-3',
                        text: '<i class="ti ti-screen-share me-1 ti-xs"></i>{{translate('Export')}}',
                        buttons: [
                            {
                                text: '<i class="ti ti-file-text me-1" ></i>{{translate('Excel')}}',
                                className: 'dropdown-item',
                                attr: {
                                    "type": "button",
                                    "onclick": "window.location='{{route('admin.codes.codes_export')}}'"
                                },
                            },
                        ]
                    },


                ],
            });
        }


        $('#filter').click(function () {
            var is_used = $('#is_used').val();

            dtTickerTable.DataTable().destroy();
            dtTickerTable.DataTable({
                "processing": true,
                "serverSide": true,
                "lengthMenu": [
                    [10, 25, 50, 100, 1000000],
                    [10, 25, 50, 100, "All"]
                ],
                ajax: {
                    url: '{{ route('admin.codes.index') }}',
                    data: {
                        is_used: is_used,
                    }
                }, // JSON file to add data
                columns: [
                    {data: 'id'},
                    {data: 'code'},
                    {data: 'user_id'},
                    {data: 'is_used'},
                    {data: 'actions', sortable: false},
                ],
                order: [
                    [0, 'asc']
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
                        extend: 'collection',
                        className: 'btn btn-label-secondary dropdown-toggle mx-3',
                        text: '<i class="ti ti-screen-share me-1 ti-xs"></i>{{translate('تصدير')}}',
                        buttons: [
                            {
                                extend: 'print',
                                text: '<i class="ti ti-printer me-1" ></i>{{translate('طباعة')}}',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3]}
                            },
                            {
                                extend: 'csv',
                                text: '<i class="ti ti-file-text me-1" ></i>Csv',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3]}
                            },
                            {
                                extend: 'excel',
                                text: '<i class="ti ti-file-text me-1" ></i>{{translate('اكسل')}}',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3]}
                            },
                        ]
                    }
                ],
            });

        });


        function deleteForm(id) {
            var modal = $('#deleteModal');
            var action = `{{ route('admin.codes.destroy', '') }}/${id}`;
            modal.find('form').attr('action', action);
            modal.modal('show');
        }
    </script>


@stop
