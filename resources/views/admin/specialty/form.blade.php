<div class="container-fluid">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card card-primary">
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6 {{ $errors->first('name') ? 'has-error' : '' }} ">
                                <label for="inputEmail3" class="control-label default">Tên dịch vụ <sup class="text-danger">(*)</sup></label>
                                <div>
                                    <input type="text" class="form-control"  placeholder="Tên dịch vụ" name="name" value="{{ old('name',isset($specialty) ? $specialty->name : '') }}">
                                    <span class="text-danger"><p class="mg-t-5">{{ $errors->first('name') }}</p></span>
                                </div>
                            </div>

                            <div class="form-group col-md-6 {{ $errors->first('price') ? 'has-error' : '' }} ">
                                <label for="inputEmail3" class="control-label default">Giá dịch vụ</label>
                                <div>
                                    <input type="number" class="form-control"  placeholder="Giá dịch vụ" name="price" value="{{ old('price',isset($specialty) ? $specialty->price : '') }}">
                                    <span class="text-danger"><p class="mg-t-5">{{ $errors->first('price') }}</p></span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group {{ $errors->first('description') ? 'has-error' : '' }} ">
                            <label for="inputEmail3" class="control-label default">Mô tả </label>
                            <div>
                                <textarea name="description" id="description" cols="30" rows="10" class="form-control" style="height: 225px;">{{ old('description', isset($specialty) ? $specialty->description : '') }}</textarea>
                                <script>
                                    ckeditor(description);
                                </script>
                                @if ($errors->first('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<label>Trạng thái</label>--}}
                            {{--<select class="form-control" name="active">--}}
                                {{--@foreach($active as $key => $value)--}}
                                    {{--<option {{ isset($specialty) && $key === $specialty->active ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $value }}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}

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
                            @if(isset($specialty) && !empty($specialty->image))
                                <img src="{{ asset(pare_url_file($specialty->image)) }}" alt="" class="margin-auto-div img-rounded"  id="image_render" style="height: 150px; width:100%;">
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
