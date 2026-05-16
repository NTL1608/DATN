<header class="header-section">
    <div class="header-top">
        <div class="row m-0">
            <div class="col-md-6 d-none d-md-block p-0">
                <div class="header-info">
                    <i class="material-icons">map</i>
                    <p>{{ config('setting.address') }}</p>
                </div>
                <div class="header-info">
                    <i class="material-icons">phone</i>
                    <p>{{ config('setting.phone') }}</p>
                </div>
            </div>
            <div class="col-md-6 text-left text-md-right p-0">
                <div class="header-info d-none d-md-inline-flex">
                    <i class="material-icons">alarm_on</i>
                    <p>{{ config('setting.alarm_on_mon_fri') }}</p>
                </div>
                <div class="header-info">
                    <i class="fa fa-fw fa-users"></i>
                    <select id="language" class="language-select">
                        @if(Auth::guard('users')->check())
                            <option data-display="{{ Auth::guard('users')->user()->name }}" value="{{ route('info.account') }}">{{ Auth::guard('users')->user()->name }}</option>
                            <option data-display="Danh sách đặt lịch" value="{{ route('users.bookings') }}">Danh sách đặt lịch</option>
                            <option data-display="Đổi mật khẩu" value="{{ route('change.password') }}">Đổi mật khẩu</option>
                            <option data-display="Đăng xuất" value="{{ route('page.user.logout') }}">Đăng xuất</option>
                        @else
                            <option data-display="Tài khoản" value="{{ route('page.user.login') }}">Đăng nhập</option>
                            <option data-display="Đăng ký" value="{{ route('page.user.register') }}">Đăng ký</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <a href="{{ route('user.home.index') }}" class="site-logo">
            <img src="{{ asset('page/img/logo1.jpg') }}" alt="" style="height: 60px; width: auto;">
        </a>
        <div class="hb-right">
            <div class="hb-switch" id="search-switch">
                <img src="{{ asset('page/img/icons/search.png') }}" alt="">
            </div>
{{--            <div class="hb-switch" id="infor-switch">--}}
{{--                <img src="{{ asset('page/img/icons/bars.png') }}" alt="">--}}
{{--            </div>--}}
        </div>
        <div class="container">
            <ul class="main-menu">
                <li><a href="{{ route('user.home.index') }}" class="{{ request()->is('/')  ? 'active' : '' }}">Trang chủ</a></li>
                <li><a href="{{ route('page.about') }}" class="{{ request()->is('gioi-thieu.html')  ? 'active' : '' }}">Giới thiệu</a></li>
                <li><a href="{{ route('page.clinic.index') }}" class="{{ request()->is(['khoa-kham-benh.html', 'khoa-kham-benh/*'])  ? 'active' : '' }}">Khoa khám bệnh</a></li>
                <li><a href="{{ route('page.specialty.index') }}" class="{{ request()->is(['dich-vu.html', 'dich-vu/*'])  ? 'active' : '' }}">Dịch vụ</a></li>
                <li><a href="{{ route('page.article.index') }}" class="{{ request()->is(['tin-tuc.html', 'tin-tuc/*'])  ? 'active' : '' }}">Tin tức</a></li>
                <li><a href="{{ route('page.contact') }}" class="{{ request()->is('lien-he.html')  ? 'active' : '' }}">Liên hệ</a></li>
            </ul>
        </div>
    </div>
</header>

