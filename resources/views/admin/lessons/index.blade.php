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
                        <th>{{translate("عدد المواد")}}</th>
                        <th>{{translate("العمليات")}}</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Ajax Sourced Server-side -->

    </div>
    <!-- / Content -->

    <div id="FormModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="classForm" action="javascript:void(0);" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="class_id" id="class_id" value="-1">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ translate('الاسم') }}<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="{{ translate('الاسم') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" type="reset" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">{{ translate('اغلاق') }} </button>
                        <button type="submit" class="btn btn-success mr-1 data-submit">{{ translate('حفظ') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('حذف') }} {{ translate('الفصل') }}</h5>
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
                    url: '{{ route('admin.classes.index') }}'
                }, // JSON file to add data
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'name'},
                    {data: 'courses_count'},
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
                        text: '<i class="tf-icons ti ti-circle-plus"></i>',
                        className: 'btn btn-secondary btn-sm btn-primary mx-1',
                        attr: {
                            "type": "button",
                            "onclick": "createForm()"
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

        function editForm(id, name) {
            var modal = $('#FormModal');

            modal.find('.modal-title').text("{{translate('تعديل الفصل')}}");
            modal.find('[name=class_id]').val(id);
            modal.find('[name=name]').val(name);
            modal.modal('show');
        }

        function createForm() {
            var modal = $('#FormModal');

            modal.find('.modal-title').text("{{translate('اضافة الفصل')}}");
            $('#classForm')[0].reset();
            $('#class_id').val(-1);
            modal.modal('show');
        }

        function deleteForm(id) {
            var modal = $('#deleteModal');
            var action = `{{ route('admin.classes.destroy', '') }}/${id}`;
            modal.find('form').attr('action', action);
            modal.modal('show');
        }
    </script>

    <script>
        $('#classForm').on('submit', function (e) {
            $(":submit").prop('disabled', true)

            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.classes.store')}}',
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
                        toastr.success('{{ translate('تم اضافة الفصل بنجاح') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        $('#classForm')[0].reset();
                        $('#FormModal').modal('hide');
                        dtTickerTable.DataTable().ajax.reload();
                    }
                }, error: function () {
                    $(":submit").prop('disabled', false)
                }
            });
        });
    </script>
@stop
