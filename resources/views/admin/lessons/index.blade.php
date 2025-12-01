@extends('layouts.admin.app')

@section('title')
    {{ translate('الدروس') }}
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

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">{{ translate('الدروس') }}</h4>

        <!-- Ajax Sourced Server-side -->
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="list-table table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('الاسم')}}</th>
                        <th>{{translate('الوصف')}}</th>
                        <th>{{translate("الموضوع")}}</th>
                        <th>{{translate("تاريخ الانشاء")}}</th>
                        <th>{{translate("العمليات")}}</th>
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
                    <h5 class="modal-title">{{ translate('حذف') }} {{ translate('الدرس') }}</h5>
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
                ajax: {
                    url: '{{ route('admin.lessons.index', ['subject'=>\Request::get('subject')]) }}'
                }, // JSON file to add data
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'description'},
                    {data: 'subject_id'},
                    {data: 'created_at'},
                    {data: 'actions', sortable: false},
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
                            "onclick": "window.location='{{ route('admin.lessons.create') }}'"
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    },
                ],
            });
        }
    </script>

    <script>
        function deleteForm(id) {
            var modal = $('#deleteModal');
            var action = `{{ route('admin.lessons.destroy', '') }}/${id}`;
            modal.find('form').attr('action', action);
            modal.modal('show');
        }
    </script>
@stop
