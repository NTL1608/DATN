<div class="container-fluid">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <div class="card card-primary">
                    <!-- form start -->
                    <div class="card-body">
                        @if ($users)
                        <div class="form-group {{ $errors->first('doctor_id') ? 'has-error' : '' }} ">
                            <label for="inputEmail3" class="control-label default">Bác sĩ <sup class="text-danger">(*)</sup></label>
                            <div>
                                <select name="doctor_id" class="form-control select2" required>
                                    <option value="">Chọn bác sĩ</option>
                                    @foreach($users as $key => $user)
                                    <option {{ isset($schedule) && $schedule->doctor_id === $user->id ? 'selected="selected"' : ''}} value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger ">
                                    <p class="mg-t-5">{{ $errors->first('doctor_id') }}</p>
                                </span>
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="doctor_id" value="{{ $user->id }}">
                        @endif
                        <div class="row">
                            <div class="form-group {{ $errors->first('date_schedule') ? 'has-error' : '' }} col-md-6">
                                <label for="inputEmail3" class="control-label default">Ngày khám <sup class="text-danger">(*)</sup></label>
                                <input type="date" class="form-control" name="date_schedule" @if(isset($schedule)) {{ (strtotime($schedule->date_schedule) - strtotime(date('Y-m-d'))) < 0 ? 'disabled' : '' }} @endif value="{{ isset($schedule) ? $schedule->date_schedule : date('Y-m-d') }}">
                                <span class="text-danger ">
                                    <p class="mg-t-5">{{ $errors->first('date_schedule') }}</p>
                                </span>
                            </div>
                            <div class="form-group {{ $errors->first('jump') ? 'has-error' : '' }} col-md-6">
                                <label for="inputEmail3" class="control-label default">Khoảng thời gian <sup class="text-danger">(*)</sup></label>
                                <div>
                                    <select name="jump" class="form-control jump_time">
                                        <option value="">Chọn khoảng thời gian</option>
                                        @foreach($jumps as $key => $jump)
                                        <option {{ isset($schedule) && $schedule->jump === $key ? 'selected="selected"' : ''}} value="{{ $key }}">{{ $jump }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger ">
                                        <p class="mg-t-5">{{ $errors->first('jump') }}</p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->first('max_number') ? 'has-error' : '' }} ">
                            <label for="inputEmail3" class="control-label default">Số bệnh nhân đăng ký tối đa trong khoảng thời gian <sup class="text-danger">(*)</sup></label>
                            <div>
                                <input type="number" class="form-control" name="max_number" value="{{ old('', isset($schedule) ? $schedule->max_number : 1) }}">
                                <span class="text-danger ">
                                    <p class="mg-t-5">{{ $errors->first('max_number') }}</p>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Lịch làm việc</label>

                            <div class="row btn-check-role">
                                <div class="col-md-2">
                                    <a class="btn btn-block btn-success checkAll" href="#" type="checkbox" title="Chọn tất cả" onclick="$('.schedule_all').prop('checked', true);return false;">
                                        <i class="fa fa-check-square-o" aria-hidden="true"></i> Chọn tất cả
                                    </a>
                                </div>
                                <div class="col-md-2" style="margin-left: 20px;">
                                    <a class="btn btn-block btn-primary canceAll" href="#" type="checkbox" title="Hủy tất cả" onclick="$('.schedule_all').prop('checked', false);return false;">
                                        <i class="fa fa-times" aria-hidden="true"></i> Hủy tất cả
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-12 schedule_time" style="padding: 0px;">
                                @if(isset($schedule))
                                <div class="row content-role default">
                                    @if (isset($list_times[$schedule->jump]))
                                    @foreach($list_times[$schedule->jump] as $time)
                                    <div class="col-md-2 role-item">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" class="schedule_all {{safeTitle($time)}}"
                                                {{ isset($listTimes) && in_array($time, $listTimes) ? 'checked' : '' }}
                                                value="{{$time}}" name="list_times[]" id="checkbox{{ safeTitle($time) }}">
                                            <label for="checkbox{{ safeTitle($time) }}">
                                                {{$time}}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Xuất bản</h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-set">
                            <button type="submit" name="submit" value="{{ isset($schedule) ? 'update' : 'create' }}" class="btn btn-info">
                                <i class="fa fa-save"></i> Lưu dữ liệu
                            </button>
                            <button type="reset" name="reset" value="reset" class="btn btn-danger">
                                <i class="fa fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-header">
                        <h3 class="card-title"> Trạng thái </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <select class="custom-select" name="status">
                                @foreach($status as $key => $item)
                                <option value="{{ $key }}" {{ old('status',isset($schedule) ? $schedule->status : '') == $key ? "selected='selected'" : "" }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>