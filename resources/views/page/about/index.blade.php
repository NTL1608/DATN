@extends('page.layouts.page')
@section('title', 'Giới thiệu về chúng tôi')
@section('style')
<style>
    .about-section { margin-bottom: 40px; }
    .about-section h3 { color: #2c7be5; border-left: 4px solid #2c7be5; padding-left: 12px; margin-bottom: 15px; }
    .value-box { background: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 20px; text-align: center; }
    .value-box i { font-size: 36px; color: #2c7be5; margin-bottom: 10px; }
    .achievement-box { background: #2c7be5; color: white; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 20px; }
    .achievement-box h2 { font-size: 40px; font-weight: bold; margin: 0; }
    .leader-card { text-align: center; margin-bottom: 30px; }
    .leader-card img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; border: 3px solid #2c7be5; }
    .timeline { position: relative; padding-left: 30px; border-left: 3px solid #2c7be5; }
    .timeline-item { margin-bottom: 25px; position: relative; }
    .timeline-item::before { content: ''; width: 14px; height: 14px; background: #2c7be5; border-radius: 50%; position: absolute; left: -38px; top: 5px; }
    .timeline-year { font-weight: bold; color: #2c7be5; font-size: 16px; }
</style>
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Giới thiệu';
        $description = 'Quá trình phát triển và hình thành của chúng tôi';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))

    <section class="service-section spad">
        <div class="container">

            {{-- Giới thiệu chung --}}
            <div class="about-section">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3>Về Bệnh viện Đa khoa Phương Đông</h3>
                        <p>Bệnh viện Đa khoa Phương Đông là một trong những cơ sở y tế uy tín hàng đầu tại Hà Nội, được thành lập với sứ mệnh mang đến dịch vụ chăm sóc sức khỏe chất lượng cao, toàn diện và nhân văn cho người dân.</p>
                        <p>Tọa lạc tại Số 9, Phố Viên, Phường Đông Ngạc, Thành phố Hà Nội, bệnh viện được trang bị hệ thống máy móc thiết bị y tế hiện đại, đội ngũ bác sĩ chuyên khoa giỏi và giàu kinh nghiệm.</p>
                        <p>Với phương châm <strong>"Tận tâm – Chuyên nghiệp – Hiệu quả"</strong>, chúng tôi cam kết luôn đặt lợi ích và sức khỏe của bệnh nhân lên hàng đầu.</p>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="{{ asset('page/img/images (1).png') }}" alt="Bệnh viện Đa khoa Phương Đông" style="max-width: 300px;">
                    </div>
                </div>
            </div>

            {{-- Tầm nhìn - Sứ mệnh - Giá trị --}}
            <div class="about-section">
                <h3>Tầm nhìn – Sứ mệnh – Giá trị cốt lõi</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="value-box">
                            <i class="fa fa-eye"></i>
                            <h5>Tầm nhìn</h5>
                            <p>Trở thành bệnh viện đa khoa hàng đầu khu vực phía Bắc, được người dân tin tưởng và lựa chọn.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="value-box">
                            <i class="fa fa-heart"></i>
                            <h5>Sứ mệnh</h5>
                            <p>Mang đến dịch vụ y tế chất lượng cao, toàn diện và nhân văn, vì sức khỏe cộng đồng.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="value-box">
                            <i class="fa fa-star"></i>
                            <h5>Giá trị cốt lõi</h5>
                            <p>Tận tâm – Trung thực – Chuyên nghiệp – Đổi mới – Trách nhiệm với cộng đồng.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thành tích --}}
            <div class="about-section">
                <h3>Thành tích nổi bật</h3>
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="achievement-box">
                            <h2>15+</h2>
                            <p class="mb-0">Năm hoạt động</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="achievement-box">
                            <h2>50+</h2>
                            <p class="mb-0">Bác sĩ chuyên khoa</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="achievement-box">
                            <h2>20+</h2>
                            <p class="mb-0">Chuyên khoa</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="achievement-box">
                            <h2>100K+</h2>
                            <p class="mb-0">Bệnh nhân tin tưởng</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lịch sử hình thành --}}
            <div class="about-section">
                <h3>Lịch sử hình thành & Phát triển</h3>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-year">2008</div>
                        <p>Bệnh viện Đa khoa Phương Đông chính thức được thành lập với quy mô ban đầu gồm 5 khoa lâm sàng và 100 giường bệnh.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2012</div>
                        <p>Mở rộng thêm các khoa chuyên sâu: Tim mạch, Thần kinh, Tiêu hóa. Đầu tư hệ thống máy móc hiện đại phục vụ chẩn đoán hình ảnh.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2016</div>
                        <p>Được Bộ Y tế công nhận đạt tiêu chuẩn chất lượng bệnh viện hạng II. Triển khai hệ thống đặt lịch khám trực tuyến.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2020</div>
                        <p>Nâng cấp cơ sở vật chất, mở rộng khu khám chữa bệnh theo yêu cầu. Đưa vào hoạt động hệ thống phòng mổ hiện đại.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2024</div>
                        <p>Ra mắt hệ thống đặt lịch khám và tư vấn trực tuyến tích hợp trí tuệ nhân tạo, phục vụ hơn 100.000 bệnh nhân mỗi năm.</p>
                    </div>
                </div>
            </div>

            {{-- Chứng nhận --}}
            <div class="about-section">
                <h3>Chứng nhận & Giải thưởng</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="value-box">
                            <i class="fa fa-certificate"></i>
                            <h6>Bệnh viện chất lượng cao</h6>
                            <p>Được Bộ Y tế công nhận đạt tiêu chuẩn chất lượng bệnh viện hạng II năm 2016.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="value-box">
                            <i class="fa fa-trophy"></i>
                            <h6>Top bệnh viện uy tín</h6>
                            <p>Lọt vào top 10 bệnh viện tư nhân uy tín nhất khu vực Hà Nội năm 2022.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="value-box">
                            <i class="fa fa-shield"></i>
                            <h6>ISO 9001:2015</h6>
                            <p>Đạt chứng nhận ISO 9001:2015 về hệ thống quản lý chất lượng trong y tế.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@stop
@section('script')
@stop
