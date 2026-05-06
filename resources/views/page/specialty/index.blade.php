@extends('page.layouts.page')
@section('title', 'Dịch vụ')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Dịch vụ';
        $description = 'Tìm bác sĩ theo dịch vụ';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))
    <div class="container">
        <div class="event-filter-warp">
            <div class="row">
                <div class="col-xl-12">
                    <form class="event-filter-form" action="">
                        <div class="ef-item" style="width: 75% !important;">
                            <input type="text" name="keyword" placeholder="Tìm kiếm với tên dịch vụ">
                            <i class="material-icons">search</i>
                        </div>
                        <button type="submit" class="site-btn sb-gradient">Tìm kiếm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <section class="service-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="comment-title">Dịch vụ</h3>
                    <ul class="comment-list d-flex flex-wrap">
                        @foreach($specialties as $specialty)
                            <li style="width: 50%; display: flex; margin-bottom: 20px;">
                                <img src="{{ isset($specialty->image) ? asset(pare_url_file($specialty->image)) : asset('page/img/image_default.svg') }}" class="comment-pic" alt="">
                                <div class="comment-text">
                                    <a href="{{ route('specialty.detail', ['id' => $specialty->id, 'slug' => safeTitle($specialty->name)]) }}">
                                        <h3>{{ $specialty->name }}</h3>
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    {{ $specialties->appends($query = '')->links('page.paginator.index') }}
                </div>
            </div>
        </div>
    </section>
    <!-- Service Section end -->
@stop
@section('script')
@stop
