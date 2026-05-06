@extends('admin.layouts.main')
@section('title', 'Quản lý lịch khám')
@section('style-css')
<!-- fullCalendar -->
@stop
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Quản lý lịch khám</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="#">Quản lý lịch khám</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Danh sách thống kê</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    @if($user->can(['toan-quyen-quan-ly', 'danh-sach-lich-lam-viec']))
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>
                                    @if($user->hasRole(['super-admin']))
                                    {{ $schedule->count() }}
                                    @else
                                    {{ $schedule->count() >= 1 ? 'Đã đăng ký' : 'Chưa đăng ký' }}
                                    @endif

                                </h3>

                                <p>Lịch làm việc hôm nay</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <a href="{{ route('schedule.index', ['to_date_schedule' => $currentTime, 'from_date_schedule' => $currentTime]) }}" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif
                    @if($user->can(['toan-quyen-quan-ly', 'danh-sach-lich-kham']))
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $bookings->count() }}</h3>

                                <p>Lịch khám hôm nay</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('booking.index', ['to_date_booking' => $currentTime, 'from_date_booking' => $currentTime]) }}" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif

                    @if($user->can(['toan-quyen-quan-ly', 'danh-sach-phong-kham']))
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $clinic }}</h3>

                                <p>Số khoa khám bệnh</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-fw fa-hotel"></i>
                            </div>
                            <a href="{{ route('clinic.index') }}" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif
                    <!-- ./col -->
                    @if($user->can(['toan-quyen-quan-ly', 'danh-sach-chuyen-khoa']))
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $specialty }}</h3>

                                <p>Số dịch vụ</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-fw fa-heartbeat"></i>
                            </div>
                            <a href="{{ route('specialty.index') }}" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif
                    <!-- ./col -->

                    <!-- ./col -->
                    @if($user->can(['toan-quyen-quan-ly', 'danh-sach-benh-nhan']))
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $patient }}</h3>

                                <p>Số bệnh nhân</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-fw fa-user"></i>
                            </div>
                            <a href="{{ route('patient.index') }}" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif
                    <!-- ./col -->

                    <!-- ./col -->
                    @if($user->can(['toan-quyen-quan-ly', 'danh-sach-bac-si']))
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $doctor }}</h3>

                                <p>Số bác sĩ</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-fw fa-user-secret"></i>
                            </div>
                            <a href="{{ route('user.index') }}" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <!-- /.col -->
            <div class="col-md-12">

                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

{{-- BIỂU ĐỒ THÊM MỚI --}}
<section class="content">
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Biểu đồ bệnh nhân theo bác sĩ (năm {{ date('Y') }})
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                {{-- THÊM MỚI: bộ lọc năm --}}
                <div class="mb-3">
                    <form method="GET" action="{{ route('admin.home') }}" class="form-inline">
                        <label class="mr-2">Năm:</label>
                        <select name="year" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                            <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>Tất cả</option>
                            <option value="2024" {{ $selectedYear == '2024' ? 'selected' : '' }}>2024</option>
                            <option value="2025" {{ $selectedYear == '2025' ? 'selected' : '' }}>2025</option>
                            <option value="2026" {{ $selectedYear == '2026' ? 'selected' : '' }}>2026</option>
                        </select>
                    </form>
                </div>
                {{-- KẾT THÚC THÊM MỚI --}}
                <canvas id="chartTheoKhoa" height="80"></canvas>
                <table class="table table-hover table-bordered mt-4">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center">STT</th>
                            <th>Tên bác sĩ</th>
                            <th class="text-center">Số bệnh nhân</th>
                            <th class="text-center">Tỉ lệ %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $tongSoLuong = $bookingByClinic->sum('so_luong'); @endphp
                        @foreach($bookingByClinic as $key => $item)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $item->ten_khoa }}</td>
                            <td class="text-center">{{ $item->so_luong }}</td>
                            <td class="text-center">
                                {{ $tongSoLuong > 0 ? round($item->so_luong / $tongSoLuong * 100, 1) : 0 }}%
                            </td>
                        </tr>
                        @endforeach
                        <tr class="bg-light">
                            <td colspan="2"><strong>Tổng cộng</strong></td>
                            <td class="text-center"><strong>{{ $tongSoLuong }}</strong></td>
                            <td class="text-center"><strong>100%</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
{{-- KẾT THÚC BIỂU ĐỒ THÊM MỚI --}}

<section class="content">
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Lịch khám hôm nay</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th width="4%" class=" text-center">STT</th>
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
                                @foreach($bookings as $key => $booking)
                                <tr>
                                    <td class=" text-center" style="vertical-align: middle;">{{ $key + 1 }}</td>
                                    <td style="vertical-align: middle;">
                                        @if (isset($booking->patient))
                                        <p>Mã bệnh nhân: {{ $booking->patient->user_code }}</p>
                                        @endif
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
                                        @if ($booking->file_result)
                                            <a href="{{ convertUrl(asset('uploads/file-result/'. $booking->file_result)) }}" download="{{ $booking->file_result }}">Kết quả </a>
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
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@stop
@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function () {
        new Chart(document.getElementById('chartTheoKhoa'), {
            type: 'bar',
            data: {
                labels: @json($bookingByClinic->pluck('ten_khoa')),
                datasets: [{
                    label: 'Số bệnh nhân',
                    data: @json($bookingByClinic->pluck('so_luong')),
                    backgroundColor: [
                        '#3498db','#1abc9c','#e67e22',
                        '#e74c3c','#9b59b6','#34495e','#f39c12'
                    ],
                    borderWidth: 0,
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Số bệnh nhân theo từng khoa'
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    });
</script>
@stop
