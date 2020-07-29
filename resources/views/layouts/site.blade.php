<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="noindex, nofollow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/favicon/site.webmanifest">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


    <link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/assets/css/dropdown.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/css/font-awesome.min.css') }}">

    <!-- Line-awesome CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/css/line-awesome.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}">
    <!-- Styles -->
    @stack('styles')
    <link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/assets/css/custom.css') }}">
</head>
<body class="lang-">

<!-- Main Wrapper -->
<div id="app" class="main-wrapper">
@include('common.header')

@include('common.sidebar')

<!-- Page Wrapper -->
    <div class="page-wrapper">

        <!-- Page Content -->
        <div class="content container-fluid">

            @yield('content')

        </div>

        <!-- /Page Content -->

        @stack('modals')

    </div>
    <!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>


@stack('scripts')
<script>
    @if ($errors->any())
    $(function () {
        @foreach ($errors->all() as $error)
        toastr.error('{{  $error }}');
        @endforeach
    });
    @endif

    @if (isset($messages) && $messages->count())
    $(function () {
        @foreach ($messages as $message)
        toastr.{{ $message['class'] }}('{{  $message['message'] }}');
        @endforeach
    });
    @endif
</script>

<!-- Slim scroll JS -->
<script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/js/app.js') }}"></script>

</body>
</html>
