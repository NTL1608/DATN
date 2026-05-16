<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Phiếu Đăng Ký Khám Chữa Bệnh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 30px;
        }

        .card {
            padding: 30px;
            max-width: 70%;
            margin: 0 auto;
        }

        .barcode img {
            height: 50px;
        }

        .highlight {
            color: red;
            font-weight: bold;
        }

        .schedule {
            font-size: 18px;
        }

        .footer-note {
            font-size: 13px;
            color: red;
            margin-top: 20px;
        }

        .section-title {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            margin: 20px 0 10px;
        }

        .patient-sign {
            text-align: center;
            font-weight: bold;
        }

        .signature-full {
            width: 100%;
        }

        .signature {
            margin-top: 40px;
            width: 300px;
            text-align: center;
            float: right;
            right: 0px;
        }

        /* Responsive cho Mobile */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .card {
                padding: 15px;
                max-width: 100% !important;
                margin: 0 auto;
            }

            h4.print-title {
                font-size: 16px !important;
            }

            h5 {
                font-size: 13px !important;
            }

            h6 {
                font-size: 12px !important;
            }

            p {
                font-size: 13px !important;
                margin: 5px 0 !important;
            }

            .barcode img {
                height: 40px;
                max-width: 100%;
            }

            .schedule {
                font-size: 14px !important;
            }

            .schedule p {
                font-size: 13px !important;
            }

            .signature {
                width: 100%;
                float: none;
                margin-top: 20px;
                text-align: center;
            }

            .patient-sign {
                margin: 20px 0;
            }

            .footer-note {
                font-size: 12px;
                line-height: 1.5;
            }

            /* Đảm bảo các cột stack trên mobile */
            .col-md-6, .col-md-8, .col-md-4 {
                width: 100% !important;
                margin-bottom: 10px;
            }

            .text-end {
                text-align: center !important;
            }

            /* Button in */
            .btn {
                width: 100%;
                padding: 12px;
                font-size: 16px;
            }
        }

        /* Tablet */
        @media (min-width: 769px) and (max-width: 1024px) {
            .card {
                max-width: 90%;
            }

            p {
                font-size: 14px;
            }
        }

        @media print {

            /* Ẩn các phần tử không cần in */
            .no-print {
                display: none !important;
            }

            /* Reset toàn bộ layout cho print */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            /* Thiết lập trang A4 */
            @page {
                size: A4;
                margin: 15mm 10mm 15mm 10mm;
            }

            /* Reset body cho print */
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                font-family: "Times New Roman", serif !important;
                font-size: 12px !important;
                line-height: 1.4 !important;
                color: black !important;
            }

            /* Container và card cho print */
            .container {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .card {
                padding: 15px !important;
                max-width: 100% !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
                background: white !important;
            }

            /* Header logo và thông tin */
            .col-md-8,
            .col-md-4 {
                padding: 0 8px !important;
            }

            .barcode img {
                height: 35px !important;
                max-width: 100px !important;
            }

            /* Typography cho print */
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                color: black !important;
                margin: 8px 0 !important;
            }

            h4 {
                font-size: 14px !important;
                font-weight: bold !important;
            }

            h5,
            h6 {
                font-size: 12px !important;
                font-weight: bold !important;
            }

            p {
                margin: 3px 0 !important;
                font-size: 11px !important;
                color: black !important;
            }

            .small {
                font-size: 10px !important;
            }

            strong,
            b {
                font-weight: bold !important;
                color: black !important;
            }

            /* Thông tin bệnh nhân */
            .row {
                margin: 0 !important;
            }

            .col-md-6,
            .col-md-3 {
                padding: 0 8px !important;
            }

            /* Phần schedule và note */
            .schedule {
                font-size: 12px !important;
                margin: 10px 0 !important;
                text-align: center !important;
            }

            .schedule p {
                font-size: 11px !important;
                line-height: 1.3 !important;
            }

            /* Footer và signature */
            .footer-note {
                font-size: 10px !important;
                margin-top: 15px !important;
                color: black !important;
                line-height: 1.3 !important;
            }

            .patient-sign {
                font-size: 11px !important;
                margin: 15px 0 !important;
            }

            .signature {
                font-size: 11px !important;
                margin-top: 20px !important;
                width: 200px !important;
            }

            /* Highlight color cho print */
            .highlight {
                color: black !important;
                font-weight: bold !important;
            }

            /* Đảm bảo không bị cắt trang */
            .card {
                page-break-inside: avoid !important;
            }

            /* Spacing tối ưu cho A4 */
            .mt-3 {
                margin-top: 10px !important;
            }

            .mb-1 {
                margin-bottom: 3px !important;
            }

            .mb-3 {
                margin-bottom: 10px !important;
            }

            /* Đảm bảo nội dung vừa trang A4 */
            .container {
                min-height: auto !important;
                max-height: 270mm !important;
            }

            /* Responsive cho các cột */
            .col-md-6 {
                width: 50% !important;
                float: left !important;
            }

            .col-md-8 {
                width: 66.67% !important;
                float: left !important;
            }

            .col-md-4 {
                width: 33.33% !important;
                float: left !important;
            }

            .text-center {
                text-align: center !important;
            }

            .text-end {
                text-align: right !important;
            }

            /* Clearfix cho row */
            .row:after {
                content: "";
                display: table;
                clear: both;
            }

            /* Section title */
            .section-title {
                font-size: 14px !important;
                margin: 15px 0 8px !important;
                text-align: center !important;
            }

            /* Signature full */
            .signature-full {
                width: 100% !important;
                clear: both !important;
            }

            /* Title cho print */
            .print-title {
                font-size: 16px !important;
                font-weight: bold !important;
                text-transform: uppercase !important;
                margin: 15px 0 !important;
                text-align: center !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card shadow">
            <div class="row">
                <div class="col-md-8">
                    <div style="text-align: left; margin-left: 10px;">
                        <div style="display: inline-block; text-align: center;">
                            <h5 class="mb-1" style="font-weight: bold; font-size: 15px;">SỞ Y TẾ TỈNH Hà Nội</h5>
                            <h6 class="mb-0" style="font-weight: bold; font-size: 14px;">Bệnh viện Đa khoa Phương Đông</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end barcode">
                    <img src="https://barcode.tec-it.com/barcode.ashx?data=080801267457&code=Code128&translate-esc=true" alt="Barcode" />
                    <p class="mb-0">Mã bệnh nhân: <strong>{{ $booking->patient->user_code }}</strong></p>
                    <p>Mã phiếu hẹn: <strong>{{ $booking->booking_code }}</strong></p>
                </div>
            </div>
            <h4 class="text-center mt-3 mb-1 print-title">PHIẾU ĐĂNG KÝ KHÁM CHỮA BỆNH</h4>
            <p class="text-center mb-3">Ngày {{ \Carbon\Carbon::parse($booking->date_booking)->format('d') }} tháng {{ \Carbon\Carbon::parse($booking->date_booking)->format('m') }} năm {{ \Carbon\Carbon::parse($booking->date_booking)->format('Y') }}
                <br><span class="highlight">{{ isset($status[$booking->status]) ? $status[$booking->status] : '' }}</span>
            </p>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Họ và tên:</strong> {{ $booking->name }}</p>
                    <p><strong>CCCD:</strong> {{ $booking->citizen_id_number }}</p>
                    <p><strong>Ngày sinh:</strong> {{ $booking->birthday }}</p>
                    <p><strong>Phone:</strong> {{ $booking->phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $booking->address }}</p>
                    <p><strong>Tiền khám:</strong> <span class="highlight">{{ number_format($booking->price) }} VNĐ</span></p>
                    <p><strong>Dịch vụ:</strong> Khám yêu cầu @if($booking->specialty) ({{ $booking->specialty->name }}) @endif</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Giới tính:</strong> {{ isset($gender[$booking->patient->gender]) ? $gender[$booking->patient->gender] : '' }}</p>
                    <p><strong>Số thẻ bảo hiểm:</strong> {{ $booking->insurance_card_number }}</p>
                    <p><strong>Email:</strong> {{ $booking->email }}</p>
                    <p><strong>Thanh toán:</strong>
                        @if($booking->status >= 3 && $booking->status != 6)
                            <span class="highlight" style="color: #28a745; font-weight: bold;">✓ Đã thanh toán</span>
                        @else
                            <span style="color: #dc3545; font-weight: bold;">✗ Chưa thanh toán</span>
                        @endif
                    </p>
                    <p><strong>Khám chức danh:</strong> {{ isset($booking->doctor) ? $jobTitle[$booking->doctor->job_title] : '' }}</p>
                    <p><b>Khoa khám bệnh: </b> {{ $booking->doctor->clinic->name }}</p>
                    <p><strong>Bác sĩ khám:</strong> {{ $booking->doctor->name }}</p>
                </div>
            </div>

            <div class="schedule text-center">
                <p><strong>{{ isset($positions[$booking->doctor->position]) ? $positions[$booking->doctor->position] : '' }}</strong></p>
                <p>Ngày khám: <strong>{{ $booking->date_booking }}</strong> (Ca Sáng từ {{ $booking->time_booking }})</p>
                <p>Số thứ tự khám: <strong>{{ $booking->number }}</strong></p>
            </div>

            <div class="patient-sign">
                Bệnh nhân<br /><br />
                <strong>{{ $booking->name }}</strong>
            </div>
            <div class="signature-full">
                <div class="signature">
                    <p>Ngày {{ \Carbon\Carbon::parse($booking->date_booking)->format('d') }} tháng {{ \Carbon\Carbon::parse($booking->date_booking)->format('m') }} năm {{ \Carbon\Carbon::parse($booking->date_booking)->format('Y') }}</p>
                    <p>Người thu</p>
                    <p>(ký và ghi rõ họ tên)</p>
                </div>
            </div>

            <div class="footer-note">
                Khi đến khám hãy mang theo phiếu hẹn này. Nếu cần hướng dẫn hãy đưa phiếu khám này cho quầy lễ tân để được hỗ trợ.<br />
                Phiếu khám chưa thanh toán, khi đến khám bạn hãy mang phiếu khám này qua quầy thu ngân để nộp tiền phí khám.<br />
                Trường hợp quý khách cần hỗ trợ thêm, xin vui lòng liên hệ đến số Hotline: 1900.6951
            </div>

            <div class="row no-print" style="margin-top: 20px;">
                <div class="col-xs-12">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fa fa-print"></i> In hóa đơn
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
