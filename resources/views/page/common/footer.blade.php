<!-- Footer Section -->
<footer class="footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="footer-widget">
                    <div class="about-widget">
                        <img src="{{ asset('page/img/images (1).png') }}" alt="">
                        <p>{{ config('setting.introduction') }}</p>
                        <ul>
                            <li><i class="material-icons">phone</i>{{ config('setting.phone') }}</li>
                            <li><i class="material-icons">email</i>{{ config('setting.email') }}</li>
                            <li><i class="material-icons">map</i>{{ config('setting.address') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="footer-widget pl-0 pl-lg-5">
                    <h2 class="fw-title">Menu</h2>
                    <ul>
                        <li><a href="{{ route('user.home.index') }}">Trang chủ</a></li>
                        <li><a href="{{ route('page.about') }}">Giới thiệu</a></li>
                        <li><a href="{{ route('page.clinic.index') }}">Khoa khám bệnh</a></li>
                        <li><a href="{{ route('page.specialty.index') }}">Dịch vụ</a></li>
                        <li><a href="{{ route('page.article.index') }}">Tin tức</a></li>
                        <li><a href="{{ route('page.contact') }}">Liên hệ</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-6">
                <div class="footer-widget">
                    <h2 class="fw-title">Dịch vụ</h2>
                    <ul>
                        @foreach($specialties as $key => $specialty)
                            @if($key < 6 )
                        <li><a href="{{ route('specialty.detail', ['id' => $specialty->id, 'slug' => safeTitle($specialty->name)]) }}">{{ $specialty->name }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="footer-widget pl-0 pl-lg-5">
                    <h2 class="fw-title">Thời gian làm việc</h2>
                    <ul>
                        <li><i class="material-icons">alarm_on</i>{{ config('setting.alarm_on_mon_fri') }}</li>
                        <li><i class="material-icons">alarm_on</i>{{ config('setting.alarm_on_sat_sun') }}</li>
                    </ul>
                    {{--<form class="infor-form">--}}
                        {{--<input type="text" placeholder="Your Email">--}}
                        {{--<button><img src="{{ asset('page/img/icons/send.png') }}" alt=""></button>--}}
                    {{--</form>--}}
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-md-8 text-md-right">
                    <div class="copyright"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                       <b>Đồ án tốt nghiệp - Nguyễn Thành Luân</b>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section end -->

<div class="chat-widget">
    <div class="chat-header">
        <h3>Chat Tư Vấn</h3>
        <button class="close-button">&times;</button>
    </div>
    <div id="chat-container" class="chat-container"></div>
    <div class="chat-input">
        <input type="text" id="message-input" placeholder="Nhập tin nhắn của bạn...">
        <button id="send-button">Gửi</button>
    </div>
</div>

<div class="back-to-top"><img src="{{ asset('page/img/icons/up-arrow.png') }}" alt=""></div>

<!-- Search model -->
<div class="search-model set-bg" data-setbg="img/search-bg.jpg">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="search-close-switch"><i class="material-icons">close</i></div>
        <form class="search-moderl-form" action="{{ route('page.search') }}">
            <input type="text" id="search-input" name="keyword" placeholder="Tên bác sĩ">
            <button><img src="{{ asset('page/img/icons/search-2.png') }}" alt=""></button>
        </form>
    </div>
</div>
<!-- Search model end -->

<!--====== Javascripts & Jquery ======-->
<script src="{{ asset('page/js/vendor/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('page/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('page/js/jquery.slicknav.min.js') }}"></script>
<script src="{{ asset('page/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('page/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('page/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('page/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('page/js/main.js') }}"></script>
<script src="{{ asset('page/plugin/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/chat.js') }}"></script>
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    })

    @if(session('success'))
        var message = "{{ session('success') }}";
        @if(session('qrCode'))
            // Lấy QR code URL từ PHP (có thể là URL hoặc data URI)
            var qrCodeSrc = {!! json_encode(session('qrCode')) !!};
            console.log('QR Code:', qrCodeSrc ? 'Có dữ liệu' : 'Không có dữ liệu');

            // Tạo element chứa message và QR code
            var contentDiv = document.createElement('div');
            contentDiv.style.textAlign = 'center';

            var messageP = document.createElement('p');
            messageP.textContent = message;
            messageP.style.marginBottom = '20px';
            messageP.style.fontSize = '16px';

            // Tạo QR code image
            var qrImg = document.createElement('img');
            qrImg.src = qrCodeSrc;
            qrImg.style.maxWidth = '150px';
            qrImg.style.width = '100%';
            qrImg.style.height = 'auto';
            qrImg.style.margin = '0 auto';
            qrImg.style.display = 'block';
            qrImg.style.border = '2px solid #ddd';
            qrImg.style.padding = '10px';
            qrImg.style.borderRadius = '8px';
            qrImg.style.backgroundColor = '#fff';

            var noteP = document.createElement('p');
            noteP.textContent = 'Quét mã QR để xem thông tin lịch khám';
            noteP.style.marginTop = '15px';
            noteP.style.fontSize = '14px';
            noteP.style.color = '#666';

            // Tạo nút tải QR code
            var downloadBtn = document.createElement('a');
            downloadBtn.href = qrCodeSrc;

            // Tạo tên file với ngày giờ
            var now = new Date();
            var dateStr = now.getFullYear() + '-' +
                         String(now.getMonth() + 1).padStart(2, '0') + '-' +
                         String(now.getDate()).padStart(2, '0') + '-' +
                         String(now.getHours()).padStart(2, '0') + '-' +
                         String(now.getMinutes()).padStart(2, '0') + '-' +
                         String(now.getSeconds()).padStart(2, '0');
            downloadBtn.download = 'QR-Code-Lich-Kham-' + dateStr + '.svg';

            downloadBtn.textContent = 'Tải mã QR';
            downloadBtn.style.display = 'inline-block';
            downloadBtn.style.marginTop = '15px';
            downloadBtn.style.padding = '10px 20px';
            downloadBtn.style.backgroundColor = '#4CAF50';
            downloadBtn.style.color = '#fff';
            downloadBtn.style.textDecoration = 'none';
            downloadBtn.style.borderRadius = '5px';
            downloadBtn.style.fontSize = '14px';
            downloadBtn.style.fontWeight = 'bold';
            downloadBtn.style.cursor = 'pointer';
            downloadBtn.style.transition = 'background-color 0.3s';
            downloadBtn.onmouseover = function() {
                this.style.backgroundColor = '#45a049';
            };
            downloadBtn.onmouseout = function() {
                this.style.backgroundColor = '#4CAF50';
            };

            contentDiv.appendChild(messageP);
            contentDiv.appendChild(qrImg);
            contentDiv.appendChild(noteP);
            contentDiv.appendChild(downloadBtn);

            swal({
                title: "Thông báo",
                content: contentDiv,
                icon: "success",
                button: "Đóng"
            });
        @else
            swal("Thông báo", message, "success");
        @endif
    @endif
    @if(session('error'))
        var message = "{{ session('error') }}";
        swal("Thông báo", message, "error");
    @endif
</script>

@yield('script')
