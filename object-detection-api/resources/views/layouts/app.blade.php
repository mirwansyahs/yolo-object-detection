
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Aplikasi')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Object Detection" name="description" />
    <meta content="M Irwansyah S" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <!-- Bootstrap Css -->
    <link href="{{ asset('css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    @stack('styles') {{-- Untuk tambahan CSS --}}
</head>
<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">
        {{-- Header --}}
        @include('layouts.partials.header')

        {{-- Sidebar (opsional) --}}
        @include('layouts.partials.sidebar')

        {{-- Konten Utama --}}
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">@yield('title', 'Aplikasi')</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                        <li class="breadcrumb-item active">@yield('title', 'Aplikasi')</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    @yield('content')
                </div> <!-- container-fluid -->
            </div> <!-- End Page-content -->

        </div> <!-- end main content -->
    </div>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    <!-- JAVASCRIPT -->
    <script src="{{ asset('libs/jquery/jquery.min.js') }} "></script>
    <script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }} "></script>
    <script src="{{ asset('libs/metismenu/metisMenu.min.js') }} "></script>
    <script src="{{ asset('libs/simplebar/simplebar.min.js') }} "></script>
    <script src="{{ asset('libs/node-waves/waves.min.js') }} "></script>

    <!-- dashboard init -->
    <script src="{{ asset('js/pages/dashboard.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts') {{-- Untuk tambahan JS --}}
</body>
</html>
