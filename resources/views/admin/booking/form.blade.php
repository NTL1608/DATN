<div class="container-fluid">
    <form role="form" action="{{ route('result.booking', $booking->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <div class="form-group {{ $errors->first('file_result') ? 'has-error' : '' }} ">
                                    <label for="inputEmail3" class="control-label default">Kết quả khám</label>
                                    <div>
                                        <input type="file" class="form-control" name="file_result">
                                        <input type="hidden" name="status" value="5">
                                        <span class="text-danger">
                                            <p class="mg-t-5">{{ $errors->first('file_result') }}</p>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->first('instruction') ? 'has-error' : '' }} ">
                                    <label for="inputEmail3" class="control-label default">Kết luận khám bệnh của bác sĩ<sup class="text-danger">(*)</sup></label>
                                    <div>
                                        <textarea name="instruction" id="instruction" cols="4" rows="4" class="form-control" style="height: 60px;" placeholder="Ghi chú kết quả khám">{{ old('instruction', isset($booking) ? $booking->instruction : '') }}</textarea>
                                        <script>
                                            ckeditor(instruction);
                                        </script>
                                        @if ($errors->first('instruction'))
                                        <span class="text-danger">{{ $errors->first('instruction') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->first('note') ? 'has-error' : '' }} ">
                                    <label for="inputEmail3" class="control-label default">Hướng dẫn điều trị và lời dặn của bác sĩ</label>
                                    <div>
                                        <textarea name="note" id="note" cols="4" rows="4" class="form-control" style="height: 60px;" placeholder="Ghi chú kết quả khám">{{ old('note', isset($booking) ? $booking->note : '') }}</textarea>
                                        <script>
                                            ckeditor(note);
                                        </script>
                                        @if ($errors->first('note'))
                                        <span class="text-danger">{{ $errors->first('note') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </form>
</div>