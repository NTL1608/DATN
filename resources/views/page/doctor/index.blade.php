@extends('page.layouts.page')
@section('title', $doctor->name)
@section('style')
<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('page/plugin/star-rating/src/css/star-rating-svg.css') }}">
@stop
@section('content')
@php
$link_img = '';
$title = $doctor->name;
$description = isset($doctor->job_title) ? $jobTitle[$doctor->job_title] : '';
@endphp
@include('page.common.top_section', compact('title', 'description'))

<section class="trainer-details-section spad overflow-hidden" style="padding-bottom: 30px !important;">
    <div class="container">
        <div class="trainer-details">
            <div class="trainer-info">
                <div class="td-left">
                    <img src="{{ isset($doctor->avatar) ? asset(pare_url_file($doctor->avatar)) : asset('page/img/iconbacsi.png') }}" alt="">
                    <div class="td-social">
                        {{-- <a href="#"><i class="fa fa-facebook"></i></a>--}}
                        {{-- <a href="#"><i class="fa fa-instagram"></i></a>--}}
                        {{-- <a href="#"><i class="fa fa-twitter"></i></a>--}}
                        {{-- <a href="#"><i class="fa fa-linkedin"></i></a>--}}
                    </div>
                </div>
                <div class="td-right">
                    <h3>
                        @php $userPosition = !empty($doctor->position) ? explode(',', $doctor->position) : [] @endphp
                        {{ implode('.', array_map(fn($position) => $positionTs[$position] ?? '', $userPosition)) }}
                        {{ $doctor->name }}
                    </h3>
                    {{-- <h6>Khoa khám bệnh : {{ isset($doctor->specialty) ? $doctor->specialty->name : '' }}</h6>--}}
                    {{-- <h6>Dịch vụ : {{ isset($doctor->clinic) ? $doctor->clinic->name : '' }}</h6>--}}
                    <div claas="row">
                        <div class="ei-text">
                            <ul>
                                <li>
                                    <i class="material-icons">event_available</i>LỊCH KHÁM
                                    @if ($doctor->schedule)
                                    <select class="date-booking-schedule" style="margin-left: 30px;">
                                        @if ($doctor->schedule->isNotEmpty())
                                        @foreach($doctor->schedule as $schedule)
                                        <option value="{{ $schedule->id }}"> {{ getDateTime('vn', 1, 1, 0, '', strtotime($schedule->date_schedule)) . '-' . date('m/d', strtotime($schedule->date_schedule)) }}</option>
                                        @endforeach
                                        @else
                                        <option>Chưa đăng ký lịch khám</option>
                                        @endif
                                    </select>
                                    @endif
                                </li>
                            </ul>
                        </div>
                        @if ($doctor->schedule)
                        @foreach($doctor->schedule as $key => $schedule)
                        @if ($schedule->times)
                        <div class="col-12 list-times list-times-{{ $schedule->id }}" style="display: {{ $key == 0 ? 'block' : 'none'}}">
                            <div class="sb-tags">
                                @foreach($schedule->times as $key => $time)
                                <a href="{{ route('booking.appointment', $time->id) }}">{{ $time->time_schedule }}</a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif
                    </div>
                    <div class="ei-text">
                        <ul>
                            <li><i class="material-icons">local_offer</i>THÔNG TIN</li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <ul class="info-doctor">
                                <li><strong>Chức danh:</strong>
                                    <p>{{ isset($jobTitle[$doctor->job_title]) ? $jobTitle[$doctor->job_title] : '' }}</p>
                                </li>
                                <li><strong>Chức Vụ:</strong>
                                        {{ implode(', ', array_map(fn($position) => $positions[$position] ?? '', $userPosition)) }}
                                </li>
                                <li><strong title="Khoa khám bệnh">Khoa KB :</strong>
                                    <p>{{ isset($doctor->clinic) ? $doctor->clinic->name : '' }}</p>
                                </li>
                                <li><strong>Dịch vụ: </strong>
                                    <p>
                                        @if(isset($doctor->specialties))
                                            @foreach($doctor->specialties as $specialty)
                                                <span>{{ $specialty->name }}</span>,
                                            @endforeach
                                        @endif
                                    </p>
                                </li>
                                
                                <li>
                                    <strong style="margin-top: 11px;">Đánh giá : </strong>
                                    <p><span class="rating-list"></span></p>
                                </li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul>
                            
                                <li><strong>Email:</strong>
                                    <p>{{ $doctor->email }}</p>
                                </li>
                                <li><strong>Phone: </strong>
                                    <p>{{ $doctor->phone }}</p>
                                </li>
                                <li><strong>Ngày sinh :</strong>
                                    <p>{{ isset($doctor->birthday) ? $doctor->birthday : '' }}</p>
                                </li>
                                <li><strong>Địa chỉ:</strong>
                                    <p>
                                        {{ isset($doctor->city) ? $doctor->city->loc_name. '-' : '' }}
                                        {{ isset($doctor->district) ? $doctor->district->loc_name. '-' : '' }}
                                        {{ isset($doctor->street) ? $doctor->street->loc_name. ' ' : '' }}
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="ei-text">
                        <ul>
                            <li><i class="material-icons">local_offer</i>GIÁ KHÁM : <b>{{ !empty($doctor->price_min) ? number_format($doctor->price_min) : 0 }} đ</b></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="trainer-details-text">
                <h3>Giới thiệu</h3>
                {!! $doctor->contents !!}
            </div>
        </div>
    </div>
</section>
<!-- Service Section end -->
<section class="trainer-details-section overflow-hidden">
    <div class="container">
        <div class="trainer-details">
            <div class="sb-widget">
                <h2 class="sb-title">THÔNG TIN</h2>
                <div class="sb-tags">
                    <a href="#comment" class="comment {{ empty(request()->get('tab')) ? 'active-info' : 'none' }}">BÌNH LUẬN</a>
                    <a href="#rating" class="rating {{ !empty(request()->get('tab')) ? 'active-info' : 'none' }}">ĐÁNH GIÁ</a>
                </div>
                <div id="comment" style="display: {{ empty(request()->get('tab')) ? 'block' : 'none' }}">
                    <div id="fb-root"></div>
                    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v17.0&appId=3498953243538776&autoLogAppEvents=1" nonce="yFB4QAsV"></script>
                    <div class="fb-comments" data-href="{{ route('doctor.detail', ['id' => $doctor->id, 'slug' => safeTitle($doctor->name)]) }}" data-width="100%" data-numposts="10"></div>
                </div>
                <div id="rating" style="display: {{ !empty(request()->get('tab')) ? 'block' : 'none' }}">
                    <form class="singup-form" action="{{ isset($ratingEdit) ? route('user.rating', ['id' => $doctor->id, 'rating_id' => $ratingEdit->id]) : route('user.rating', $doctor->id) }}" method="POST">
                        <div class="row" style="margin-top: 20px">
                            <div class="col-md-3" style="margin-bottom: 10px">
                                <!-- example using callback -->
                                <span class="doctor-rating"></span>
                                <input type="hidden" name="star" class="input-star" value="{{ old('star', isset($ratingEdit) ? $ratingEdit->star : 0) }}">
                                @if ($errors->first('star'))
                                <span class="text-danger">{{ $errors->first('star') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <textarea placeholder="Nội dung đánh giá" name="content">{{ old('content', isset($ratingEdit) ? $ratingEdit->content : '') }}</textarea>
                                @if ($errors->first('content'))
                                <span class="text-danger">{{ $errors->first('content') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                @csrf
                                <button type="submit" class="site-btn sb-gradient btn-send-rating">Gửi đánh giá</button>
                            </div>
                        </div>
                    </form>

                    <h2 class="sb-title" style="margin-top: 40px">Danh sách đánh giá</h2>
                    <div class="row">
                        <ul class="list-star">
                            @for($i = 1 ; $i <= 5; $i++) @foreach($stars as $star) @if ($i==$star->star)
                                <li><a href="{{ route('doctor.detail', ['id' => $doctor->id, 'slug' => safeTitle($doctor->name), 'star' => $i, 'tab'=>'rating']) }}"><i class="fa fa-fw fa-star star-yellow "></i> {{ $i }} Sao ({{ $star->number_star }})</a></li>
                                @else
                                <li><a href="{{ route('doctor.detail', ['id' => $doctor->id, 'slug' => safeTitle($doctor->name), 'star' => $i, 'tab'=>'rating']) }}"><i class="fa fa-fw fa-star star-yellow "></i> {{ $i }} Sao (0)</a></li>
                                @endif
                                @endforeach
                                @endfor
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <ul class="comment-list">
                            @foreach($ratings as $rating)
                            <li>
                                <div class="comment-text">
                                    <h3>{{ isset($rating->patient) ? $rating->patient->name : '' }}</h3>
                                    <div class="comment-date">
                                        <i class="material-icons">alarm_on</i>{{ date('Y-m-d H:i', strtotime($rating->created_at)) }}
                                        @if(Auth::guard('users')->check())
                                        @if (Auth::guard('users')->id() == $rating->patient_id)
                                        <a href="{{ route('doctor.detail', ['id' => $doctor->id, 'slug' => safeTitle($doctor->name), 'rating_id' => $rating->id]) }}"><i class="fa fa-fw fa-pencil" style="top: 0px; margin-left: 15px;"></i></a>
                                        @endif
                                        @endif
                                    </div>
                                    </br>
                                    <div class="comment-date">
                                        @for($i =1 ; $i <=5; $i ++) <i class="fa fa-fw fa-star {{ $rating->star >= $i ? 'star-yellow' : 'star-default'  }}"></i>
                                            @endfor
                                    </div>
                                    <p>{{ $rating->content }} </p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="margin-top: 15px">
                            {{ $ratings->appends($query = '')->links('page.paginator.index') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
@section('script')
<script src="{{ asset('page/plugin/star-rating/dist/jquery.star-rating-svg.min.js') }}"></script>
<script>
    var numberRating = "{{ $numberStar }}"
    var editRating = "{{isset($ratingEdit) ? $ratingEdit->star : 0}}"
    $(function() {

        $('.comment').click(function(event) {
            event.preventDefault()
            $('#comment').css('display', 'block');
            $('#rating').css('display', 'none');
            $('.rating').removeClass('active-info');
            $('.comment').removeClass('active-info');
            $('.comment').addClass('active-info');
        })

        $('.rating').click(function(event) {
            event.preventDefault()
            $('#comment').css('display', 'none');
            $('#rating').css('display', 'block');

            $('.rating').removeClass('active-info');
            $('.comment').removeClass('active-info');
            $('.rating').addClass('active-info');
        })

        $(".doctor-rating").starRating({
            initialRating: parseFloat(editRating),
            useFullStars: true,
            disableAfterRate: false,
            onHover: function(currentIndex, currentRating, $el) {
                console.log('index: ', currentIndex, 'currentRating: ', currentRating, ' DOM element ', $el);
                $('.input-star').val(currentIndex);
            },
            onLeave: function(currentIndex, currentRating, $el) {
                console.log('index: ', currentIndex, 'currentRating: ', currentRating, ' DOM element ', $el);
                $('.input-star').val(currentIndex);
            }
        });
        $(".rating-list").starRating({
            initialRating: parseFloat(numberRating),
            useFullStars: true,
            disableAfterRate: true,
        });
    })
</script>
@stop