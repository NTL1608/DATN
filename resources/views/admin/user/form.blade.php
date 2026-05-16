<div class="container-fluid">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <div class="row">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName" class="control-label default">Họ và tên <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <input type="text" class="form-control" id="inputName" placeholder="Họ và tên" name="name" value="{{old('name', isset($user->name) ? $user->name : '')}}">
                                            <span class="text-danger ">
                                                <p class="mg-t-5">{{ $errors->first('name') }}</p>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputEmail" class="control-label default">Email <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email" value="{{old('email', isset($user->email) ? $user->email : '')}}">
                                            <span class="text-danger ">
                                                <p class="mg-t-5">{{ $errors->first('email') }}</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName2" class="control-label default"> Mật khẩu <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <input type="text" name="password" class="form-control" value="" id="exampleInputEmail1" placeholder="{{ isset($user) ? 'Vui lòng nhập mật khẩu nếu cần thay đổi' : 'Mật khẩu' }}">
                                        </div>
                                        <span class="text-danger">
                                            <p class="mg-t-5">{{ $errors->first('password') }}</p>
                                        </span>
                                    </div>
                                    <div class="form-group {{ $errors->has('role') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName2" class="control-label default">Vai trò <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <select name="role" class="form-control">
                                                <option value="">Chọn vai trò</option>
                                                @if($roles)
                                                @foreach($roles as $role)
                                                <option {{old('role', isset($listRoleUser->role_id) ? $listRoleUser->role_id : '') == $role->id ? 'selected=selected' : '' }} value="{{$role->id}}">{{$role->display_name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <span class="text-danger">
                                                <p class="mg-t-5">{{ $errors->first('role') }}</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName2" class="control-label default">Phone <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <input type="text" class="form-control" id="inputName2" placeholder="Phone" name="phone" value="{{old('phone', isset($user->phone) ? $user->phone : '')}}">
                                            <span class="text-danger ">
                                                <p class="mg-t-5">{{ $errors->first('phone') }}</p>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('position') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName2" class="control-label default">Chức danh <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            @php $userPosition = isset($user) ? explode(',', $user->position) : [] @endphp
                                            <select name="position[]" class="form-control select2" multiple>
                                                @foreach($positions as $key => $position)
                                                <option {{ in_array($key, $userPosition)  ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $position }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger ">
                                                <p class="mg-t-5">{{ $errors->first('position') }}</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group {{ $errors->has('clinic_id') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName" class="control-label default">Khoa khám bệnh<sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <select name="clinic_id" class="form-control" id="change-clinic">
                                                <option value="">Chọn khoa khám bệnh</option>
                                                @foreach($clinics as $key => $clinic)
                                                <option {{ isset($user) && $clinic->id === $user->clinic_id ? 'selected="selected"' : ''}} value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger ">
                                                <p class="mg-t-5">{{ $errors->first('clinic_id') }}</p>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('specialty_id') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName" class="control-label default">Dịch vụ <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <select name="specialty_ids[]" id="specialty_id" class="form-control select2" multiple>

                                                @if(isset($user))
                                                    @foreach($specialties as $key => $specialty)
                                                    <option {{ isset($user) && in_array($specialty->id, $arraySpecialty) ? 'selected="selected"' : ''}} value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="text-danger ">
                                                <p class="mg-t-5">{{ $errors->first('specialty_id') }}</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName" class="control-label default">Quê quán</label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="Địa chỉ" name="address" value="{{old('address', isset($user->address) ? $user->address : '')}}">
                                            <span class="text-danger ">
                                                <p class="mg-t-5">{{ $errors->first('address') }}</p>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName" class="control-label default">Giới tinh</label>
                                        <div>
                                            <select name="gender" class="form-control">
                                                @foreach($genders as $key => $gender)
                                                <option {{ isset($user) && $key === $user->gender ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $gender }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger ">
                                                <p class="mg-t-5">{{ $errors->first('gender') }}</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputName2" class="col-form-label">Giá khám min </label>
                                        <div>
                                            <input type="number" class="form-control" placeholder="Số tiền khám cho mỗi dịch vụ" name="price_min" value="{{old('price_min', isset($user->price_min) ? $user->price_min : '')}}">
                                            <span class="text-danger ">
                                                <p class="mg-t-5">{{ $errors->first('price_min') }}</p>
                                            </span>
                                        </div>
                                    </div>
                                    {{--<div class="form-group col-md-6">--}}
                                    {{--<label for="inputName2" class="col-form-label">Giá khám max  </label>--}}
                                    {{--<div>--}}
                                    {{--<input type="number" class="form-control" placeholder="Số tiền khám cho mỗi dịch vụ" name="price_max" value="{{old('price_max', isset($user->price_max) ? $user->price_max : '')}}">--}}
                                    {{--<span class="text-danger "><p class="mg-t-5">{{ $errors->first('price_max') }}</p></span>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="form-group col-md-6">
                                        <label for="inputName2" class="col-form-label">Ngày sinh </label>
                                        <div>
                                            <input type="date" class="form-control" placeholder="Địa chỉ" name="birthday" value="{{old('birthday', isset($user->birthday) ? $user->birthday : '')}}">
                                            <span class="text-danger ">
                                                <p class="mg-t-5">{{ $errors->first('birthday') }}</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group {{ $errors->first('description') ? 'has-error' : '' }} ">
                                        <label for="inputEmail3" class="control-label default">Mô tả ngắn </label>
                                        <div>
                                            <textarea name="description" id="description" cols="30" rows="10" class="form-control" style="height: 225px;">{{ old('description', isset($user) ? $user->description : '') }}</textarea>
                                            <script>
                                                ckeditor(description);
                                            </script>
                                            @if ($errors->first('description'))
                                            <span class="text-danger">{{ $errors->first('description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->first('contents') ? 'has-error' : '' }} ">
                                        <label for="inputEmail3" class="control-label default">Giới thiệu người dùng </label>
                                        <div>
                                            <textarea name="contents" id="contents" cols="30" rows="10" class="form-control" style="height: 225px;">{{ old('contents', isset($user) ? $user->contents : '') }}</textarea>
                                            <script>
                                                ckeditor(contents);
                                            </script>
                                            @if ($errors->first('contents'))
                                            <span class="text-danger">{{ $errors->first('contents') }}</span>
                                            @endif
                                        </div>
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
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Xuất bản </h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-set">
                            <button type="submit" name="submit" value="{{ isset($user) ? 'update' : 'create' }}" class="btn btn-info">
                                <i class="fa fa-save"></i> Lưu dữ liệu
                            </button>
                            <input type="hidden" value="1" name="type">
                            <button type="reset" name="reset" value="reset" class="btn btn-danger">
                                <i class="fa fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Profile Image -->
                <div class="card card-outline">
                    <div class="card-header">
                        <h3 class="card-title"> Thông tin </h3>
                    </div>
                    <div class="card-body box-profile">
                        <div class="form-group">
                            <label for="inputName2" class="col-form-label">Chức vụ </label>
                            <select name="job_title" class="form-control">
                                @foreach($jobTitle as $job => $job_title)
                                    <option {{ isset($user) && $job === $user->job_title ? 'selected="selected"' : ''}} value="{{ $job }}">{{ $job_title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inputName2" class="col-form-label">Kiểu người dùng </label>
                            <select name="type" class="form-control">
                                @foreach($types as $key => $type)
                                <option {{ isset($user) && $key === $user->type ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inputName2" class="col-form-label">Thành phố </label>
                            <div>
                                <select class="js-select2 form-control address" name="city_id" data-type="district">
                                    <option value="">Thành phố</option>
                                    @if (isset($citys) && !empty($citys))
                                    @foreach($citys as $city)
                                    <option value="{{ $city->id }}" {{ isset($user) && $city->id === $user->city_id ? 'selected="selected"' : ''}}>{{ $city->loc_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="text-danger ">
                                    <p class="mg-t-5">{{ $errors->first('city_id') }}</p>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName2" class="col-form-label">Tỉnh / Quận huyện </label>
                            <div>
                                <select class="js-select2 form-control address district " name="district_id" data-type="street">
                                    <option value="">Tỉnh / Quận huyện</option>
                                    @if (isset($district) && !empty($district))
                                    @foreach($district as $di)
                                    <option value="{{ $di->id }}" {{ isset($user) && $di->id === $user->district_id ? 'selected="selected"' : ''}}>{{ $di->loc_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="text-danger ">
                                    <p class="mg-t-5">{{ $errors->first('district_id') }}</p>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName2" class="col-form-label">Phường / Xã </label>
                            <div>
                                <select class="js-select2 form-control address street" name="street_id">
                                    <option value="">Phường / Xã</option>
                                    @if (isset($street) && !empty($street))
                                    @foreach($street as $st)
                                    <option value="{{ $st->id }}" {{ isset($user) && $st->id === $user->street_id ? 'selected="selected"' : ''}}>{{ $st->loc_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="text-danger ">
                                    <p class="mg-t-5">{{ $errors->first('street_id') }}</p>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputName2" class="col-form-label">Trạng thái </label>
                            <div class="col-sm-12">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="radio-status-1" name="status" value="1" @if (isset($user)) {{ isset($user) && $user->status == 1 ? 'checked' : '' }} @else checked @endif>
                                    <label for="radio-status-1">
                                        Hoạt động
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline" style="margin-left: 30px;">
                                    <input type="radio" id="radio-status-2" name="status" value="2" @if (isset($user)) {{ isset($user) && $user->status == 2 ? 'checked' : '' }} @endif>
                                    <label for="radio-status-2">
                                        Đã khóa
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Ảnh đại diện </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center" style="margin-top: 50px">
                            @if(isset($user) && !empty($user->avatar))
                            <img src="{{ asset(pare_url_file($user->avatar)) }}" alt="" class=" margin-auto-div img-rounded profile-user-img img-fluid img-circle" id="image_render" style="height: 150px; width:150px;">
                            @else
                            <img alt="" class="margin-auto-div img-rounded profile-user-img img-fluid img-circle" src="{{ asset('admin/dist/img/avatar5.png') }}" id="image_render" style="height: 150px; width:150px;">
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="input-group input-file" name="images">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-choose" type="button">Chọn tệp</button>
                                </span>
                                <input type="text" class="form-control" placeholder='Không có tệp nào ...' />
                                <span class="input-group-btn"></span>
                            </div>
                            <span class="text-danger ">
                                <p class="mg-t-5">{{ $errors->first('images') }}</p>
                            </span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>