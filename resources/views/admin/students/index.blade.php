@extends('layouts.admin.app')

@section('title')
    {{ translate('الطلاب') }}
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
        <h4 class="py-3 mb-2">{{ translate('الطلاب') }}</h4>

        <div class="app-ecommerce-category">
            <!-- Category List Table -->
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="list-table table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('الاسم')}}</th>
                            <th>{{translate("البريد الالكتروني")}}</th>
                            <th>{{translate("الفصل")}}</th>
                            <th>{{translate("عدد الكورسات المسجلة")}}</th>
                            <th>{{translate("العمليات")}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/ Content -->

    <div class="modal fade" id="quick-view" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" id="quick-view-modal">

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
                ajax: {
                    url: '{{ route('admin.students.index') }}'
                }, // JSON file to add data
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'class_id'},
                    {data: 'activeCourses', sortable: false},
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
                                extend: 'print',
                                text: '<i class="ti ti-printer me-1" ></i>{{translate('Print')}}',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3, 4]}
                            },
                            {
                                extend: 'csv',
                                text: '<i class="ti ti-file-text me-1" ></i>Csv',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3, 4]}
                            },
                            {
                                extend: 'excel',
                                text: '<i class="ti ti-file-text me-1" ></i>{{translate('Excel')}}',
                                className: 'dropdown-item',
                                exportOptions: {columns: [0, 1, 2, 3, 4]}
                            },
                        ]
                    },


                ],
            });
        }

    </script>

    <script>
        function quick_view(student_id) {
            $.get({
                url: '{{ route('admin.students.show', '') }}/' + student_id,
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function edit_code(active_id) {
            $.get({
                url: '{{ route('admin.students.editCode', '') }}/' + active_id,
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function updateClass(user_id, class_id) {
            $.get({
                url: '{{ route('admin.students.toggle-settings', ['', '', '']) }}/' + user_id + '/' + class_id + '/class_id',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                },
                success: function (data) {
                }
            });
        }

    </script>
@stop
