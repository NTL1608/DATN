@extends('page.layouts.page')
@section('title', 'Giới thiệu về chúng tôi')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Giới thiệu';
        $description = 'Quá trình phát triển và hình thành của chúng tôi';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))

    <section class="service-section spad">
        <div class="container">
            <div class="row">

            </div>
        </div>
    </section>
    <!-- Service Section end -->
@stop
@section('script')
@stop