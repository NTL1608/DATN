@extends('admin.layouts.main')
@section('title', '')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"> <i class="nav-icon fas fa fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('slide.index') }}">Banner</a></li>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <div class="btn-group">
                                    <a href="{{ route('slide.create') }}"><button type="button" class="btn btn-block btn-info"><i class="fa fa-plus"></i> Tạo mới</button></a>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th width="4%" class=" text-center">STT</th>
                                        <th>Ảnh</th>
                                        <th>Title</th>
                                        <th>Target</th>
                                        <th>Sắp xếp</th>
                                        <th>Trạng thái</th>
                                        <th class=" text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$slides->isEmpty())
                                        @php $i = $slides->firstItem(); @endphp
                                        @foreach($slides as $slide)
                                            <tr>
                                                <td class=" text-center" style="vertical-align: middle;">{{ $i }}</td>
                                                <td style="vertical-align: middle;">
                                                    <div class="slide-img" style="background-image: url('{{ !empty($slide->image) ? asset(pare_url_file($slide->image)) : asset('admin/dist/img/no-image.png') }}')">
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle;">{{ $slide->title }}</td>
                                                <td style="vertical-align: middle;">
                                                    {{ isset($target[$slide->target]) ? $target[$slide->target] : '' }}
                                                </td>
                                                <td  style="vertical-align: middle;">
                                                    {{ $slide->sort }}
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    {{ isset($active[$slide->active]) ? $active[$slide->active] : '' }}
                                                </td>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    <a class="btn btn-primary btn-sm" href="{{ route('slide.update', $slide->id) }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm btn-delete btn-confirm-delete" href="{{ route('slide.delete', $slide->id) }}">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @php $i++ @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            @if($slides->hasPages())
                                <div class="pagination float-right margin-20">
                                    {{ $slides->appends($query = '')->links() }}
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
