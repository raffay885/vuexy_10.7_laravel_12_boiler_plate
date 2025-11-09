<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-skin="default" data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template" data-bs-theme="light">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title')</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/notyf/notyf.css') }}" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('admin.layouts.sidebar')
            <div class="layout-page">
                @include('admin.layouts.header')
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <footer class="content-footer footer bg-footer-theme">
                        @include('admin.layouts.footer')
                    </footer>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/notiflix/notiflix.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/notyf/notyf.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('js/generic-datatable.js') }}"></script>
    <script src="{{ asset('js/generic-form.js') }}"></script>
    <script src="{{ asset('js/notify.js') }}"></script>
    <script>
        function blockUI() {
            Loading.standard({
                backgroundColor: 'rgba(' + window.Helpers.getCssVar('black-rgb') + ', 0.5)',
                svgSize: '0px'
            });
            let customSpinnerHTML = `
              <div class="d-flex">
                  <p class="mb-0 text-white">Please wait...</p>
                  <div class="sk-wave m-0">
                      <div class="sk-rect sk-wave-rect"></div>
                      <div class="sk-rect sk-wave-rect"></div>
                      <div class="sk-rect sk-wave-rect"></div>
                      <div class="sk-rect sk-wave-rect"></div>
                      <div class="sk-rect sk-wave-rect"></div>
                  </div>
              </div>
            `;
            let notiflixBlock = document.querySelector('.notiflix-loading');
            notiflixBlock.innerHTML = customSpinnerHTML;
        }

        function unblockUI() {
          Loading.remove();
        }
    </script>
    @yield('page-js')
</body>

</html>
