<link href="{{ asset('css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
<link href="{{ asset('css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">

<div class="body">
    <table>
        <thead>
            <tr>
                <td colspan="8"></td>
            </tr>
            <tr>
                <th style="width: 50px;"></th>
                <th colspan="7" style="width: 100%">
                    <h3 style="margin-left: 50px; font-family: 'Courier New', Courier, monospace; font-weight: bold">BỆNH VIỆN TRUNG ƯƠNG Hà Nội</h3>
                </th>
            </tr>
            <tr>
                <th style="width: 50px;"></th>
                <th colspan="7">
                    <p style="margin-left: 50px; font-family: 'Courier New', Courier, monospace">Địa chỉ : Số 9, Phố Viên, Phường Đông Ngạc, Thành phố Hà Nội, Việt Nam</p>
                </th>
            </tr>
            <tr>
                <th style="width: 50px;"></th>
                <th colspan="7">
                    <p style="margin-left: 50px; font-family: 'Courier New', Courier, monospace">Điện thoại: 19001806</p>
                </th>
            </tr>
            <tr>
                <th style="width: 50px;"></th>
                <th colspan="7">
                    <p style="margin-left: 50px; font-family: 'Courier New', Courier, monospace">Email: benhvientutn@gmail.com</p>
                </th>
            </tr>
        </thead>
    </table>
</div>
<div class="row">
    <div class="col-md-12" style="text-align: center">
        <table>
            <thead>
                <tr>
                    <th colspan="7" style="width: 100%; text-align: center; ">
                        <h3 style="font-family: 'Courier New', Courier, monospace; font-weight: bold"><b>THỐNG KÊ BỆNH NHÂN THEO KHOA KHÁM BỆNH</b></h3>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-md-12" style="text-align: center">
        <table class="table" style="border: 1px solid #000000;">
            <thead style="border: 1px solid #000000;">
                <tr>
                    <th style="width: 50px;"></th>
                    <th colspan="3"><span>Khoa khám bệnh: {{ $data['clinic'] }}</span></th>
                    <th colspan="3" style="text-align: right"><span>Từ ngày: {{ $data['start_date'] }}</span></th>
                    <th colspan="3"><span>Đến ngày: {{ $data['end_date'] }}</span></th>
                </tr>
                <tr>
                    <td colspan="7"></td>
                </tr>
                <tr style="border: 1px solid">
                    <th style="width: 30px; text-align: center; border: 1px solid #000000;"><b>STT</b></th>
                    <th style="width: 30px; text-align: center; border: 1px solid #000000;"><b>Mã bệnh nhân</b></th>
                    <th style="width: 200px; border: 1px solid #000000;"><b>Bệnh nhân</b></th>
                    <th style="width: 200px; border: 1px solid #000000;"><b>Bác sĩ khám</b></th>
                    <th style="width: 150px; border: 1px solid #000000;"><b>Email</b></th>
                    <th style="width: 150px; border: 1px solid #000000;"><b>Số điện thoại</b></th>
                    <th style="width: 150px; border: 1px solid #000000;"><b>CCCD</b></th>
                    <th style="width: 150px; border: 1px solid #000000;"><b>Số thẻ BH</b></th>
                    <th style="width: 300px; border: 1px solid #000000;"><b>Ngày sinh</b></th>
                    <th style="width: 150px; border: 1px solid #000000;"><b>Giới tính</b></th>
                    <th style="width: 150px; border: 1px solid #000000;"><b>Ngày đặt</b></th>
                    <th style="width: 150px; border: 1px solid #000000;"><b>Giờ đặt</b></th>
                    <th style="width: 150px; border: 1px solid #000000;"><b>Giá</b></th>
                    <th style="width: 150px; border: 1px solid #000000;"><b>Trạng thái</b></th>
                    <th style="width: 150px; border: 1px solid #000000;"><b>Ngày tạo</b></th>
                </tr>
                @php ($stt = 1)
                @if ($patients)
                @foreach ($patients as $patient)
                <tr style="border: 1px solid #000000;">
                    <td style="width: 30px; text-align: center;border: 1px solid #000000;">{{ $stt++ }}</td>
                    <td style="width: 150px; border: 1px solid #000000;">{{ isset($patient->patient) ? $patient->patient->user_code : '' }}</td>
                    <td style="width: 150px; border: 1px solid #000000;">{{ $patient->name }}</td>
                    <td style="width: 150px; border: 1px solid #000000;">{{ isset($patient->doctor) ? $patient->doctor->name : ''  }}</td>
                    <td style="width: 300px; border: 1px solid #000000;">{!! $patient->email !!}</td>
                    <td style="width: 300px; border: 1px solid #000000;">{!! $patient->phone !!}</td>
                    <td style="width: 300px; border: 1px solid #000000; mso-number-format: '@';">{!! isset($patient->patient) ? $patient->patient->citizen_id_number : '' !!}</td>
                    <td style="width: 300px; border: 1px solid #000000; mso-number-format: '@';">{!! isset($patient->patient) ? $patient->patient->insurance_card_number : '' !!}</td>
                    <td style="width: 300px; border: 1px solid #000000;">{!! $patient->birthday !!}</td>
                    <td style="width: 300px; border: 1px solid #000000;">{!! $genders[$patient->gender] ?? '' !!}</td>
                    <td style="width: 300px; border: 1px solid #000000;">{!! $patient->date_booking ?? '' !!}</td>
                    <td style="width: 300px; border: 1px solid #000000;">{!! $patient->time_booking ?? '' !!}</td>
                    <td style="width: 300px; border: 1px solid #000000;">{!! number_format($patient->price) . 'đ' ?? '' !!}</td>
                    <td style="width: 300px; border: 1px solid #000000;">{!! $status[$patient->status] ?? '' !!}</td>
                    <td style="width: 150px; border: 1px solid #000000;">{{ date("d-m-Y", strtotime($patient->created_at)) }}</td>
                </tr>
                @endforeach
                @endif
                <tr>
                    <td colspan="7"></td>
                </tr>
            </thead>
        </table>
    </div>
</div>
