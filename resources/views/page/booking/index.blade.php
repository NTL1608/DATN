@extends('page.layouts.page')
@section('title', 'Đặt lịch khám')
@section('style')
@stop
@section('content')
@php
$link_img = '';
$title = 'Đặt lịch khám';
$description = 'Đặt lịch khám để chúng tôi có thể hỗ trợ bạn tốt hơn';
@endphp
@include('page.common.top_section', compact('title', 'description'))

<section class="service-section spad">
    <div class="container">
        <div class="row">
            <div class="col-8" style="margin: auto;">
                <ul class="comment-list" style="width: 100%; margin-bottom: 0px !important;">
                    <li style="width: 100%">
                        <img src="{{ isset($schedule->schedule->doctor) ? asset(pare_url_file($schedule->schedule->doctor->avatar)) : asset('page/img/iconbacsi.png') }}" class="comment-pic" alt="">
                        <div class="comment-text">
                            <h3 style="padding-top: 0px !important;">ĐẶT LỊCH KHÁM</h3>
                            <a href="">
                                <p>
                                    @if(isset($schedule->schedule->doctor))
                                        @php $userPosition = !empty($schedule->schedule->doctor->position) ? explode(',', $schedule->schedule->doctor->position) : [] @endphp
                                        {{ implode('.', array_map(fn($position) => $positionTs[$position] ?? '', $userPosition)) }}
                                    @endif
                                    {{ isset($schedule->schedule->doctor) ? $schedule->schedule->doctor->name : '' }}
                                </p>
                                <p>
                                    Khoa khám bệnh : {{ isset($schedule->schedule->doctor) && isset($schedule->schedule->doctor->clinic) ? $schedule->schedule->doctor->clinic->name : '' }}
                                </p>
                                <p>
                                    Dịch vụ :
                                    @if(isset($schedule->schedule->doctor->specialties))
                                        @foreach($schedule->schedule->doctor->specialties as $specialty)
                                            <span>{{ $specialty->name }}</span>,
                                        @endforeach
                                    @endif
                                </p>
                            </a>
                            <p>{{ $schedule->time_schedule }} - {{ getDateTime('vn', 1, 1, 0, '', strtotime($schedule->schedule->date_schedule)) }} {{ $schedule->schedule->date_schedule }}</p>
                            <p>Giá khám : <b>{{ !empty($schedule->schedule->doctor->price_min) ? number_format($schedule->schedule->doctor->price_min) : 0 }} đ</b></p>
                        </div>
                    </li>
                </ul>
                <form class="singup-form contact-form" action="{{ route('post.booking.appointment', $schedule->id) }}" method="POST">

                    <h3 class="comment-title">THÔNG TIN ĐẶT LỊCH KHÁM</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-label">Họ và tên <sup class="text-danger">(*)</sup></label>
                            <input type="text" name="name" value="{{ isset($user) ? $user->name : '' }}" placeholder="Hãy ghi rõ Họ Và Tên, viết hoa những chữ cái đầu tiên, ví dụ: Trần Văn Phú">
                            @if ($errors->first('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Ngày sinh <sup class="text-danger">(*)</sup></label>
                            <input type="date" name="birthday" value="{{ isset($user) ? $user->birthday : '' }}">
                            @if ($errors->first('birthday'))
                            <span class="text-danger">{{ $errors->first('birthday') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-label">Số điện thoại <sup class="text-danger">(*)</sup></label>
                            <input type="text" name="phone" placeholder="Số điện thoại" value="{{ isset($user) ? $user->phone : '' }}">
                            @if ($errors->first('phone'))
                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Địa chỉ <sup class="text-danger">(*)</sup></label>
                            <input type="text" name="address" placeholder="Địa chỉ" value="{{ isset($user) ? $user->address : '' }}">
                            @if ($errors->first('address'))
                            <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-label">Căn cước công dân </label>
                            <input type="text" value="{{ old('citizen_id_number', isset($user) ? $user->citizen_id_number : '') }}" name="citizen_id_number" placeholder="Căn cước công dân">
                            @if ($errors->first('citizen_id_number'))
                                <span class="text-danger">{{ $errors->first('citizen_id_number') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Số thẻ bảo hiểm </label>
                            <input type="text" name="insurance_card_number" value="{{ old('insurance_card_number', isset($user) ? $user->insurance_card_number : '') }}" placeholder="Số thẻ bảo hiểm">
                            @if ($errors->first('insurance_card_number'))
                                <span class="text-danger">{{ $errors->first('insurance_card_number') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
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
                            <label class="text-label">Email <sup class="text-danger">(*)</sup></label>
                            <input type="email" name="email" placeholder="Email" value="{{ isset($user) ? $user->email : '' }}">
                            @if ($errors->first('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-label">Dịch vụ <sup class="text-danger">(*)</sup></label>
                            <select name="specialty_id" class="circle-select" id="" style="width: 100% !important;">
                                <option value="">Chọn dịch vụ</option>
                                @if(isset($schedule->schedule->doctor->specialties))
                                    @foreach($schedule->schedule->doctor->specialties as $specialty)
                                        <option {{ old('specialty_id') == $specialty->id ? 'selected="selected"' : ''}} value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->first('specialty_id'))
                                <span class="text-danger">{{ $errors->first('specialty_id') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Đặt cho </label>
                            <select name="book_for" class="circle-select" id="" style="width: 100% !important;">
                                @foreach($book_for as $key => $book)
                                    <option {{ old('book_for') == $key ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $book }}</option>
                                @endforeach
                            </select>
                            @if ($errors->first('book_for'))
                                <span class="text-danger">{{ $errors->first('book_for') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-label">Thành phố</label>
                            <select class="js-select2 form-control address" name="city_id" data-type="district">
                                <option value="">Thành phố</option>
                                @if (isset($citys) && !empty($citys))
                                @foreach($citys as $city)
                                <option value="{{ $city->id }}" {{ isset($user) && $city->id === $user->city_id ? 'selected="selected"' : ''}}>{{ $city->loc_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="text-label">Tỉnh / Quận huyện</label>
                            <select class="js-select2 form-control address district" name="district_id" data-type="street">
                                <option value="">Tỉnh / Quận huyện</option>
                                @if (isset($district) && !empty($district))
                                @foreach($district as $di)
                                <option value="{{ $di->id }}" {{ isset($user) && $di->id === $user->district_id ? 'selected="selected"' : ''}}>{{ $di->loc_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="text-label">Phường / Xã</label>
                            <select class="js-select2 form-control address street" name="street_id">
                                <option value="">Phường / Xã</option>
                                @if (isset($street) && !empty($street))
                                @foreach($street as $st)
                                <option value="{{ $st->id }}" {{ isset($user) && $st->id === $user->street_id ? 'selected="selected"' : ''}}>{{ $st->loc_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="text-label">Lý do khám </label>
                            <textarea placeholder="Lý do khám" name="reason_other"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <ul class="comment-list" style="margin-bottom: 0px; width: 100%;">
                            <li>
                                <div class="comment-text">
                                    <h3>Chi phí cần thanh toán ( Thanh toán sau tại cơ sở y tế )</h3>
                                    <table class="table" style="width: 100%">
                                        <tr>
                                            <td>Giá khám</td>
                                            <td class="text-right">{{ !empty($schedule->schedule->doctor->price_min) ? number_format($schedule->schedule->doctor->price_min) : 0 }} đ</td>
                                        </tr>
                                        <tr>
                                            <td>Phí đặt lịch</td>
                                            <td class="text-right">Miễn phí</td>
                                        </tr>
                                        <tr>
                                            <td>Tổng cộng</td>
                                            <td class="text-right">{{ !empty($schedule->schedule->doctor->price_min) ? number_format($schedule->schedule->doctor->price_min) : 0 }} đ</td>
                                        </tr>
                                    </table>
                                </div>
                            </li>
                        </ul>
                        <p class="text-center" style="margin: auto">Quý khách vui lòng điền đầy đủ thông tin để tiết kiệm thời gian làm thủ tục khám</p>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="dauvao-canhbao">
                                <p style="text-align: justify; "><b>LƯU Ý</b></p>
                                <p style="text-align: justify; ">Thông tin anh/chị cung cấp sẽ được sử dụng làm hồ sơ khám bệnh, khi điền thông tin anh/chị vui lòng:</p>
                                <ul>
                                    <li style="text-align: justify; ">Ghi rõ họ và tên, viết hoa những chữ cái đầu tiên, ví dụ:<b> Trần Văn Phú&nbsp;</b></li>
                                    <li style="text-align: justify; ">Điền đầy đủ, đúng và vui lòng kiểm tra lại thông tin trước khi ấn "Xác nhận"</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @csrf
                            <button type="submit" class="site-btn sb-gradient">Xác nhận đặt khám</button>
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