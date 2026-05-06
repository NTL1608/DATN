<!DOCTYPE html>
<html lang="zxx">
    @include('page.common.head')
<body>
<!-- Page Preloder -->
<div id="preloder">
    <div class="loader"></div>
</div>
<!-- Header Section -->
    @include('page.common.header')
<!-- Header Section end -->
    @yield('content')
    
    <!-- Chat Widget -->
    @include('partials.chat')
    
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('page.common.footer')

</body>
</html>
