@if (Session::has('success') || $errors->any() || Session::has('error') || Session::has('import_errors'))
    @if (Session::has('success'))
        <script>
            document.addEventListener("DOMContentLoaded",function(){
                Swal.fire({
                    type: 'success',
                    icon: "success",
                    title: '{{ Session::get('success') }}',
                    showConfirmButton: false,
                    timer: 1500,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                })

            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded",function(){
                Swal.fire({
                    type: 'error',
                    icon: "error",
                    title: "{{ translate('Error') }}",
                    html: '<div class="alert alert-danger p-2"> <ul style="margin-top: 1rem !important; text-align: right; font-size: 15px;"> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul> </div>',
                    showConfirmButton: false,
                    timer: 3000,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                })
            })
        </script>
    @endif

    @if (Session::has('import_errors'))
        <script>
            document.addEventListener("DOMContentLoaded",function(){
                Swal.fire({
                    type: 'error',
                    icon: "error",
                    title: "{{ translate('Error') }}",
                    html: '<div class="alert alert-danger p-2"> <ul style="margin-top: 1rem !important; text-align: right; font-size: 15px;"> @foreach (Session::get('import_errors') as $error) <li>{{ $error }}</li> </br> @endforeach </ul> </div>',
                    showConfirmButton: false,
                    timer: 3000,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                })
            })
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            document.addEventListener("DOMContentLoaded",function(){
                Swal.fire({
                    type: 'error',
                    icon: "error",
                    title: '{{ translate('Error') }}',
                    html: '<span style="color: red;">{{ Session::get('error') }}</span>',
                    showConfirmButton: false,
                    timer: 3000,
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                })
            })
        </script>
    @endif

@endif
