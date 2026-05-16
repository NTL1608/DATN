@extends('admin.layouts.main')
@section('title', '')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"> <i class="nav-icon fas fa fa-home"></i> Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking.index') }}">Danh sách đặt lịch khám</a></li>
                    <li class="breadcrumb-item active">Danh sách</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header card-header-border-bottom">
                        <h3 class="card-title">Form tìm kiếm</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="">
                            <div class="row">
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <input type="text" name="user_code" class="form-control mg-r-15" placeholder="Mã bệnh nhân">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control mg-r-15" placeholder="Tên bệnh nhân or bác sĩ">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <input type="text" name="citizen_id_number" class="form-control mg-r-15" placeholder="Số căn cước công dân">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <input type="text" name="insurance_card_number" class="form-control mg-r-15" placeholder="Số bảo hiểm">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <input type="date" name="to_date_booking" class="form-control mg-r-15">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <input type="date" name="from_date_booking" class="form-control mg-r-15">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <select name="status" id="" class="form-control">
                                            <option value="">Trạng thái</option>
                                            @foreach($status as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <select name="clinic_id" class="form-control" id="change-clinic">
                                            <option value="">Chọn khoa khám bệnh</option>
                                            @foreach($clinics as $key => $clinic)
                                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <select name="specialty_id" id="specialty_id" class="form-control">
                                            <option value="">Chọn dịch vụ</option>
                                            @foreach($specialties as $key => $specialty)
                                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-2">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-success " style="margin-right: 10px"><i class="fas fa-search"></i> Tìm kiếm </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0 table-responsive-x">
                        <table class="table table-hover text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th width="4%" class=" text-center">STT</th>
                                    <th>Mã đặt lịch</th>
                                    @if($user->can(['toan-quyen-quan-ly', 'thong-tin-bac-si']))
                                    <th>Thông tin bác sĩ</th>
                                    @endif
                                    <th>Thông tin bệnh nhân</th>
                                    <th>Ngày đặt</th>
                                    <th>Thời gian</th>
                                    <th>Giá</th>
                                    <th>Kết quả</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian tạo</th>
                                    <th class=" text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$bookings->isEmpty())
                                @php $i = $bookings->firstItem(); @endphp
                                @foreach($bookings as $booking)
                                <tr>
                                    <td class=" text-center" style="vertical-align: middle;">{{ $i }}</td>
                                    <td style="vertical-align: middle;">
                                        {{ $booking->booking_code }}
                                    </td>
                                    @if($user->can(['toan-quyen-quan-ly', 'thong-tin-bac-si']))
                                    <td style="vertical-align: middle;">
                                        <p>Mã bác sĩ : {{ isset($booking->doctor) ? $booking->doctor->user_code : '' }}</p>
                                        <p>Bác sĩ: {{ isset($booking->doctor) ? $booking->doctor->name : '' }}</p>
                                        <p>Khoa khám bệnh: {{ isset($booking->doctor->clinic) ? $booking->doctor->clinic->name : '' }}</p>
                                        <p>Dịch vụ: {{ isset($booking->specialty->name) ? $booking->specialty->name : '' }}</p>
                                        <p>Địa chỉ khám: {{ isset($booking->doctor->clinic) ? $booking->doctor->clinic->address : '' }}</p>
                                        <p>Số thứ tự khám : {{ $booking->number }}</p>
                                    </td>
                                    @endif
                                    <td style="vertical-align: middle;">
                                        @if (isset($booking->patient))
                                        <p>Mã bệnh nhân: {{ $booking->patient->user_code }}</p>
                                        @endif
                                        <p>CCCD : {{ isset($booking->citizen_id_number) ? $booking->citizen_id_number : '' }}</p>
                                        <p>Số bảo hiểm : {{ isset($booking->insurance_card_number) ? $booking->insurance_card_number : '' }}</p>
                                        <p>Bệnh nhân: {{ $booking->name }}</p>
                                        <p>Giới tính: {{ isset($gender[$booking->gender]) ? $gender[$booking->gender] : '' }}</p>
                                        <p>Phone: {{ $booking->phone }}</p>
                                        <p>Ngày sinh: {{ $booking->birthday }}</p>
                                        <p>Đặt cho: {{ isset($book_for[$booking->book_for]) ? $book_for[$booking->book_for] : '' }}</p>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        {{ $booking->date_booking }}
                                    </td>
                                    <td style="vertical-align: middle;">
                                        {{ $booking->time_booking }}
                                    </td>
                                    <td style="vertical-align: middle;">
                                        {{ number_format($booking->price) }} đ
                                    </td>
                                    <td style="vertical-align: middle;">
                                        @if (in_array($booking->status, [4, 5]))
                                        <p><a target="_blank" href="{{ route('booking.result.print', $booking->id) }}">Phiếu kết quả</a></p>
                                        @endif
                                        @if ($booking->file_result)
                                        <a href="{{ convertUrl(asset('uploads/file-result/'. $booking->file_result)) }}" download="{{ $booking->file_result }}">File Kết quả </a>
                                        @endif

                                    </td>

                                    <td class="text-center" style="vertical-align: middle;">
                                        <button type="button" class="btn btn-block {{ isset($class_status[$booking->status]) ? $class_status[$booking->status] : '' }} btn-xs">{{ isset($status[$booking->status]) ? $status[$booking->status] : '' }}</button>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        {{ date('Y-m-d H:i', strtotime($booking->created_at)) }}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm">Hành động</button>
                                            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu action-transaction" role="menu">
                                                <li><a href="{{ route('booking.delete', $booking->id) }}" class="btn-confirm-delete"><i class="fa fa-trash"></i> Delete</a></li>
                                                <li class="update_booking" url='{{ route('booking.update.status', $booking->id) }}?status=1'><a><i class="fas fa-check"></i> Tiếp nhận</a></li>
                                                <li class="update_booking" url='{{ route('booking.update.status', $booking->id) }}?status=2'><a><i class="fas fa-check"></i> KH xác nhận</a></li>
                                                <li class="update_booking" url='{{ route('booking.update.status', $booking->id) }}?status=7'><a><i class="fas fa-check"></i> NV xác nhận</a></li>
                                                <li class="update_booking" url='{{ route('booking.update.status', $booking->id) }}?status=3'><a><i class="fas fa-check"></i> Đã thanh toán</a></li>
                                                <li class="update_booking" url='{{ route('booking.update.status', $booking->id) }}?status=4'><a><i class="fas fa-check"></i> Đã khám</a></li>
                                                <li class="update_booking" url='{{ route('booking.update.status', $booking->id) }}?status=5'><a><i class="fas fa-check"></i> Đã trả kết quả</a></li>
                                                <li class="update_booking" url='{{ route('booking.update.status', $booking->id) }}?status=6'><a><i class="fa fa-ban"></i> Huỷ</a></li>
                                            </ul>
                                        </div>
                                        @if ($booking->status == 4)
                                        <a class="btn btn-primary btn-sm" href="{{ route('booking.update', $booking->id) }}" title="Kết quả khám">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        @endif
                                        @if (in_array($booking->status, [3, 4, 5]))
                                        <a class="btn btn-info btn-sm" href="{{ route('booking.medical.exam.form', $booking->id) }}" title="Kết quả khám">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @php $i++ @endphp
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if($bookings->hasPages())
                        <div class="pagination float-right margin-20">
                            {{ $bookings->appends($query = '')->links() }}
                        </div>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>
@stop