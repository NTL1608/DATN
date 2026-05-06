<div class="container-fluid">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card card-primary">
                    <!-- form start -->
                    <div class="card-body">
                        <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }} ">
                            <label for="inputEmail3" class="control-label default">Tên khoa khám bệnh <sup class="text-danger">(*)</sup></label>
                            <div>
                                <input type="text" class="form-control"  placeholder="Tên khoa khám bệnh " name="name" value="{{ old('name',isset($clinic) ? $clinic->name : '') }}">
                                <span class="text-danger"><p class="mg-t-5">{{ $errors->first('name') }}</p></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group {{ $errors->first('email') ? 'has-error' : '' }} col-md-6">
                                <label for="inputEmail3" class="control-label default">Email </label>
                                <div>
                                    <input type="email" class="form-control"  placeholder="Email " name="email" value="{{ old('email',isset($clinic) ? $clinic->email : '') }}">
                                    <span class="text-danger"><p class="mg-t-5">{{ $errors->first('email') }}</p></span>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('phone') ? 'has-error' : '' }} col-md-6">
                                <label for="inputEmail3" class="control-label default">Phone </label>
                                <input type="text" class="form-control"  placeholder="Phone" name="phone" value="{{ old('phone',isset($clinic) ? $clinic->phone : '') }}">
                                <span class="text-danger"><p class="mg-t-5">{{ $errors->first('phone') }}</p></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group {{ $errors->first('address') ? 'has-error' : '' }} col-md-6">
                                <label for="inputEmail3" class="control-label default">Địa chỉ </label>
                                <input type="text" class="form-control"  placeholder="Địa chỉ " name="address" value="{{ old('address',isset($clinic) ? $clinic->address : '') }}">
                                <span class="text-danger"><p class="mg-t-5">{{ $errors->first('address') }}</p></span>
                            </div>
                            <div class="form-group {{ $errors->first('address') ? 'has-error' : '' }} col-md-6">
                                <label for="inputEmail3" class="control-label default">Link website</label>
                                <input type="text" class="form-control"  placeholder="Link website " name="link_website" value="{{ old('link_website',isset($clinic) ? $clinic->link_website : '') }}">
                                <span class="text-danger"><p class="mg-t-5">{{ $errors->first('link_website') }}</p></span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->first('specialties') ? 'has-error' : '' }}">
                            <label for="inputEmail3" class="control-label default">Dịch vụ </label>
                            @php
                                $arraySpecialtyIds = old('specialties', isset($activeSpecialtyIds) ? $activeSpecialtyIds : []);
                            @endphp
                            <select name="specialties[]" id="" class="form-control select2" multiple>
                                <option value="">Chọn dịch vụ</option>
                                @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" {{ in_array($specialty->id, $arraySpecialtyIds) ? 'selected' : '' }}>{{ $specialty->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger"><p class="mg-t-5">{{ $errors->first('specialties') }}</p></span>
                        </div>

                        <div class="form-group {{ $errors->first('description') ? 'has-error' : '' }} ">
                            <label for="inputEmail3" class="control-label default">Mô tả ngắn </label>
                            <div>
                                <textarea name="description" id="description" cols="4" rows="4" class="form-control" style="height: 60px;" placeholder="Mô tả ngắn khoảng 70 ký tự">{{ old('description', isset($clinic) ? $clinic->description : '') }}</textarea>
                                @if ($errors->first('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->first('contents') ? 'has-error' : '' }} ">
                            <label for="inputEmail3" class="control-label default">Giới thiệu khoa khám bệnh</label>
                            <div>
                                <textarea name="contents" id="contents" cols="30" rows="10" class="form-control" style="height: 225px;">{{ old('contents', isset($clinic) ? $clinic->contents : '') }}</textarea>
                                <script>
                                    ckeditor(contents);
                                </script>
                                @if ($errors->first('contents'))
                                    <span class="text-danger">{{ $errors->first('contents') }}</span>
                                @endif
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<label>Trạng thái</label>--}}
                            {{--<select class="form-control" name="active">--}}
                                {{--@foreach($active as $key => $value)--}}
                                    {{--<option {{ isset($clinic) && $key === $clinic->active ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $value }}</option>--}}
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
                            @if(isset($clinic) && !empty($clinic->logo))
                                <img src="{{ asset(pare_url_file($clinic->logo)) }}" alt="" class="margin-auto-div img-rounded"  id="image_render" style="height: 150px; width:100%;">
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
