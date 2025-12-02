<div class="initial-32">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title product-title">{{$active->student?->name}}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
            <div class="card m-0">
                <!-- Account -->
                <form method="POST" id="activeForm" action="javascript:void(0);" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="active_id" id="active_id" value="{{ $active->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('المادة') }}<span class="text-danger">*</span></label>
                            <select name="course_id" id="course_id" class="select2 form-control">
                                @php($courses = \App\Models\Course::all())
                                @foreach($courses as $course)
                                    <option
                                        value="{{ $course->id }}" {{ $course->id == $active->course_id ? 'selected' : '' }}>{{ $course->name }}</option>

                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" type="reset" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">{{ translate('اغلاق') }} </button>
                        <button type="submit" class="btn btn-success mr-1 data-submit">{{ translate('حفظ') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    $('#activeForm').on('submit', function (e) {
        $(":submit").prop('disabled', true)

        e.preventDefault();
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: '{{route('admin.students.updateCode')}}',
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
                    toastr.success('{{ translate('تم تعديل المادة بنجاح') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#activeForm')[0].reset();
                    $('#quick-view').modal('hide');
                    dtTickerTable.DataTable().ajax.reload();
                }
            }, error: function () {
                $(":submit").prop('disabled', false)
            }
        });
    });
</script>


