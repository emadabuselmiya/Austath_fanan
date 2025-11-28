@extends('layouts.admin.app')

@section('title')
    {{ translate('Users') }}
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
        <h4 class="py-3 mb-2">{{ translate('Users List') }}</h4>

        <div class="app-ecommerce-category">
            <!-- Category List Table -->
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="list-table table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('Full name')}}</th>
                            <th>{{translate("E-mail")}}</th>
                            <th>{{translate("Phone")}}</th>
                            <th>{{translate("Status")}}</th>
                            <th>{{translate("Actions")}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--/ Content -->

    <div id="FormModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="userForm" action="javascript:void(0);" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id" value="-1">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ translate('Full name') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="{{ old('name') }}"
                                           placeholder="{{ translate('Full name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="email">{{ translate('E-mail') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control"
                                           value="{{ old('email') }}"
                                           placeholder="{{ translate('E-mail') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="phone">{{ translate('Phone') }}</label>
                                    <input type="tel" name="phone" id="phone" class="form-control"
                                           value="{{ old('Phone') }}"
                                           placeholder="{{ translate('Phone') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="job_title">{{ translate('Job Title') }}</label>
                                    <input type="text" name="job_title" id="job_title" class="form-control"
                                           value="{{ old('job_title') }}"
                                           placeholder="{{ translate('Job Title') }}">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="password">{{ translate('Password') }}</label>
                                    <input type="text" name="password" id="password" class="form-control"
                                           value="{{ old('password') }}"
                                           placeholder="{{ translate('Password') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="password_confirmation">{{ translate('Confirm Password') }}</label>
                                    <input type="text" name="password_confirmation" id="password_confirmation"
                                           class="form-control" value="{{ old('password_confirmation') }}"
                                           placeholder="{{ translate('password_confirmation') }}">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" type="reset" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">{{ translate('Close') }} </button>
                        <button type="submit" class="btn btn-success mr-1 data-submit">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Delete') }} {{ translate('User') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        {{ translate('Are sure of the deleting process?') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" type="reset" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">{{ translate('Close') }}</button>
                        <button type="submit" class="btn btn-danger mr-1 data-submit">{{ translate('Delete') }}</button>
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
                    url: '{{ route('admin.users.index') }}'
                }, // JSON file to add data
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'phone'},
                    {data: 'status'},
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

                    {
                        text: '<i class="tf-icons ti ti-circle-plus"></i>',
                        className: 'btn btn-sm btn-primary mx-1',
                        attr: {
                            "type": "button",
                            "onclick": "createForm()"
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }

                ],
            });
        }

    </script>

    <script>
        function deleteForm(id) {
            var modal = $('#deleteModal');
            var action = `{{ route('admin.users.destroy', '') }}/${id}`;
            modal.find('form').attr('action', action);
            modal.modal('show');
        }


        function editForm(id, name, email, phone, job_title, role_id) {
            var modal = $('#FormModal');

            modal.find('.modal-title').text("{{translate('Edit User')}}");
            modal.find('[name=user_id]').val(id);
            modal.find('[name=name]').val(name);
            modal.find('[name=email]').val(email);
            modal.find('[name=phone]').val(phone);
            modal.find('[name=job_title]').val(job_title);
            modal.find('[name=role_id]').val(role_id).change();

            modal.modal('show');
        }

        function createForm() {
            var modal = $('#FormModal');

            modal.find('.modal-title').text("{{translate('Add User')}}");
            $('#userForm')[0].reset();
            $('#user_id').val(-1);
            modal.modal('show');
        }

        $('#userForm').on('submit', function (e) {
            $(":submit").prop('disabled', true)

            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.users.store')}}',
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
                        toastr.success('{{ translate('The User was added/updated successfully') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        $('#userForm')[0].reset();
                        $('#FormModal').modal('hide');
                        dtTickerTable.DataTable().ajax.reload();
                    }
                }, error: function (data) {
                    $(":submit").prop('disabled', false)

                }
            });
        });

    </script>
@stop
