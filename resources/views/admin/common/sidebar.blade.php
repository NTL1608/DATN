<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.home') }}" class="brand-link navbar-info">
        <img src="{!! asset('admin/dist/img/AdminLTELogo.png') !!}"
             alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Quản trị website</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        @php
            $user = Auth::user();
        @endphp
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(isset($user) && !empty($user->avatar))
                    <img src="{{ asset(pare_url_file($user->avatar)) }}" alt="" class="img-circle elevation-2" style="width: 50px; height: 50px;">
                @else
                    <img src="{{ asset('/admin/dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="#" class="d-block">{!! $user->name !!}</a>
        </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                @if($user->can(['toan-quyen-quan-ly', 'truy-cap-he-thong']))
                <li class="nav-item has-treeview">
                    <a href="{{ route('admin.home') }}" class="nav-link">
                        <i class="nav-icon fas fa fa-home"></i>
                        <p>Bảng điều khiển</p>
                    </a>
                </li>
                @endif
                @if($user->can(['toan-quyen-quan-ly', 'danh-sach-phong-kham']))
                <li class="nav-item">
                    <a href="{{ route('clinic.index') }}" class="nav-link {{ isset($clinic_active) ? $clinic_active : '' }}">
                        <i class="nav-icon fa fa-fw fa-hotel"></i>
                        <p>Khoa khám bệnh</p>
                    </a>
                </li>
                @endif
                @if($user->can(['toan-quyen-quan-ly', 'danh-sach-dich-vu']))
                <li class="nav-item">
                    <a href="{{ route('specialty.index') }}" class="nav-link {{ isset($specialty_active) ? $specialty_active : '' }}">
                        <i class="nav-icon fa fa-fw fa-heartbeat"></i>
                        <p>Dịch vụ</p>
                    </a>
                </li>
                @endif
                @if($user->can(['toan-quyen-quan-ly', 'danh-sach-benh-nhan']))
                <li class="nav-item">
                    <a href="{{ route('patient.index') }}" class="nav-link {{ isset($patient_active) ? $patient_active : '' }}">
                        <i class="nav-icon fa fa-fw fa-user" aria-hidden="true"></i>
                        <p> Bệnh nhân </p>
                    </a>
                </li>
                @endif
                @if($user->can(['toan-quyen-quan-ly', 'danh-sach-bac-si']))
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ isset($user_active) ? $user_active : '' }}">
                        <i class="nav-icon fa fa-fw fa-user-secret" aria-hidden="true"></i>
                        <p> Bác sĩ </p>
                    </a>
                </li>
                @endif
                @if($user->can(['toan-quyen-quan-ly', 'danh-sach-lich-lam-viec']))
                <li class="nav-item">
                    <a href="{{ route('schedule.index') }}" class="nav-link {{ isset($schedule_active) ? $schedule_active : '' }}">
                        <i class="nav-icon fas fa-calendar-check" aria-hidden="true"></i>
                        <p> Lịch làm việc </p>
                    </a>
                </li>
                @endif
                @if($user->can(['toan-quyen-quan-ly', 'danh-sach-lich-kham']))
                <li class="nav-item">
                    <a href="{{ route('booking.index') }}" class="nav-link {{ isset($booking_active) ? $booking_active : '' }}">
                        <i class="nav-icon fa fa-fw fa-list" aria-hidden="true"></i>
                        <p> Thông tin lịch khám </p>
                    </a>
                </li>
                @endif
                @if($user->can(['toan-quyen-quan-ly', 'bao-cao-thong-ke']))
                <li class="nav-item">
                    <a href="{{ route('booking.report.statistics') }}" class="nav-link {{ isset($booking_report_active) ? $booking_report_active : '' }}">
                        <i class="nav-icon fas fa-chart-line" aria-hidden="true"></i>
                        <p> Báo cáo thống kê</p>
                    </a>
                </li>
                @endif
                @if($user->can(['danh-sach-bai-viet', 'toan-quyen-quan-ly']))
                <li class="nav-item has-treeview">
                    <a href="{{ route('article.index') }}" class="nav-link {{ isset($article_active) ? $article_active : '' }}">
                        <i class="nav-icon fas fa-file-word" aria-hidden="true"></i>
                        <p>Bài viết</p>
                    </a>
                </li>
                @endif
                @if($user->can(['danh-sach-danh-gia', 'toan-quyen-quan-ly']))
                    <li class="nav-item has-treeview">
                        <a href="{{ route('rating.index') }}" class="nav-link {{ isset($rating_active) ? $rating_active : '' }}">
                            <i class="nav-icon fa fa-fw fa-star" aria-hidden="true"></i>
                            <p>Đánh giá</p>
                        </a>
                    </li>
                @endif
                @if($user->can(['toan-quyen-quan-ly', 'danh-sach-banner']))
                <li class="nav-item has-treeview">
                    <a href="{{ route('slide.index') }}" class="nav-link {{ isset($slide_active) ? $slide_active : '' }}">
                        <i class="nav-icon fab fa-jsfiddle" aria-hidden="true"></i>
                        <p>Banner</p>
                    </a>
                </li>
                @endif
                @if($user->can(['toan-quyen-quan-ly', 'danh-sach-lien-he']))
                    <li class="nav-item has-treeview">
                        <a href="{{ route('contact.index') }}" class="nav-link {{ isset($contact_active) ? $contact_active : '' }}">
                            <i class="nav-icon fa fa-fw fa-envelope" aria-hidden="true"></i>
                            <p>Liên hệ</p>
                        </a>
                    </li>
                @endif
                {{--<li class="nav-item">--}}
                    {{--<a href="{{ route('group.permission.index') }}" class="nav-link {{ isset($group_permission) ? $group_permission : '' }}">--}}
                        {{--<i class="nav-icon fa fa-hourglass" aria-hidden="true"></i>--}}
                        {{--<p>Nhóm quyền</p>--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--<li class="nav-item">--}}
                    {{--<a href="{{ route('permission.index') }}" class="nav-link {{ isset($permission_active) ? $permission_active : '' }}">--}}
                        {{--<i class="nav-icon fa fa-balance-scale"></i>--}}
                        {{--<p> Quyền </p>--}}
                    {{--</a>--}}
                {{--</li>--}}
                @if($user->can(['toan-quyen-quan-ly', 'danh-sach-vai-tro']))
                <li class="nav-item">
                    <a href="{{ route('role.index') }}" class="nav-link {{ isset($role_active) ? $role_active : '' }}">
                        <i class="nav-icon fa fa-gavel" aria-hidden="true"></i>
                        <p> Vai trò </p>
                    </a>
                </li>
                @endif
                @if($user->can(['toan-quyen-quan-ly', 'chi-tiet-tai-khoan-bac-si', 'chi-tiet-tai-khoan-benh-nhan']))
                <li class="nav-item">
                    <a href="{{ route('user.show') }}" class="nav-link {{ isset($user_profile_active) ? $user_profile_active : '' }}">
                        <i class="nav-icon fa fa-fw fa-user" aria-hidden="true"></i>
                        <p> Tài khoản </p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('profile.change.password') }}" class="nav-link {{ isset($change_password) ? $change_password : '' }}">
                        <i class="nav-icon fa fa-fw fa-lock" aria-hidden="true"></i>
                        <p> Đổi mật khẩu </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
