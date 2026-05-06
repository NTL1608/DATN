@extends('page.layouts.page')
@section('title', 'Danh sách đặt lịch')
@section('style')
@stop
@section('content')
@php
$link_img = '';
$title = 'Danh sách đặt lịch';
$description = 'Danh sách lịch khám của bạn';
@endphp
@include('page.common.top_section', compact('title', 'description'))

<section class="service-section spad">
    <div class="container">
        <div class="row">
            @include('page.common.menu_user')
            <div class="col-lg-9">
                <h2 class="sb-title">Danh sách đặt lịch</h2>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap table-bordered" style="margin-top: 30px">
                        <thead>
                            <tr>
                                <th scope="col">STT</th>
                                <th>Mã đặt lịch</th>
                                <th scope="col">Thông tin bác sĩ</th>
                                <th scope="col">Ngày khám</th>
                                <th scope="col">Thời gian</th>
                                <th scope="col">Giá khám</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Kết quả</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = $bookings->firstItem(); @endphp
                            @foreach($bookings as $booking)
                            <tr>
                                <th scope="row" style="vertical-align: middle">{{ $i }}</th>
                                <td style="vertical-align: middle">
                                    {{ $booking->booking_code }}
                                </td>
                                <td style="vertical-align: middle">
                                    <p>Bác sĩ:
                                        @if(isset($booking->doctor))
                                        @php $userPosition = !empty($booking->doctor->position) ? explode(',', $booking->doctor->position) : [] @endphp
                                        {{ implode('.', array_map(fn($position) => $positionTs[$position] ?? '', $userPosition)) }}
                                        @endif
                                        {{ isset($booking->doctor) ? $booking->doctor->name : '' }}
                                    </p>
                                    <p>Khoa khám bệnh: {{ isset($booking->doctor->clinic) ? $booking->doctor->clinic->name : '' }}</p>
                                    <p>Dịch vụ: {{ isset($booking->specialty->name) ? $booking->specialty->name : '' }}</p>
                                    <p>Địa chỉ khám: {{ isset($booking->doctor->clinic) ? $booking->doctor->clinic->address : '' }}</p>
                                    <p>Số thứ tự khám : {{ $booking->number }}</p>
                                </td>
                                <td style="vertical-align: middle">{{ $booking->date_booking }}</td>
                                <td style="vertical-align: middle">{{ $booking->time_booking }}</td>
                                <td style="vertical-align: middle">{{ number_format($booking->price) }} đ</td>
                                <td style="vertical-align: middle">
                                    <button type="button" class="btn btn-status-booking {{ isset($class_status[$booking->status]) ? $class_status[$booking->status] : '' }} btn-xs">{{ isset($status[$booking->status]) ? $status[$booking->status] : '' }}</button>
                                </td>
                                <td style="vertical-align: middle">
                                    @if ($booking->file_result)
                                    <p><a href="{{ convertUrl(asset('uploads/file-result/'. $booking->file_result)) }}" download="{{ $booking->file_result }}">File kết quả </a></p>
                                    @endif
                                    @if (in_array($booking->status, [4, 5]))
                                    <p><a target="_blank" href="{{ route('booking.result.print', $booking->id) }}">Phiếu kết quả</a></p>
                                    @endif
                                </td>
                                <td style="vertical-align: middle">
                                    @if ($booking->status == 1)
                                    <div class="sb-tags">
                                        <a href="{{ route('cancel.booking', $booking->id) }}" class="btn-danger btn-sm">Hủy đặt lịch</a>
                                    </div>
                                    @endif
                                    <div class="sb-tags">
                                        <a href="{{ route('booking.print.medical.exam.form', $booking->id) }}" class="btn-danger btn-sm">Xem</a>
                                    </div>
                                </td>
                            </tr>
                            @php $i++ @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-12" style="margin-top: 15px">
                        {{ $bookings->appends($query = '')->links('page.paginator.index') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Service Section end -->
@stop
@section('script')
@stop