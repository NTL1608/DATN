@extends('page.layouts.page')
@section('title', 'Tìm kiếm bác sĩ')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Tìm kiếm bác sĩ';
        $description = 'Tìm kiếm nhanh thông tin bác sĩ theo tên khoa khám bệnh và dịch vụ';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))
    @include('page.common.search')
    <section class="service-section spad">
        <div class="container">
            <div class="row">
                @foreach($doctors as $user)
                    <div class="blog-author">
                        @include('page.common.user_item', compact('user'))
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Service Section end -->
@stop
@section('script')
@stop
