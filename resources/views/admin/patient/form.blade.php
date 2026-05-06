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
                                        <label for="inputName" class="control-label default">Họ và tên  <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <input type="text" class="form-control" id="inputName" placeholder="Họ và tên" name="name" value="{{old('name', isset($user->name) ? $user->name : '')}}">
                                            <span class="text-danger "><p class="mg-t-5">{{ $errors->first('name') }}</p></span>
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputEmail" class="control-label default">Email  <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email" value="{{old('email', isset($user->email) ? $user->email : '')}}">
                                            <span class="text-danger "><p class="mg-t-5">{{ $errors->first('email') }}</p></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName2" class="control-label default"> Mật khẩu <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <input type="text" name="password" class="form-control" value="" id="exampleInputEmail1" placeholder="{{ isset($user) ? 'Vui lòng nhập mật khẩu nếu cần thay đổi' : 'Mật khẩu' }}">
                                        </div>
                                        <span class="text-danger"><p class="mg-t-5">{{ $errors->first('password') }}</p></span>
                                    </div>
                                    <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName2" class="control-label default">Phone  <sup class="title-sup">(*)</sup></label>
                                        <div>
                                            <input type="text" class="form-control" id="inputName2" placeholder="Phone" name="phone" value="{{old('phone', isset($user->phone) ? $user->phone : '')}}">
                                            <span class="text-danger "><p class="mg-t-5">{{ $errors->first('phone') }}</p></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName" class="control-label default">Địa chỉ</label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="Địa chỉ" name="address" value="{{old('address', isset($user->address) ? $user->address : '')}}">
                                            <span class="text-danger "><p class="mg-t-5">{{ $errors->first('address') }}</p></span>
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
                                            <span class="text-danger "><p class="mg-t-5">{{ $errors->first('gender') }}</p></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group {{ $errors->has('citizen_id_number') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName" class="control-label default">Căn cước công dân</label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="Căn cước công dân" name="citizen_id_number" value="{{old('citizen_id_number', isset($user->citizen_id_number) ? $user->citizen_id_number : '')}}">
                                            <span class="text-danger "><p class="mg-t-5">{{ $errors->first('citizen_id_number') }}</p></span>
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('insurance_card_number') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName" class="control-label default">Số thẻ bảo hiểm</label>
                                        <div>
                                            <input type="text" class="form-control" placeholder="Số thẻ bảo hiểm" name="insurance_card_number" value="{{old('insurance_card_number', isset($user->insurance_card_number) ? $user->insurance_card_number : '')}}">
                                            <span class="text-danger "><p class="mg-t-5">{{ $errors->first('insurance_card_number') }}</p></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group {{ $errors->has('birthday') ? 'has-error' : '' }} col-md-6">
                                        <label for="inputName" class="control-label default">Ngày sinh</label>
                                        <div>
                                            <input type="date" class="form-control" placeholder="Địa chỉ" name="birthday" value="{{old('birthday', isset($user->birthday) ? $user->birthday : '')}}">
                                            <span class="text-danger "><p class="mg-t-5">{{ $errors->first('birthday') }}</p></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName2" class="col-form-label">Trạng thái  </label>
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
                            <input type="hidden" value="2" name="type">
                            <button type="reset" name="reset" value="reset" class="btn btn-danger">
                                <i class="fa fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Ảnh đại diện </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center" style="margin-top: 50px">
                            @if(isset($user) && !empty($user->avatar))
                                <img src="{{ asset(pare_url_file($user->avatar)) }}" alt="" class=" margin-auto-div img-rounded profile-user-img img-fluid img-circle"  id="image_render" style="height: 150px; width:150px;">
                            @else
                                <img alt="" class="margin-auto-div img-rounded profile-user-img img-fluid img-circle" src="{{ asset('admin/dist/img/avatar5.png') }}" id="image_render" style="height: 150px; width:150px;">
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="input-group input-file" name="images">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Chọn tệp</button>
                                    </span>
                                <input type="text" class="form-control" placeholder='Không có tệp nào ...'/>
                                <span class="input-group-btn"></span>
                            </div>
                            <span class="text-danger "><p class="mg-t-5">{{ $errors->first('images') }}</p></span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
