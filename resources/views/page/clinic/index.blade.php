@extends('page.layouts.page')
@section('title', 'Khoa khám bệnh')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Khoa khám bệnh';
        $description = 'Tìm bác sĩ theo khoa khám bệnh';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))

    <section class="service-section spad">
        <div class="container">
            <div class="row">
                @foreach($clinics as $clinic)
                    <div class="col-lg-6">
                        @include('page.common.clinic_item', compact('clinic'))
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-lg-12" style="margin-top: 15px">
                    {{ $clinics->appends($query = '')->links('page.paginator.index') }}
                </div>
            </div>
        </div>
    </section>
    <!-- Service Section end -->
@stop
@section('script')
@stop
