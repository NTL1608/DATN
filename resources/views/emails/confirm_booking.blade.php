<style>
    table > thead > tr {
        border: 1px solid;
    }
    table > thead > tr > th {
        border: 1px solid;
    }
    table > tbody > tr {
        border: 1px solid;
    }
    table > tbody > tr > td {
        border: 1px solid;
    }
</style>
<div style="width: 100%;max-width: 800px;margin:0 auto">

    <div style="background: white;padding: 15px;border:1px solid #dedede;">
        <h2 style="margin:10px 0;border-bottom: 1px solid #dedede;padding-bottom: 10px;">Cám ơn bạn đã đặt lịch khám tại hệ thống của Bệnh viện Đa khoa Phương Đông </h2>
        <div>
            <h2>Xin chào bệnh nhân: {{ $data['name'] }}<b></b></h2>
        </div>
        <div>
            <b>Thông tin cho cuộc hẹn đã đặt</b>
            <p>Mã phiếu khám : {{ $data['booking_code'] }}</p>
            <p>Tên bác sĩ: {{ $data['name_doctor'] }}</p>
            <p>Dịch vụ: {{ $data['specialty'] }}</p>
            <p>Thời gian: {{ $data['time_booking'] }}</p>
            <p>Ngày: {{ $data['date_booking'] }}</p>
            <p>Giá tiền  : {{ number_format($data['price'],0,',','.') }} vnđ </p>
            <p>Trạng thái : {{ $data['status'] }}</p>
            <p>Số thứ tự khám : {{ $data['number'] }}</p>
            @if ($data['confirm'])
                <p>Vui lòng kích vào link xác nhận : <a href="{{ route('booking.confirm', $data['id']) }}">Xác nhận </a></p>
            @else
                <p>Hệ thống sẽ tự động gửi email thông báo cuộc hẹn được xác nhận hoàn tất. Cám ơn bạn !</p>
            @endif

            @if(isset($data['qr_code']))
                <div style="margin-top: 20px; text-align: center; padding: 20px; background: #f9f9f9;">
                    <p style="margin-bottom: 15px; font-weight: bold; color: #333; font-size: 16px;">📎 QR Code thông tin lịch khám (đính kèm file)</p>
                    <p style="font-size: 12px; color: #666; margin-top: 10px;">Vui lòng tải file đính kèm <strong>qrcode.svg</strong> và quét mã QR</p>
                    <p style="margin-top: 5px; font-size: 11px; color: #999;">Hoặc truy cập: <a href="{{ route('booking.print.medical.exam.form', $data['id']) }}" style="color: #4CAF50;">Xem phiếu khám</a></p>
                </div>
            @endif

            <p>Đây là email tự động xin vui không không trả lời vào email này</p>
            <b>Trân trọng cảm ơn !</b>
        </div>
    </div>
    <div style="background: #f4f5f5;box-sizing: border-box;padding: 15px">
        <p style="margin:2px 0;color: #333">Email : </p>
        <p style="margin:2px 0;color: #333">Phone : </p>
        <p style="margin:2px 0;color: #333">Face : <a href=""></a></p>
    </div>
</div>
