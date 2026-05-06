<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Phiếu Khám Bệnh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 30px;
        }

        .card {
            padding: 30px;
            max-width: 90%;
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
            .col-md-3 img {
                max-width: 70px !important;
                height: auto !important;
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

            p {
                margin: 3px 0 !important;
                font-size: 11px !important;
                color: black !important;
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
            }

            .schedule h4 {
                font-size: 13px !important;
                margin-bottom: 5px !important;
            }

            .schedule p {
                font-size: 11px !important;
                line-height: 1.3 !important;
            }

            /* Footer */
            .footer {
                margin-top: 25px !important;
                text-align: center !important;
                font-size: 11px !important;
                margin-right: 0 !important;
                page-break-inside: avoid !important;
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

            /* Đảm bảo nội dung vừa trang A4 */
            .container {
                min-height: auto !important;
                max-height: 270mm !important;
            }

            /* Title cho print */
            .print-title {
                font-size: 16px !important;
                font-weight: bold !important;
                text-transform: uppercase !important;
                margin: 15px 0 !important;
                text-align: center !important;
            }

            /* Responsive cho các cột */
            @media print {
                .col-md-6 {
                    width: 50% !important;
                    float: left !important;
                }

                .col-md-3 {
                    width: 25% !important;
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
            }

            /* Tối ưu cho in */
            .patient-sign,
            .signature-full {
                height: 20px !important;
                margin: 10px 0 !important;
            }
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
            margin-right: 15%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card shadow">
            <div class="row">
                <div class="col-md-3">
                    <img src="{{ asset('admin/dist/img/images (1).png') }}" alt="Logo/>
                </div>
                <div class="col-md-6 text-center">
                    <h4>Bệnh viện Đa khoa Phương Đông</h4>
                    <h5 style="text-transform: uppercase">{{ isset($booking->doctor->clinic) ? $booking->doctor->clinic->name : '' }}</h5>
                    <p style="font-size: 13px; margin-bottom: 0;">Địa chỉ: Số 9, Phố Viên, Phường Đông Ngạc, Thành phố Hà Nội, Việt Nam</p>
                    <p style="font-size: 13px;">Điện thoại: 19001806| Website: www.benhvientutn.online</p>
                </div>
                <div class="col-md-3 text-end barcode">

                    <p class="mb-0">Mã bệnh nhân: <strong>{{ $booking->patient->user_code }}</strong></p>
                    <p>Mã phiếu hẹn: <strong>{{ $booking->booking_code }}</strong></p>
                    <img src="{{ $booking->qr_code }}" alt="Barcode" />
                </div>
            </div>

            <h4 class="text-center mt-3 print-title" style="margin-bottom: 25px">PHIẾU KẾT QUẢ KHÁM BỆNH</h4>

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
                    <p><strong>Thanh toán:</strong> <span class="highlight">Đã thanh toán</span></p>
                    <p><strong>Khám chức danh:</strong> {{ isset($booking->doctor) ? $jobTitle[$booking->doctor->job_title] : '' }}</p>
                    <p><b>Khoa khám bệnh: </b> {{ $booking->doctor->clinic->name }}</p>
                    <p><strong>Bác sĩ khám:</strong> {{ $booking->doctor->name }}</p>
                </div>
            </div>
            @if(!empty($booking->instruction))
            <div class="schedule">
                <h4>Kết luận khám bệnh của bác sĩ: </h4>
                <p style="font-family: Sans-Serif; font-size: 13px">{!! $booking->instruction !!}</p>
            </div>
            @endif
            @if(!empty($booking->note))
            <div class="schedule">
                <h4>Hướng dẫn điều trị và lời dặn của bác sĩ: </h4>
                <p>{!! $booking->note !!}</p>
            </div>
            @endif
            <div class="patient-sign">

            </div>
            <div class="signature-full">

            </div>

            <div class="footer">
                <p><strong>BÁC SĨ KHÁM BỆNH</strong></p>
                <br><br><br>
            </div>

            <div class="row no-print" style="margin-top: 20px;">
                <div class="col-xs-12">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fa fa-print"></i> In phiếu kết quả khám bệnh
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
