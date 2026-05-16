@extends('page.layouts.page')
@section('title', 'Đăng nhập')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Đăng nhập';
        $description = 'Đăng nhập để dễ dàng theo dõi tiến độ lịch khám và kết quả khám chữa bệnh';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))

    <section class="service-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if (session('danger'))
                        <p class="login-box-msg text-danger">{{ session('danger') }}</p>
                    @endif
                    <form class="singup-form contact-form" action="{{ route('account.login') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-label">Email/Số điện thoại/CMND đăng nhập</label>
                                <input type="text" name="email" placeholder="Email/Số điện thoại/CMND đăng nhập">
                                @if ($errors->first('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="text-label">Mật khẩu đăng nhập</label>
                                <input type="password" name="password" placeholder="Mật khẩu đăng nhập">
                                @if ($errors->first('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="site-btn sb-gradient">Đăng nhập</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Service Section end -->
@stop
@section('script')
@stop
