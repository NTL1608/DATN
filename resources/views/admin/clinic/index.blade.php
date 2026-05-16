@extends('admin.layouts.main')
@section('title', '')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"> <i class="nav-icon fas fa fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clinic.index') }}">Khoa khám bệnh</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
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
                                    <input type="text" name="name" class="form-control mg-r-15" placeholder="Tên khoa khám bệnh">
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <div class="btn-group">
                                    <a href="{{ route('clinic.create') }}"><button type="button" class="btn btn-block btn-info"><i class="fa fa-plus"></i> Tạo mới</button></a>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th width="4%" class=" text-center">STT</th>
                                        <th style="width: 20%; text-align: center;">Ảnh</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Địa chỉ</th>
                                        <th>Website</th>
                                        <th class=" text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$clinics->isEmpty())
                                        @php $i = $clinics->firstItem(); @endphp
                                        @foreach($clinics as $clinic)
                                            <tr>
                                                <td class=" text-center" style="vertical-align: middle;">{{ $i }}</td>
                                                <td style="vertical-align: middle; width: 20%; text-align: center;">
                                                    <div class="product-img">
                                                        <img src="{{ !empty($clinic->logo) ? asset(pare_url_file($clinic->logo)) : asset('admin/dist/img/no-image.png') }}" alt="Product Image" class="img-size-150">
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <a href="{{ route('clinic.show', $clinic->id) }}">{{ $clinic->name }}</a>
                                                </td>
                                                <td style="vertical-align: middle;">{{ $clinic->email }}</td>
                                                <td style="vertical-align: middle;">{{ $clinic->phone }}</td>
                                                <td style="vertical-align: middle;">{{ $clinic->address }}</td>
                                                <td style="vertical-align: middle;">
                                                    @if(!empty($clinic->link_website))
                                                    <a href="{{ $clinic->link_website }}">Website</a>
                                                    @endif
                                                </td>

                                                <td class="text-center" style="vertical-align: middle;">
                                                    <a class="btn btn-primary btn-sm" href="{{ route('clinic.update', $clinic->id) }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm btn-delete btn-confirm-delete" href="{{ route('clinic.delete', $clinic->id) }}">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @php $i++ @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            @if($clinics->hasPages())
                                <div class="pagination float-right margin-20">
                                    {{ $clinics->appends($query = '')->links() }}
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
