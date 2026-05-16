@extends('admin.layouts.main')
@section('title', '')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"> <i class="nav-icon fas fa fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('contact.index') }}">Liên hệ</a></li>
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
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-bordered">
                                <thead>
                                <tr>
                                    <th width="4%" class=" text-center">STT</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Nội dung</th>
                                    <th>Ngày tạo</th>
                                    <th class=" text-center">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!$contacts->isEmpty())
                                    @php $i = $contacts->firstItem(); @endphp
                                    @foreach($contacts as $contact)
                                        <tr>
                                            <td class=" text-center" style="vertical-align: middle;">{{ $i }}</td>
                                            <td style="vertical-align: middle;">{{ $contact->name }}</td>
                                            <td style="vertical-align: middle;">{{ $contact->email }}</td>
                                            <td style="vertical-align: middle;">{{ $contact->phone }}</td>
                                            <td style="vertical-align: middle;">{{ $contact->message }}</td>
                                            <td style="vertical-align: middle;">{{ date('Y-m-d H:i', strtotime($contact->created_at)) }}</td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                <a class="btn btn-danger btn-sm btn-delete btn-confirm-delete" href="{{ route('contact.delete', $contact->id) }}">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php $i++ @endphp
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            @if($contacts->hasPages())
                                <div class="pagination float-right margin-20">
                                    {{ $contacts->appends($query = '')->links() }}
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
