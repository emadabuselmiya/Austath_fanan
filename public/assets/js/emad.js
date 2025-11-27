$(function () {
    'use strict';

    var dtUsdtTable = $('.usdt-list-table'),
        dtAdminTable = $('.admin-list-table'),
        dtNewUsdtTable = $('.newusdt-list-table'),
        dtActiveUsdtTable = $('.activeusdt-list-table'),
        dtRejectedUsdtTable = $('.rejectedusdt-list-table'),
        dtSliderTable = $('.slider-list-table'),
        dtMessageTable = $('.message-list-table'),
        dtContactTable = $('.contact-list-table'),
        dtFAQsTable = $('.faq-list-table'),
        dtPlateformTable = $('.plateform-list-table'),
        dtbotsTable = $('.bots-list-table'),
        dtBlogTable = $('.blog-list-table'),


        status = {
            0: {title: 'غير مقروءه', class: 'badge-light-danger'},
            1: {title: 'مقروءه', class: 'badge-light-success'},
        },

        sliderStatus = {
            0: {title: 'سلايدر داخلي', class: 'badge-light-danger'},
            1: {title: 'سلايدر خارجي', class: 'badge-light-success'},
        },

        statusObj = {
            1: {title: 'مفعل', class: 'badge-light-success'},
            0: {title: 'غير مفعل', class: 'badge-light-danger'}
        };


    if (dtUsdtTable.length) {
        dtUsdtTable.DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: '/admin/users/usdt'
            }, // JSON file to add data
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'user_name'},
                {data: 'email'},
                {data: 'status'},
                {data: ''},
            ],
            columnDefs: [
                {
                    // Slider Status
                    targets: 4,
                    render: function (data, type, full, meta) {
                        var $status = full['status'];

                        return (
                            '<span class="badge badge-pill ' +
                            statusObj[$status].class +
                            '" text-capitalized>' +
                            statusObj[$status].title +
                            '</span>'
                        );
                    }
                },
                {
                    // Actions
                    targets: -1,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var id = full['id'];

                        return (
                            '<a href="javascript:void();" data-toggle= "modal"' +
                            'data-target= "#modals-status' + id + '" class="btn btn-outline-success mr-1 mt-1 btn-sm">' +
                            feather.icons['check'].toSvg({class: 'font-small-4 mr-50'}) +
                            'تغيير الحالة</a>' +
                            '<a href="javascript:void();" class="btn btn-success btn-sm mr-1 mt-1" data-toggle= "modal"' +
                            'data-target= "#modals-send-message' + id + '">' +
                            feather.icons['send'].toSvg({class: 'font-small-4 mr-50'}) +
                            'ارسال اشعار</a>' +
                            // '<a href="/admin/users/usdt/' + id + '/edit" class="btn btn-sm btn-secondary mr-1 mt-1">' +
                            // feather.icons['archive'].toSvg({class: 'font-small-4 mr-50'}) +
                            // 'تعديل</a>' +
                            '<a href="javascript:void();" class="btn btn-sm btn-danger mr-1 mt-1" data-toggle= "modal"' +
                            'data-target= "#modals-user-delete' + id + '">' +
                            feather.icons['trash-2'].toSvg({class: 'font-small-4 mr-50'}) +
                            'حذف</a>'

                        );
                    }
                }
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
                sLengthMenu: 'Show _MENU_',
                search: 'بحث',
                searchPlaceholder: 'بحث..',
                paginate: {
                    // remove previous & next text from pagination\
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle mr-2 mt-50',
                    text: feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + 'تصدير',
                    buttons: [
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({class: 'font-small-4 mr-50'}) + 'طباعة',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
                        },
                        {
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({class: 'font-small-4 mr-50'}) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'اكسل',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
                        },
                        {
                            extend: 'pdf',
                            text: feather.icons['clipboard'].toSvg({class: 'font-small-4 mr-50'}) + 'Pdf',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
                        }
                    ],
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    }
                },

                // {
                //     text: 'اضافة مستخدم جديد',
                //     className: 'add-new btn btn-success mt-50',
                //     attr: {
                //         "type": "button",
                //         "onclick": "location.href = '/admin/users/usdt/create'",
                //     },
                //     init: function (api, node, config) {
                //         $(node).removeClass('btn-secondary');
                //     }
                // }
            ],
        });
    }


    if (dtActiveUsdtTable.length) {
        dtActiveUsdtTable.DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: '/admin/users/usdt-active'
            }, // JSON file to add data
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'phone'},
                {data: 'amount'},
                {data: 'current_price'},
                {data: 'presagan'},
                {data: 'price_share'},
                {data: 'status'},
                {data: ''},
            ],
            columnDefs: [
                {
                    // Slider Status
                    targets: 8,
                    render: function (data, type, full, meta) {
                        var $status = full['status'];

                        return (
                            '<span class="badge badge-pill ' +
                            statusObj[$status].class +
                            '" text-capitalized>' +
                            statusObj[$status].title +
                            '</span>'
                        );
                    }
                },
                {
                    // Actions
                    targets: -1,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var id = full['id'];

                        return (
                            '<a href="javascript:void();" data-toggle= "modal"' +
                            'data-target= "#modals-status' + id + '" class="btn btn-outline-success mr-1 mt-1 btn-sm">' +
                            feather.icons['check'].toSvg({class: 'font-small-4 mr-50'}) +
                            'تغيير الحالة</a>' +
                            '<a href="javascript:void();" class="btn btn-success btn-sm mr-1 mt-1" data-toggle= "modal"' +
                            'data-target= "#modals-send-message' + id + '">' +
                            feather.icons['send'].toSvg({class: 'font-small-4 mr-50'}) +
                            'ارسال اشعار</a>' +
                            '<a href="/admin/users/usdt-active/' + id + '/edit" class="btn btn-sm btn-secondary mr-1 mt-1">' +
                            feather.icons['archive'].toSvg({class: 'font-small-4 mr-50'}) +
                            'تعديل</a>' +
                            '<a href="javascript:void();" class="btn btn-sm btn-danger mr-1 mt-1" data-toggle= "modal"' +
                            'data-target= "#modals-user-delete' + id + '">' +
                            feather.icons['trash-2'].toSvg({class: 'font-small-4 mr-50'}) +
                            'حذف</a>'

                        );
                    }
                }
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
                sLengthMenu: 'Show _MENU_',
                search: 'بحث',
                searchPlaceholder: 'بحث..',
                paginate: {
                    // remove previous & next text from pagination\
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle mr-2 mt-50',
                    text: feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + 'تصدير',
                    buttons: [
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({class: 'font-small-4 mr-50'}) + 'طباعة',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
                        },
                        {
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({class: 'font-small-4 mr-50'}) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'اكسل',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
                        },
                        {
                            extend: 'pdf',
                            text: feather.icons['clipboard'].toSvg({class: 'font-small-4 mr-50'}) + 'Pdf',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
                        }
                    ],
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    }
                },

                {
                    text: 'اضافة مستخدم جديد',
                    className: 'add-new btn btn-success mt-50',
                    attr: {
                        "type": "button",
                        "onclick": "location.href = '/admin/users/usdt-active/create'",
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }
            ],
        });
    }


    if (dtAdminTable.length) {
        dtAdminTable.DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: '/admin/users'
            }, // JSON file to add data
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'phone'},
                {data: 'actions'},
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
                sLengthMenu: 'Show _MENU_',
                search: 'بحث',
                searchPlaceholder: 'بحث..',
                paginate: {
                    // remove previous & next text from pagination\
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle mr-2 mt-50',
                    text: feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + 'تصدير',
                    buttons: [
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({class: 'font-small-4 mr-50'}) + 'طباعة',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3]}
                        },
                        {
                            extend: 'csv',
                            text: feather.icons['file-text'].toSvg({class: 'font-small-4 mr-50'}) + 'Csv',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3]}
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'اكسل',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3]}
                        },
                        {
                            extend: 'pdf',
                            text: feather.icons['clipboard'].toSvg({class: 'font-small-4 mr-50'}) + 'Pdf',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2, 3]}
                        }
                    ],
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    }
                },

                {
                    text: 'اضافة مستخدم جديد',
                    className: 'add-new btn btn-success mt-50',
                    attr: {
                        "type": "button",
                        "onclick": "location.href = '/admin/users/create'",
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }
            ],
        });
    }

    if (dtMessageTable.length) {
        dtMessageTable.DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: '/admin/messages'
            }, // JSON file to add data
            columns: [
                {data: 'id'},
                {data: 'title'},
                {data: 'body'},
                {data: 'status'},
                {data: 'updated_at'},
                {data: ''},
            ],
            columnDefs: [
                {
                    targets: 2,
                    width: 300
                },
                {
                    // Slider Status
                    targets: 3,
                    render: function (data, type, full, meta) {
                        var $status = full['status'];

                        return (
                            '<span class="badge badge-pill ' +
                            statusObj[$status].class +
                            '" text-capitalized>' +
                            statusObj[$status].title +
                            '</span>'
                        );
                    }
                },
                {
                    // Actions
                    targets: 4,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var updated_at = full['updated_at'];

                        return new Date(updated_at).toDateString();
                    }
                },
                {
                    // Actions
                    targets: -1,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var id = full['id'];

                        return (
                            '<a href="javascript:void();" data-toggle= "modal"' +
                            'data-target= "#modals-send-message' + id + '" class="btn btn-sm btn-success mr-1">' +
                            feather.icons['send'].toSvg({class: 'font-small-4 mr-50'}) +
                            'ارسال الاشعار</a>' +
                            '<a href="#" data-toggle= "modal"' +
                            'data-target= "#modals-edit' + id + '" class="btn btn-sm btn-secondary mr-1">' +
                            feather.icons['archive'].toSvg({class: 'font-small-4 mr-50'}) +
                            'تعديل</a>' +
                            '<a href="#" class="btn btn-sm btn-danger mr-1" data-toggle= "modal"' +
                            'data-target= "#modals-delete' + id + '">' +
                            feather.icons['trash-2'].toSvg({class: 'font-small-4 mr-50'}) +
                            'حذف</a>'

                        );
                    }
                }
            ],
            order: [
                [0, 'ASC']
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
                sLengthMenu: 'Show _MENU_',
                search: 'بحث',
                searchPlaceholder: 'بحث..',
                paginate: {
                    // remove previous & next text from pagination\
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [
                {
                    text: 'اضافة رسالة جديد',
                    className: 'add-new btn btn-success mt-50',
                    attr: {
                        'data-toggle': "modal",
                        'data-target': "#modals-create"
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }
            ],
        });
    }

    if (dtSliderTable.length) {
        dtSliderTable.DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: '/admin/sliders'
            }, // JSON file to add data
            columns: [
                {data: 'id'},
                {data: 'title'},
                {data: 'status'},
                {data: 'updated_at'},
                {data: ''},
            ],
            columnDefs: [

                {
                    // Actions
                    targets: -1,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var $id = full['id'];
                        return (
                            '<a href="javascript:void();"   data-toggle= "modal"' +
                            'data-target= "#modals-slider-view' + $id + '" class="btn btn-sm btn-success mr-1" > ' +
                            feather.icons['file-text'].toSvg({class: 'font-small-4 mr-50'}) +
                            'تفاصيل</a>' +

                            '<a href="/admin/sliders/' +
                            $id +
                            '/edit" class="btn btn-sm btn-secondary mr-1">' +
                            feather.icons['archive'].toSvg({class: 'font-small-4 mr-50'}) +
                            'تعديل</a>' +

                            '<a href="javascript:void();" class="btn btn-sm btn-danger mr-1" data-toggle= "modal"' +
                            'data-target= "#modals-slider-delete' + $id + '">' +
                            feather.icons['trash-2'].toSvg({class: 'font-small-4 mr-50'}) +
                            'حذف</a>'
                        );
                    }
                },
                {
                    // Slider Status
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var $status = full['status'];

                        return (
                            '<span class="badge badge-pill ' +
                            sliderStatus[$status].class +
                            '" text-capitalized>' +
                            sliderStatus[$status].title +
                            '</span>'
                        );
                    }
                },

                {
                    // updated at
                    targets: 3,
                    render: function (data, type, full, meta) {
                        var $status = full['updated_at'];

                        return (
                            new Date($status).toLocaleString());
                    }
                },
            ],
            order: [
                [0, 'ASC']
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
                sLengthMenu: 'Show _MENU_',
                search: 'بحث',
                searchPlaceholder: 'بحث..',
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [{
                text: 'اضافة صورة متحركة',
                className: 'add-new btn btn-success mt-50',
                attr: {
                    "type": "button",
                    "onclick": "location.href = '/admin/sliders/create'",
                },
                init: function (api, node, config) {
                    $(node).removeClass('btn-secondary');
                }
            }],
            initComplete: function () {
                // Adding role filter once table initialized
                this.api()
                    .columns(2)
                    .every(function () {
                        var column = this;
                        var select = $(
                            '<select id="UserRole" class="form-control text-capitalize mb-md-0 mb-2"><option value=""> اختيار الحالة </option></select>'
                        )
                            .appendTo('.user_role')
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column
                            .each(function (d, j) {
                                select.append('<option value="1" class="text-capitalize">مفعل</option>');
                                select.append('<option value="0" class="text-capitalize">غير مفعل</option>');

                            });
                    });
            }
        });
    }

    if (dtContactTable.length) {
        dtContactTable.DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: '/admin/contacts'
            }, // JSON file to add data
            columns: [
                // columns according to JSON
                {data: 'name'},
                {data: 'email'},
                {data: 'subject'},
                {data: 'date'},
                {data: 'status'},
                {data: 'actions', orderable: false}
            ],
            columnDefs: [
                {
                    // Slider Status
                    targets: 4,
                    render: function (data, type, full, meta) {
                        var $status = full['status'];

                        return (
                            '<span class="badge badge-pill ' +
                            status[$status].class +
                            '" text-capitalized>' +
                            status[$status].title +
                            '</span>'
                        );
                    }
                },
            ],
            order: [3, 'DESC'],
            dom:
                '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                '<"col-lg-12 col-xl-6" l>' +
                '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                '>t' +
                '<"d-flex justify-content-between mx-2 row mb-1"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: 'بحث',
                searchPlaceholder: 'بحث..',
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle mr-2 mt-50',
                    text: feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + 'تصدير',
                    buttons: [
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({class: 'font-small-4 mr-50'}) + 'طباعة',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2]}
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'اكسل',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2]}
                        },
                        {
                            extend: 'pdf',
                            text: feather.icons['clipboard'].toSvg({class: 'font-small-4 mr-50'}) + 'Pdf',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2]}
                        }
                    ],
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    },
                }
            ],

        });
    }

    if (dtFAQsTable.length) {
        dtFAQsTable.DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: urlMain
            },
            columns: [
                // columns according to JSON
                {data: 'DT_RowIndex'},
                {data: 'question'},
                {data: 'actions'}
            ],

            order: [[0, 'desc']],
            dom:
                '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                '<"col-lg-12 col-xl-6" l>' +
                '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                '>t' +
                '<"d-flex justify-content-between mx-2 row mb-1"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: 'بحث',
                searchPlaceholder: 'بحث..',
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle mr-2 mt-50',
                    text: feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + 'تصدير',
                    buttons: [
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({class: 'font-small-4 mr-50'}) + 'طباعة',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1]}
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'اكسل',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1]}
                        }
                    ],
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    },
                },
                {
                    text: 'Add new +',
                    className: 'add-new btn btn-primary mt-50',
                    attr: {
                        'data-toggle': 'modal',
                        'data-target': '#modals-create-faqs'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }
            ]
        });
    }

    if (dtPlateformTable.length) {
        dtPlateformTable.DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: urlMain
            },
            columns: [
                // columns according to JSON
                {data: 'DT_RowIndex'},
                {data: 'image'},
                {data: 'title'},
                {data: 'actions'}
            ],

            order: [[0, 'desc']],
            dom:
                '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                '<"col-lg-12 col-xl-6" l>' +
                '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                '>t' +
                '<"d-flex justify-content-between mx-2 row mb-1"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: 'بحث',
                searchPlaceholder: 'بحث..',
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle mr-2 mt-50',
                    text: feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + 'تصدير',
                    buttons: [
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({class: 'font-small-4 mr-50'}) + 'طباعة',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 2]}
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'اكسل',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 2]}
                        }
                    ],
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    },
                },
                {
                    text: 'Add new +',
                    className: 'add-new btn btn-primary mt-50',
                    attr: {
                        'data-toggle': 'modal',
                        'data-target': '#modals-create-plateform'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }
            ]
        });
    }

    if (dtbotsTable.length) {
        dtbotsTable.DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: urlMain
            },
            columns: [
                // columns according to JSON
                {data: 'DT_RowIndex'},
                {data: 'image'},
                {data: 'name'},
                {data: 'actions'}
            ],

            order: [[0, 'desc']],
            dom:
                '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                '<"col-lg-12 col-xl-6" l>' +
                '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                '>t' +
                '<"d-flex justify-content-between mx-2 row mb-1"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: 'بحث',
                searchPlaceholder: 'بحث..',
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [

                {
                    text: 'Add new +',
                    className: 'add-new btn btn-primary mt-50',
                    attr: {
                        'data-toggle': 'modal',
                        'data-target': '#modals-create-plateform'
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }
            ]
        });
    }

    if (dtBlogTable.length) {
        dtBlogTable.DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: mainUrl
            }, // JSON file to add data
            columns: [
                // columns according to JSON
                {data: 'main_image', orderable: false},
                {data: 'title'},
                {data: 'sub_title'},
                {data: 'actions', orderable: false}
            ],

            order: [0, 'asc'],
            dom:
                '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                '<"col-lg-12 col-xl-6" l>' +
                '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                '>t' +
                '<"d-flex justify-content-between mx-2 row mb-1"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: 'بحث',
                searchPlaceholder: 'بحث..',
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle mr-2 mt-50',
                    text: feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + 'تصدير',
                    buttons: [
                        {
                            extend: 'print',
                            text: feather.icons['printer'].toSvg({class: 'font-small-4 mr-50'}) + 'طباعة',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2]}
                        },
                        {
                            extend: 'excel',
                            text: feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'اكسل',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2]}
                        },
                        {
                            extend: 'pdf',
                            text: feather.icons['clipboard'].toSvg({class: 'font-small-4 mr-50'}) + 'Pdf',
                            className: 'dropdown-item',
                            exportOptions: {columns: [0, 1, 2]}
                        }
                    ],
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                        $(node).parent().removeClass('btn-group');
                        setTimeout(function () {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    },
                },
                {
                    text: 'Create',
                    className: 'add-new btn btn-primary mt-50',
                    onclick: "",
                    attr: {
                        "type": "button",
                        "onclick": "location.href = '"+createUrl+"'",
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }

                },
            ],

        });
    }


});
