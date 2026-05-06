@extends('admin.layouts.main')
@section('title', 'Thông tin tài khoản')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"> <i class="nav-icon fas fa fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="">Thông tin tài khoản</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form role="form" action="{{ route('profile.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    @if(isset($user) && !empty($user->avatar))
                                        <img src="{{ asset(pare_url_file($user->avatar)) }}" alt="" class=" margin-auto-div img-rounded profile-user-img img-fluid img-circle"  id="image_render" style="height: 150px; width:150px;">
                                    @else
                                        <img alt="" class="margin-auto-div img-rounded profile-user-img img-fluid img-circle" src="{{ asset('admin/dist/img/avatar5.png') }}" id="image_render" style="height: 150px; width:150px;">
                                    @endif
                                </div>
                                @if (isset($user->name))
                                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                                @endif
                                @if (isset($user->email))
                                    <p class="text-muted text-center">{{ $user->email }}</p>
                                @endif
                                @if (isset($user->phone))
                                    <p class="text-muted text-center">{{ $user->phone }}</p>
                                @endif
                                @if (isset($user->userRole))
                                    <p class="text-muted text-center">{{ isset($user->userRole[0]) ? $user->userRole[0]->display_name : '' }}</p>
                                @endif
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                <?php //dd($errors) ?>
                <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="settings">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Họ và tên :</label>
                                                    <div class="col-sm-9">
                                                        <span>{{ isset($user->name) ? $user->name : '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Email :</label>
                                                    <div class="col-sm-9">
                                                        <span>{{ isset($user->email) ? $user->email : '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Phone :</label>
                                                    <div class="col-sm-9">
                                                        <span>{{ isset($user->phone) ? $user->phone : '' }}</span>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Ngày sinh :</label>
                                                    <div class="col-sm-9">
                                                        <span>{{ isset($user->birthday) ? date('Y-m-d',strtotime($user->birthday)) : '' }}</span>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Quê quán :</label>
                                                    <div class="col-sm-9">
                                                        <span>{{ isset($user->address) ? $user->address : '' }}</span>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Giới tính :</label>
                                                    <div class="col-sm-9">
                                                        <span>{{ isset($user->gender) && ($user->gender == 1) ?  'Nam' : 'Nữ' }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Địa chỉ :</label>
                                                    <div class="col-sm-9">
                                                        <span>
                                                            {{ isset($user->city) ? $user->city->loc_name. '-' : '' }}
                                                            {{ isset($user->district) ? $user->district->loc_name. '-' : '' }}
                                                            {{ isset($user->street) ? $user->street->loc_name. ' ' : '' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Khoa KB :</label>
                                                    <div class="col-sm-9">
                                                        <span>{{ isset($user->clinic) ? $user->clinic->name : '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Dịch vụ :</label>
                                                    <div class="col-sm-9">
                                                        @foreach($user->specialties as $specialty)
                                                            <p>{{ $specialty->name }}</p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Vai trò :</label>
                                                    <div class="col-sm-9">
                                                        <span>{{ isset($user->userRole[0]) ? $user->userRole[0]->display_name : '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Chức vụ :</label>
                                                    <div class="col-sm-9">
                                                        <span>{{ isset($jobTitle[$user->job_title]) ? $jobTitle[$user->job_title] : '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Chức danh :</label>
                                                    <div class="col-sm-9">
                                                        <span>
                                                            @php $userPosition = !empty($user->position) ? explode(',', $user->position) : [] @endphp
                                                            {{ implode(', ', array_map(fn($position) => $positions[$position] ?? '', $userPosition)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="control-label col-sm-3 default">Giá khám :</label>
                                                    <div class="col-sm-9">
                                                        <span>{{ isset($user->price_min) ? number_format($user->price_min) . 'đ' : '' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 default">
                                                <label class="control-label default">Mô tả ngắn :</label>
                                            </div>
                                            <div class="col-sm-12 default">
                                                <p>{!! isset($user) ? $user->description : '' !!}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 default">
                                                <label class="control-label default">Giới thiệu :</label>
                                            </div>
                                            <div class="col-sm-12 default">
                                                <p>{!! isset($user) ? $user->contents : '' !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
    </section>
@stop