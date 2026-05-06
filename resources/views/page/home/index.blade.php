@extends('page.layouts.page')
@section('title', 'Bệnh viện Đa khoa Phương Đông')
@section('style')
@stop
@section('content')
@include('page.common.slider', compact('slides'))
@include('page.common.search')

<!-- Classes Section -->
<section class="classes-section spad">
    <div class="container">
        <div class="section-title text-center">
            <img src="{{ asset('page/img/images (2).png') }}" alt="">
            <h2>Dịch vụ</h2>
            <p>Danh sách dịch vụ khách hàng có thể tìm nhanh thông tin bác sĩ !</p>
        </div>
        <div class="gallery-slider owl-carousel">
            @foreach($specialties as $spe)
            <div class="gs-item">
                <img class="specialties-image" src="{{ isset($spe->image) ? asset(pare_url_file($spe->image)) : asset('page/img/image_default.svg') }}" style="height: 150px !important;" alt="">
                <div class="gs-hover">
                    <a href="{{ route('specialty.detail', ['id' => $spe->id, 'slug' => safeTitle($spe->name)]) }}">
                        <i class="fa fa-fw fa-eye"></i>
                        <p>{{ $spe->name }}</p>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Classes Section end -->
<!-- Trainer Section -->
<section class="trainer-section overflow-hidden spad">
    <div class="container">
        <div class="section-title text-center">
            <img src="{{ asset('page/img/icons/images (2).png') }}" alt="">
            <h2>Danh sách khoa khám bệnh</h2>
            <p>Danh sách các khoa khám bệnh giúp bệnh nhân xem thống tin và tìm kiếm bác sĩ nhanh nhất!</p>
        </div>
        <div class="trainer-slider owl-carousel">
            @foreach($clinics as $clinic)
            <div class="ts-item">
                @include('page.common.clinic_item', compact('clinic'))
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Trainer Section end -->

<section class="classes-section spad">
    <div class="container">
        <div class="section-title text-center">
            <img src="{{ asset('page/img/icons/images (2).png') }}" alt="">
            <h2>Bác sĩ</h2>
            <p></p>
        </div>
        <div class="classes-slider owl-carousel">
            @foreach($users as $user)
            <div class="classes-item">
                <div class="ci-img">
                    <img src="{{ isset($user->avatar) ? asset(pare_url_file($user->avatar)) : asset('page/img/iconbacsi.png') }}" alt="">
                </div>
                <div class="ci-text">
                    <h4>
                        <a href="{{ route('doctor.detail', ['id' => $user->id, 'slug' => safeTitle($user->name)]) }}">
                            {{ $user->name }}</a>
                    </h4>
                    <div class="ci-metas">
                        <div class="ci-meta">
                            @php $userPosition = !empty($user->position) ? explode(',', $user->position) : [] @endphp
                            {{ implode(', ', array_map(fn($position) => $positions[$position] ?? '', $userPosition)) }}
                        </div>
                        <div class="ci-meta">
                            <p>{{ isset($user->clinic) ? $user->clinic->name : '' }}</p>
                        </div>
                        <div class="ci-meta">
                            @php
                            $number = 0;
                            $star = 0;
                            $medium = 0;
                            if (isset($user->ratings)) {
                            $number = $user->ratings->count();
                            $star = $user->ratings->sum('star');
                            }
                            if ($number > 0) {
                            $medium = $star / $number;
                            $medium = round($medium, 1);
                            }
                            @endphp
                            @for($i =1 ; $i <=5; $i ++)
                                <i class="fa fa-fw fa-star {{ $medium >= $i ? 'star-yellow' : 'star-default'  }}"></i>
                                @endfor
                        </div>
                    </div>
                    {{--<p>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis</p>--}}
                </div>
                <div class="ci-bottom">
                    <a href="{{ route('doctor.detail', ['id' => $user->id, 'slug' => safeTitle($user->name)]) }}" class="site-btn sb-gradient">Đặt lịch</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@stop
@section('script')
@stop
