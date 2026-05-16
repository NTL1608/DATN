@extends('page.layouts.page')
@section('title', isset($clinic) ? $clinic->name : '')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = isset($clinic) ? $clinic->name : '';
        $description = 'Khoa khám bệnh';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))

    <section class="service-section spad">
        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--@foreach($doctors as $user)--}}
                    {{--<div class="blog-author">--}}
                        {{--@include('page.common.user_item', compact('user'))--}}
                    {{--</div>--}}
                {{--@endforeach--}}

            {{--</div>--}}
        {{--</div>--}}
        <div class="container" style="margin-top: 50px">
            <div class="row">
                <div class="blog-details">
                    {!! $clinic->contents !!}
                </div>
            </div>
        </div>
        <div class="container">
            @if($users->isNotEmpty())
            <div class="section-title text-center">
                <h2>Cán bộ</h2>
            </div>
            @endif
            <div class="row justify-content-center text-center">
                @if(isset($users[1]))
                    @foreach($users[1] as $user)
                        <div class="col-md-4">
                            <div class="pc-item">
                                <img src="{{ isset($user->avatar) ? asset(pare_url_file($user->avatar)) : asset('page/img/iconbacsi.png') }}" alt="">
                                <div class="pc-text">
                                    <h4><a href="{{ route('doctor.detail', ['id' => $user->id, 'slug' => safeTitle($user->name)]) }}">
                                            <span style="color: red">
                                                @php $userPosition = !empty($user->position) ? explode(',', $user->position) : [] @endphp
                                                {{ implode('.', array_map(fn($position) => $positions[$position] ?? '', $userPosition)) }}
                                            </span>
                                            {{ $user->name }}</a></h4>
                                    <ul>
                                        <li style="color: red">{{ isset($jobTitle[$user->job_title]) ? $jobTitle[$user->job_title] : '' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="row justify-content-center text-center">
                @if(isset($users[2]))
                    @foreach($users[2] as $user)
                        <div class="col-md-4">
                            <div class="pc-item">
                                <img src="{{ isset($user->avatar) ? asset(pare_url_file($user->avatar)) : asset('page/img/iconbacsi.png') }}" alt="">
                                <div class="pc-text">
                                    <h4><a href="{{ route('doctor.detail', ['id' => $user->id, 'slug' => safeTitle($user->name)]) }}">
                                            <span style="color: red">
                                                @php $userPosition = !empty($user->position) ? explode(',', $user->position) : [] @endphp
                                                {{ implode('.', array_map(fn($position) => $positions[$position] ?? '', $userPosition)) }}
                                            </span>
                                            {{ $user->name }}</a></h4>
                                    <ul>
                                        <li style="color: red">{{ isset($jobTitle[$user->job_title]) ? $jobTitle[$user->job_title] : '' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="row justify-content-center text-center">
                @if(isset($users[3]))
                    @foreach($users[3] as $user)
                        <div class="col-md-4">
                            <div class="pc-item">
                                <img src="{{ isset($user->avatar) ? asset(pare_url_file($user->avatar)) : asset('page/img/iconbacsi.png') }}" alt="">
                                <div class="pc-text">
                                    <h4><a href="{{ route('doctor.detail', ['id' => $user->id, 'slug' => safeTitle($user->name)]) }}">
                                            <span style="color: red">
                                                @php $userPosition = !empty($user->position) ? explode(',', $user->position) : [] @endphp
                                                {{ implode('.', array_map(fn($position) => $positions[$position] ?? '', $userPosition)) }}
                                            </span>
                                            {{ $user->name }}</a></h4>
                                    <ul>
                                        <li style="color: red">{{ isset($jobTitle[$user->job_title]) ? $jobTitle[$user->job_title] : '' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    <!-- Service Section end -->
@stop
@section('script')
@stop
