<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
       data-bs-auto-close="outside"
       id="make-read" onclick="read()" aria-expanded="false">
        <i class="ti ti-bell ti-md"></i>
        <span class="badge bg-danger rounded-pill badge-notifications" id="unread">{{ $unread }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end py-0">
        <li class="dropdown-menu-header border-bottom">
            <div class="dropdown-header d-flex align-items-center py-3">
                <h5 class="text-body mb-0 me-auto">{{translate('الاشعارات')}}</h5>
                <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip"
                   id="make-read" onclick="read()"
                   data-bs-placement="top" title="Mark all as read">
                    <i class="ti ti-mail-opened fs-4"></i>
                </a>
            </div>
        </li>
        <li class="dropdown-notifications-list scrollable-container">
            <ul class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <li class="list-group-item list-group-item-action dropdown-notifications-item">
                        <a class="d-flex" href="{{ $notification->data['url'] ?? 'javascript:void(0);' }}"
                           id="read-message">

                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar">
                                        <img src="/dashboard/img/avatars/1.png" alt class="h-auto rounded-circle"/>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $notification->data['title'] }}</h6>
                                    <p class="mb-0">{{ $notification->data['body'] }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                    @if($notification->unread())
                                        <a href="javascript:void(0)" class="dropdown-notifications-read">
                                            <span class="badge badge-dot"></span>
                                        </a>
                                    @endif

                                    <a href="javascript:void(0)" class="dropdown-notifications-archive">
                                        <span class="ti ti-x"></span>
                                    </a>
                                </div>
                            </div>
                        </a>

                    </li>

                @empty
                    <li class="list-group-item list-group-item-action dropdown-notifications-item">
                        <div class="media d-flex align-items-start">
                            <div class="media-body">
                                <small class="notification-text">No found Notifications</small>
                            </div>
                        </div>
                    </li>

                @endforelse

            </ul>
        </li>

    </ul>
</li>


<script>
    var read = function (event) {
        // alert(44);
        $.ajax({
            type: "get",
            url: '{{ route('admin.read-messages') }}',
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                document.getElementById("unread").innerHTML = 0;

            },
        });
    }


</script>

