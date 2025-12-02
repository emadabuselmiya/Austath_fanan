<div class="initial-32">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title product-title">{{$student->name}}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
            <div class="card m-0">
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">

                        <div class="button-wrapper">
                            <div class="text-muted">{{ $student->email }}</div>
                            <div class="text-muted">{{ $student->class?->name }}</div>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="row">
                        @foreach ($student->activeCourses as $item)
                            <div class="col-md-6 col-sm-12 mb-1">
                                <div class="btn btn-outline-primary form-control" onclick="edit_code({{$item->id}})">
                                    {{ $item->course->name }} | {{ $item->code->code }}
                                    <br>
                                    @if($item->status) <span class="badge bg-label-success font-regular">مفعل</span> @else <span class="badge bg-label-danger font-regular">غير فعال</span> @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

