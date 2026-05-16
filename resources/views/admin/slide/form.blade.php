<div class="container-fluid">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card card-primary">
                    <!-- form start -->
                    <div class="card-body">
                        <div class="form-group {{ $errors->first('title') ? 'has-error' : '' }} ">
                            <label for="inputEmail3" class="control-label default">Tiêu đề <sup class="text-danger">(*)</sup></label>
                            <div>
                                <input type="text" class="form-control"  placeholder="Tiêu đề " name="title" value="{{ old('title',isset($slide) ? $slide->title : '') }}">
                                <span class="text-danger"><p class="mg-t-5">{{ $errors->first('title') }}</p></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Sắp xếp <sup class="text-danger">(*)</sup></label>
                            <input type="number" maxlength="100" class="form-control"  placeholder="Sắp xếp" name="sort" value="{{ old('sort',isset($slide) ? $slide->sort : '') }}">
                            <span class="text-danger"><p class="mg-t-5">{{ $errors->first('sort') }}</p></span>
                        </div>
                        <div class="form-group">
                            <label>Link tới trang khác</label>
                            <input type="text" class="form-control"  placeholder="Link đến trang khác " name="link" value="{{ old('link',isset($slide) ? $slide->link : '') }}">
                            <span class="text-danger"><p class="mg-t-5">{{ $errors->first('link') }}</p></span>
                        </div>
                        <div class="form-group">
                            <label>Kiểu link</label>
                            <select class="form-control" name="target">
                                @foreach($target as $key => $value)
                                    <option {{ isset($slide) && $key === $slide->target ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select class="form-control" name="active">
                                @foreach($active as $key => $value)
                                    <option {{ isset($slide) && $key === $slide->active ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Xuất bản </h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-set">
                            <button type="submit" name="submit" class="btn btn-info">
                                <i class="fa fa-save"></i> Lưu dữ liệu
                            </button>
                            <button type="reset" name="reset" value="reset" class="btn btn-danger">
                                <i class="fa fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                    <div class="card-header">
                        <h3 class="card-title">Ảnh </h3>
                    </div>
                    <div class="card-body" style="min-height: 288px">
                        <div class="form-group">
                            <div class="input-group input-file" name="image">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-choose" type="button">Chọn tệp</button>
                                </span>
                                <input type="text" class="form-control" placeholder='Không có tệp nào ...'/>
                                <span class="input-group-btn"></span>
                            </div>
                            <span class="text-danger "><p class="mg-t-5">{{ $errors->first('image') }}</p></span>
                            @if(isset($slide) && !empty($slide->image))
                                <img src="{{ asset(pare_url_file($slide->image)) }}" alt="" class="margin-auto-div img-rounded"  id="image_render" style="height: 150px; width:100%;">
                            @else
                                <img src="{{ asset('admin/dist/img/no-image.png') }}" alt="" class="margin-auto-div img-rounded"  id="image_render" style="height: 150px; width:100%;">
                            @endif
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </form>
</div>
