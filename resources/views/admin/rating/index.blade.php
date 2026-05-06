@extends('admin.layouts.main')
@section('title', '')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"> <i class="nav-icon fas fa fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('rating.index') }}">Đánh giá</a></li>
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

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th width="4%" class=" text-center">STT</th>
                                        <th>Bác sĩ</th>
                                        <th>Bệnh nhân</th>
                                        <th>Điểm đánh giá</th>
                                        <th>Nội dung</th>
                                        <th class=" text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$ratings->isEmpty())
                                        @php $i = $ratings->firstItem(); @endphp
                                        @foreach($ratings as $rating)
                                            <tr>
                                                <td class=" text-center" style="vertical-align: middle;">{{ $i }}</td>
                                                <td style="vertical-align: middle;">{{ isset($rating->doctor) ? $rating->doctor->name : '' }}</td>
                                                <td style="vertical-align: middle;">{{ isset($rating->patient) ? $rating->patient->name : '' }}</td>
                                                <td style="vertical-align: middle;">{{ $rating->star }} / 5 </td>
                                                <td style="vertical-align: middle;">{{ $rating->content }} </td>
                                                <td class="text-center" style="vertical-align: middle;">
                                                    <a class="btn btn-danger btn-sm btn-delete btn-confirm-delete" href="{{ route('rating.delete', $rating->id) }}">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @php $i++ @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            @if($ratings->hasPages())
                                <div class="pagination float-right margin-20">
                                    {{ $ratings->appends($query = '')->links() }}
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
