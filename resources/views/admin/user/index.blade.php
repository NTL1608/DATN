@extends('admin.layouts.main')
@section('title', '')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"> <i class="nav-icon fas fa fa-home"></i> Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Người dùng</a></li>
                    <li class="breadcrumb-item active">Danh sách</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
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
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <input type="text" name="user_code" class="form-control mg-r-15" placeholder="Mã bác sĩ">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control mg-r-15" placeholder="Tên bác sĩ">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
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
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <select name="status" id="" class="form-control">
                                        <option value="">Trạng thái</option>
                                        <option value="1">Hoạt động</option>
                                        <option value="2">Đã khóa</option>
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
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools">
                            <div class="btn-group">
                                <a href="{{ route('user.create') }}"><button type="button" class="btn btn-block btn-info"><i class="fa fa-plus"></i> Tạo mới</button></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th width="4%" class=" text-center">STT</th>
                                    <th>Mã bác sĩ</th>
                                    <th>Họ tên</th>
                                    <th>Khoa khám bệnh</th>
                                    <th>Dịch vụ</th>
                                    <th>Giới tính</th>
                                    <th>Chức danh</th>
                                    <th>Chức vụ</th>
                                    <th>Vai trò</th>
                                    <th>Trạng thái</th>
                                    <th class=" text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$users->isEmpty())
                                @php $i = $users->firstItem(); @endphp
                                @foreach($users as $user)
                                <tr>
                                    <td style="vertical-align: middle" class=" text-center">{{ $i }}</td>
                                    <td style="vertical-align: middle"><a href="{{ route('user.show', ['user_id' => $user->id]) }}">{{ $user->user_code }}</a></td>
                                    <td style="vertical-align: middle">{{ $user->name }}</td>
                                    <td style="vertical-align: middle">{{ isset($user->clinic) ? $user->clinic->name : '' }}</td>
                                    <td style="vertical-align: middle">
                                        @foreach($user->specialties as $specialty)
                                        <p>{{ $specialty->name }}</p>
                                        @endforeach
                                    </td>
                                    <td style="vertical-align: middle">{{ isset($genders[$user->gender]) ? $genders[$user->gender] : '' }}</td>
                                    <td style="vertical-align: middle">
                                        @php $userPosition = !empty($user->position) ? explode(',', $user->position) : [] @endphp
                                        {{ implode(', ', array_map(fn($position) => $positions[$position] ?? '', $userPosition)) }}
                                    </td>
                                    <td style="vertical-align: middle">{{ isset($jobTitle[$user->job_title]) ? $jobTitle[$user->job_title] : '' }}</td>
                                    <td style="vertical-align: middle">
                                        @if($user->userRole != null)
                                        @foreach($user->userRole as $role)
                                        <span class="label label-success">{{$role->display_name}}</span>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle">{{ isset($status[$user->status]) ? $status[$user->status] : '' }}</td>
                                    <td class="text-center" style="vertical-align: middle">
                                        <a class="btn btn-primary btn-sm" href="{{ route('user.update', $user->id) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm btn-delete btn-confirm-delete" href="{{ route('user.delete', $user->id) }}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php $i++ @endphp
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if($users->hasPages())
                        <div class="pagination float-right margin-20">
                            {{ $users->appends($query = '')->links() }}
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