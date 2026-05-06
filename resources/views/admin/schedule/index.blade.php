@extends('admin.layouts.main')
@section('title', '')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"> <i class="nav-icon fas fa fa-home"></i> Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('schedule.index') }}">Lịch làm việc</a></li>
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
                                @if($user->hasRole(['super-admin']))
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <input type="text" name="user_code" class="form-control mg-r-15" placeholder="Mã bác sĩ">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control mg-r-15" placeholder="Tên bác sĩ">
                                    </div>
                                </div>
                                @endif
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <input type="date" name="to_date_schedule" class="form-control mg-r-15">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <input type="date" name="from_date_schedule" class="form-control mg-r-15">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <div class="form-group">
                                        <select name="clinic_id" id="" class="form-control">
                                            <option value="">Khoa khám bệnh</option>
                                            @foreach($clinics as $clinic)
                                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                            @endforeach
                                        </select>
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
                    <div class="card-header">
                        <div class="card-tools">
                            <div class="btn-group">
                                <a href="{{ route('schedule.create') }}"><button type="button" class="btn btn-block btn-info"><i class="fa fa-plus"></i> Tạo mới</button></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th width="4%" class=" text-center">STT</th>
                                    <th>Mã bác sĩ</th>
                                    <th>Tên bác sĩ</th>
                                    <th>Khoa khám bệnh</th>
                                    <th>Ngày đăng ký</th>
                                    <th width="30%">Lịch làm việc</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$schedules->isEmpty())
                                @php $i = $schedules->firstItem(); @endphp
                                @foreach($schedules as $schedule)
                                <tr>
                                    <td class=" text-center" style="vertical-align: middle">{{ $i }}</td>
                                    <td style="vertical-align: middle">{{isset($schedule->doctor)? $schedule->doctor->user_code : ''}}</td>
                                    <td style="vertical-align: middle">{{isset($schedule->doctor)? $schedule->doctor->name : ''}}</td>
                                    <td style="vertical-align: middle">{{isset($schedule->doctor)? $schedule->doctor->clinic->name : ''}}</td>
                                    <td style="vertical-align: middle"><span class="label label-success">{{$schedule->date_schedule}}</span></td>
                                    <td width="30%" style="vertical-align: middle">
                                        @if(isset($schedule->times))
                                        @foreach($schedule->times as $time)
                                        <small class="badge badge-primary">{{$time->time_schedule}}</small>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle">
                                        <button type="button" class="btn btn-block {{ isset($class_status[$schedule->status]) ? $class_status[$schedule->status] : '' }} btn-xs">{{ isset($status[$schedule->status]) ? $status[$schedule->status] : '' }}</button>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle">
                                        <a class="btn btn-primary btn-sm" href="{{ route('schedule.update', $schedule->id) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm btn-delete btn-confirm-delete" href="{{ route('schedule.delete', $schedule->id) }}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php $i++ @endphp
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if($schedules->hasPages())
                        <div class="pagination float-right margin-20">
                            {{ $schedules->appends($query = '')->links() }}
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