@extends('page.layouts.page')
@section('title', 'Đổi mật khẩu')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Đổi mật khẩu';
        $description = 'Cập nhật mật khẩu của bạn';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))

    <section class="service-section spad">
        <div class="container">
            <div class="row">
                @include('page.common.menu_user')
                <div class="col-lg-9">
                    <h2 class="sb-title">Đổi mật khẩu</h2>
                    <form class="singup-form contact-form" action="{{ route('post.change.password') }}" method="POST">
                        @csrf
                        <div class="col-md-12">
                            <label class="text-label">Mật khẩu cũ <sup class="text-danger">(*)</sup></label>
                            <input type="password" name="current_password" placeholder="Nhập mật khẩu cũ">
                            @if ($errors->first('current_password'))
                                <span class="text-danger">{{ $errors->first('current_password') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <label class="text-label">Mật khẩu mới <sup class="text-danger">(*)</sup></label>
                            <input type="password" name="password" placeholder="Nhập mật khẩu mới">
                            @if ($errors->first('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <label class="text-label">Nhập lại mật khẩu mới <sup class="text-danger">(*)</sup></label>
                            <input type="password" name="r_password" placeholder="Nhập lại mật khẩu mới">
                            @if ($errors->first('r_password'))
                                <span class="text-danger">{{ $errors->first('r_password') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12 text-center" style="margin-top: 30px">
                            <button type="submit" class="site-btn sb-gradient">Đổi mật khẩu</button>
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