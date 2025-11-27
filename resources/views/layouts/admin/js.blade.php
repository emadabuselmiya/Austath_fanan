<!-- build:js assets/admin/js/core.js -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<script src="https://code.iconify.design/2/2.2.0/iconify.min.js"></script>

<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- Sweet alert init js-->
<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/js/forms-selects.js') }}"></script>

<script>

    @if(session('preloader', 0) == 0)
    document.querySelector(".preloader").style.display = "block";
    $(window).on("load", function () {
        setTimeout(function () {
            document.querySelector(".preloader").classList.add("opcity-0");
        }, 5000)
        setTimeout(function () {
            document.querySelector(".preloader").style.display = "none";
        }, 7000)

        localStorage.setItem("preloader", 1);

    });
    @php(session()->put('preloader', 1))
    @endif

</script>

@yield('js', ' ')

<audio id="myAudioNewOrder">
    <source src="{{ asset('assets/audio/notification.mp3') }}" type="audio/mpeg">
</audio>

<script>
    var audio = document.getElementById("myAudioNewOrder");

    function playAudioOrder() {
        audio.play();
    }

    function pauseAudioOrder() {
        audio.pause();
    }

</script>

<script>

    function route_alert(route, message, title = "{{translate('Are you sure about this process?')}}", status = null) {

        if (status == 'confirmed') {
            confirmed_order();
        } else {
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                customClass: {
                    confirmButton: 'btn btn-success me-1',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false,
                cancelButtonText: '{{translate('No')}}',
                confirmButtonText: '{{translate('Yes')}}',
            }).then((result) => {
                if (result.value) {
                    location.href = route;
                }
            })
        }

    }

</script>
<!-- BEGIN: Vendor JS-->
