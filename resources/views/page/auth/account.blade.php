@extends('page.layouts.page')
@section('title', 'Thông tin tài khoản')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Thông tin tài khoản';
        $description = '';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))

    <section class="service-section spad">
        <div class="container">
            <div class="row">
                @include('page.common.menu_user')
                <div class="col-lg-9">
                    <h2 class="sb-title">Thông tin tài khoản</h2>
                    @if (session('danger'))
                        <p class="login-box-msg text-danger">{{ session('danger') }}</p>
                    @endif
                    <form class="singup-form contact-form" action="{{ route('update.info.account', $user->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-label">Mã tài khoản</label>
                                <input type="text" value="{{ old('user_code', isset($user) ? $user->user_code : '') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="text-label">Họ và tên <sup class="text-danger">(*)</sup></label>
                                <input type="text" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" placeholder="Họ và tên">
                                @if ($errors->first('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="text-label">Email đăng nhập <sup class="text-danger">(*)</sup></label>
                                <input type="email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" placeholder="Email đăng nhập">
                                @if ($errors->first('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="text-label">Ngày sinh <sup class="text-danger">(*)</sup></label>
                                <input type="date" name="birthday" value="{{ old('birthday', isset($user) ? $user->birthday : '') }}">
                                @if ($errors->first('birthday'))
                                    <span class="text-danger">{{ $errors->first('birthday') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="text-label">Giới tính <sup class="text-danger">(*)</sup></label>
                                <select name="gender" class="circle-select" id="" style="width: 100% !important;">
                                    @foreach($genders as $key => $gender)
                                        <option {{ old('gender', isset($user) ? $user->gender : '') == $key ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $gender }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->first('gender'))
                                    <span class="text-danger">{{ $errors->first('gender') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="text-label">Số điện thoại</label>
                                <input type="text" name="phone" placeholder="Số điện thoại" value="{{ old('phone', isset($user) ? $user->phone : '') }}">
                                @if ($errors->first('phone'))
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="text-label">Địa chỉ </label>
                                <input type="text" name="address" placeholder="Địa chỉ" value="{{ old('address', isset($user) ? $user->address : '') }}">
                                @if ($errors->first('address'))
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="text-label">Căn cước công dân </label>
                                <input type="text" value="{{ old('citizen_id_number', isset($user) ? $user->citizen_id_number : '') }}" name="citizen_id_number" placeholder="Căn cước công dân">
                                @if ($errors->first('citizen_id_number'))
                                    <span class="text-danger">{{ $errors->first('citizen_id_number') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label class="text-label">Số thẻ bảo hiểm </label>
                                <input type="text" name="insurance_card_number" value="{{ old('insurance_card_number', isset($user) ? $user->insurance_card_number : '') }}" placeholder="Số thẻ bảo hiểm">
                                @if ($errors->first('insurance_card_number'))
                                    <span class="text-danger">{{ $errors->first('insurance_card_number') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="site-btn sb-gradient">Cập nhật thông tin</button>
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
