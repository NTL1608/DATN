<head>
    <title>@yield('title', 'Bệnh viện Đa khoa Phương Đông')</title>
    <meta charset="UTF-8">
    <meta name="description" content="@yield('description', 'Mô tả')">
    <meta name="keywords" content="@yield('keywords', 'Từ khóa')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="{{ asset('admin/dist/img/images (2).png') }}" id="favicon" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('page/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('page/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('page/css/owl.carousel.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('page/css/nice-select.css') }}"/>
    <link rel="stylesheet" href="{{ asset('page/css/magnific-popup.css') }}"/>
    <link rel="stylesheet" href="{{ asset('page/css/slicknav.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('page/css/animate.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">

    <!-- Main Stylesheets -->
    <link rel="stylesheet" href="{{ asset('page/css/style.css') }}"/>


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('style')
    <script>
        var pageLoadLocation = "{{ route('page.ajax.post.load.location') }}";
    </script>
</head>
