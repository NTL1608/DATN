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
        <h2 style="margin:10px 0;border-bottom: 1px solid #dedede;padding-bottom: 10px;">Cám ơn bạn đã khám chữa bệnh tại Bệnh viện Đa khoa Phương Đông </h2>
        <div>
            <h2>Xin chào bệnh nhân: {{ $data['name'] }}<b></b></h2>
        </div>
        <div>
            <b>Sau khi bạn đã khám bệnh bác sĩ {{ $data['name_doctor'] }}, bạn có thể xem lại kết quả khám chi kết tại file đính kèm</b>
            <p>Trạng thái : {{ $data['status'] }}</p>
            @if(!empty($data['instruction']))
                <h4>Kết luận khám bệnh của bác sĩ : </h4>
                {!! $data['instruction'] !!}
            @endif
            @if(!empty($data['instruction']))
                <h4>Hướng dẫn điều trị và lời dặn của bác sĩ : </h4>
                {!! $data['note'] !!}
            @endif
            @if(!empty($data['file_result']))
                <p><a href="{{ convertUrl(asset('uploads/file-result/'. $data['file_result'])) }}">File đính kèm</a></p>
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
