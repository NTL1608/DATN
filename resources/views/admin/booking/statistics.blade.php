@extends('admin.layouts.main')
@section('title', '')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"> <i class="nav-icon fas fa fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('booking.report.statistics') }}">Báo cáo thống kê</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover text-nowrap table-bordered" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th width="4%" class=" text-center">STT</th>
                                    <th>Báo cáo</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td><a href="#" class="patient-stats-by-department">Thống kê bệnh nhân theo khoa khám bệnh</a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td><a href="#" class="stats-link stats-link--service">Thống kê bệnh nhân theo dịch vụ khám bệnh</a></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td><a href="" class="stats-link stats-link--doctor">Thống kê bệnh nhân theo bác sĩ khám bệnh</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- /.modal -->
    <div class="modal fade" id="patient-stats-by-department">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('booking.report.clinic') }}" method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title modal-title-report">Thống kê bệnh nhân theo khoa khám bệnh</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="clinic_id">Khoa khám bệnh <span class="text-danger">*</span></label>
                                    <select name="clinic_id" class="form-control" required>
                                        <option value="">Chọn khoa khám bệnh</option>
                                        @foreach($clinics as $key => $clinic)
                                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('clinic_id', '<span class="error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="start_date">Từ ngày <span class="text-danger">*</span></label>
                                    <input id="start_date" name="start_date" type="date" class="form-control" required>
                                    {!! $errors->first('start_date', '<span class="error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="end_date">Đến ngày <span class="text-danger">*</span></label>
                                    <input id="end_date" name="end_date" type="date" class="form-control" required>
                                    {!! $errors->first('end_date', '<span class="error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <select name="status" id="" class="form-control">
                                        <option value="">Trạng thái</option>
                                        @foreach($status as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Tạo báo cáo</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- /.modal -->
    <div class="modal fade" id="stats-link--service">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('booking.report.service') }}" method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title modal-title-report">Thống kê bệnh nhân theo dịch vụ khám bệnh</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="specialty_id">Dịch vụ khám bệnh <span class="text-danger">*</span></label>
                                    <select name="specialty_id" class="form-control" required>
                                        <option value="">Chọn dịch vụ khám bệnh</option>
                                        @foreach($specialties as $key => $specialty)
                                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('specialty_id', '<span class="error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="start_date">Từ ngày <span class="text-danger">*</span></label>
                                    <input id="start_date" name="start_date" type="date" class="form-control" required>
                                    {!! $errors->first('start_date', '<span class="error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="end_date">Đến ngày <span class="text-danger">*</span></label>
                                    <input id="end_date" name="end_date" type="date" class="form-control" required>
                                    {!! $errors->first('end_date', '<span class="error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <select name="status" id="" class="form-control">
                                        <option value="">Trạng thái</option>
                                        @foreach($status as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Tạo báo cáo</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- /.modal -->
    <div class="modal fade" id="stats-link--doctor">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('booking.report.doctor') }}" method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title modal-title-report">Thống kê bệnh nhân theo bác sĩ khám bệnh</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="doctor_id">Bác sĩ <span class="text-danger">*</span></label>
                                    <select name="doctor_id" class="form-control" required>
                                        <option value="">Chọn bác sĩ</option>
                                        @foreach($users as $key => $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('doctor_id', '<span class="error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="start_date">Từ ngày <span class="text-danger">*</span></label>
                                    <input id="start_date" name="start_date" type="date" class="form-control" required>
                                    {!! $errors->first('start_date', '<span class="error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="end_date">Đến ngày <span class="text-danger">*</span></label>
                                    <input id="end_date" name="end_date" type="date" class="form-control" required>
                                    {!! $errors->first('end_date', '<span class="error">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <select name="status" id="" class="form-control">
                                        <option value="">Trạng thái</option>
                                        @foreach($status as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Tạo báo cáo</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@stop

@section('script')
    <script>
        $(function () {
            $(".patient-stats-by-department").click(function (event) {
                event.preventDefault();
                $("#patient-stats-by-department").modal('show');
            });

            $(".stats-link--service").click(function (event) {
                event.preventDefault();
                $("#stats-link--service").modal('show');
            });

            $(".stats-link--doctor").click(function (event) {
                event.preventDefault();
                $("#stats-link--doctor").modal('show');
            });
        })
    </script>
@endsection