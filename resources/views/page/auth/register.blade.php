@extends('page.layouts.page')
@section('title', 'Đăng ký')
@section('style')
@stop
@section('content')
@php
$link_img = '';
$title = 'Đăng ký';
$description = 'Đăng ký tài khoản để nhận tư vấn, hưởng nhiều ưu đãi hơn và dễ dàng theo dõi tiến độ lịch khám cùng kết quả khám chữa bệnh';
@endphp
@include('page.common.top_section', compact('title', 'description'))

<section class="service-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if (session('danger'))
                <p class="login-box-msg text-danger">{{ session('danger') }}</p>
                @endif
                <form class="singup-form contact-form" action="{{ route('account.register') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-label">Họ và tên <sup class="text-danger">(*)</sup></label>
                            <input type="text" name="name" placeholder="Họ và tên" value="{{ old('name') }}">
                            @if ($errors->first('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="text-label">Email đăng nhập <sup class="text-danger">(*)</sup></label>
                            <input type="email" name="email" placeholder="Email đăng nhập" value="{{ old('email') }}">
                            @if ($errors->first('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="text-label">Mật khẩu đăng nhập <sup class="text-danger">(*)</sup></label>
                            <input type="password" name="password" placeholder="Mật khẩu đăng nhập">
                            @if ($errors->first('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Nhập lại mật khẩu đăng nhập <sup class="text-danger">(*)</sup></label>
                            <input type="password" name="password_confirm" placeholder="Mật khẩu đăng nhập">
                            @if ($errors->first('password_confirm'))
                            <span class="text-danger">{{ $errors->first('password_confirm') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Ngày sinh <sup class="text-danger">(*)</sup></label>
                            <input type="date" name="birthday" value="{{ old('birthday') }}">
                            @if ($errors->first('birthday'))
                            <span class="text-danger">{{ $errors->first('birthday') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Giới tính <sup class="text-danger">(*)</sup></label>
                            <select name="gender" class="circle-select" id="" style="width: 100% !important;">
                                @foreach($genders as $key => $gender)
                                <option {{ old('gender') == $key ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $gender }}</option>
                                @endforeach
                            </select>
                            @if ($errors->first('gender'))
                            <span class="text-danger">{{ $errors->first('gender') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Số điện thoại <sup class="text-danger">(*)</sup></label>
                            <input type="text" name="phone" placeholder="Số điện thoại" value="{{ old('phone') }}">
                            @if ($errors->first('phone'))
                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Địa chỉ </label>
                            <input type="text" name="address" placeholder="Địa chỉ" value="{{ old('address') }}">
                            @if ($errors->first('address'))
                            <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Căn cước công dân <sup class="text-danger">(*)</sup></label>
                            <input type="text" name="citizen_id_number" value="{{ old('citizen_id_number') }}" placeholder="Căn cước công dân">
                            @if ($errors->first('citizen_id_number'))
                            <span class="text-danger">{{ $errors->first('citizen_id_number') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Số thẻ bảo hiểm </label>
                            <input type="text" name="insurance_card_number" value="{{ old('insurance_card_number') }}" placeholder="Số thẻ bảo hiểm">
                            @if ($errors->first('insurance_card_number'))
                            <span class="text-danger">{{ $errors->first('insurance_card_number') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="site-btn sb-gradient">Đăng ký</button>
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