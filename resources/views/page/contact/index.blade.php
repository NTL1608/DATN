@extends('page.layouts.page')
@section('title', 'Liên hệ')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Liên hệ';
        $description = 'Liên hệ với chúng tôi để bạn có thể nhận được chăm sóc và tư vấn tốt nhất';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))

    <section class="contact-page-section spad overflow-hidden">
        <div class="container">
            <div class="contact-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.186380640254!2d105.82464037599841!3d21.025227187898373!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab75c1fe0b85%3A0xc52e209c7fd4903f!2zUC4gSMOgbyBOYW0sIMSQ4buRbmcgxJBhLCBIw6AgTuG7mWksIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1745165559980!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="con-info">
                        <h3>Trụ sở tại Hà Nội</h3>
                        <ul>
                            <li><i class="material-icons">map</i>{{ config('setting.address') }}</li>
                        </ul>
                    </div>
                    <div class="con-info">
                        <h3>Thời gian làm việc</h3>
                        <ul>
                            <li><i class="material-icons">alarm_on</i>{{ config('setting.alarm_on_mon_fri') }}</li>
                            <li><i class="material-icons">alarm_on</i>{{ config('setting.alarm_on_sat_sun') }}</li>
                        </ul>
                    </div>
                    <div class="contact-social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <form class="singup-form contact-form" method="POST" action="{{ route('send.contact') }}">
                        <div class="row">
                            @csrf
                            <div class="col-md-6">
                                <input type="text" name="name" placeholder="Họ và tên">
                                @if ($errors->first('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" placeholder="Email">
                                @if ($errors->first('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <input type="text" name="phone" placeholder="Số điện thoại">
                                @if ($errors->first('phone'))
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <textarea name="message" placeholder="Nội dung liên hệ"></textarea>
                                @if ($errors->first('message'))
                                    <span class="text-danger">{{ $errors->first('message') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="site-btn sb-gradient">Gửi liên hệ</button>
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
