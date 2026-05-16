<div class="row">
    <div class="col-md-6">
        <img src="{{ isset($user->avatar) ? asset(pare_url_file($user->avatar)) : asset('page/img/iconbacsi.png') }}" alt="">
        <div class="ba-text">
            <a href="{{ route('doctor.detail', ['id' => $user->id, 'slug' => safeTitle($user->name)]) }}">
                <h3 class="name_doctor">{{ $user->name }}</h3>
            </a>
            <p>{!! $user->description !!}</p>
            @if ($user->city_id)
                <div class="blog-meta">
                    <p>
                        <a href="?city={{ isset($user->city) ? $user->city->loc_slug : '' }}"> {{ isset($user->city) ? $user->city->loc_name : '' }}</a>
                    </p>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="">
            @if ($user->schedule)
                <select class="date-booking-schedule">
                    @if ($user->schedule->isNotEmpty())
                    @foreach($user->schedule as $schedule)
                        <option value="{{ $schedule->id }}"> {{ getDateTime('vn', 1, 1, 0, '', strtotime($schedule->date_schedule)) . '-' . date('m/d', strtotime($schedule->date_schedule)) }}</option>
                    @endforeach
                    @else
                        <option>Chưa đăng ký lịch khám</option>
                    @endif
                </select>
            @endif
        </div>
        <div class="ei-text">
            <ul>
                <li><i class="material-icons">event_available</i>LỊCH KHÁM</li>
            </ul>
        </div>
        @if ($user->schedule)
            @foreach($user->schedule as $key => $schedule)
                @if ($schedule->times)
                    <div class="col-12 list-times list-times-{{ $schedule->id }}" style="display: {{ $key == 0 ? 'block' : 'none'}}">
                        <div class="sb-tags">
                            @foreach($schedule->times as $key => $time)
                                <a href="{{ route('booking.appointment', $time->id) }}" >{{ $time->time_schedule }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
        <div class="col-12">
            <p style="font-size: 12px;">Chọn và đặt ( Phi đặt lịch 0 đ )</p>
        </div>
        <div class="ei-text" style="border-top: 1px solid #e6e6e6;">
            <ul>
                <li><i class="material-icons">map</i> ĐỊA CHỈ KHÁM</li>
            </ul>
            <p>{{ isset($user->clinic) ? $user->clinic->address : '' }}</p>
        </div>
        <div class="ei-text" style="border-top: 1px solid #e6e6e6;">
            <ul>
                <li><i class="material-icons">local_offer</i> GIÁ KHÁM : <b>{{ !empty($user->price_min) ? number_format($user->price_min) : 0 }} đ</b></li>
            </ul>
        </div>
        <div class="ei-text" style="border-top: 1px solid #e6e6e6;">
            <ul>
                <li><i class="fa fa-fw fa-star-o"></i> ĐÁNH GIÁ :
                    @php
                        $number = 0;
                        $star = 0;
                        $medium = 0;
                        if (isset($user->ratings)) {
                            $number = $user->ratings->count();
                            $star = $user->ratings->sum('star');
                        }
                        if ($number > 0) {
                        $medium = $star / $number;
                        $medium = round($medium, 1);
                        }
                    @endphp
                    @for($i =1 ; $i <=5; $i ++)
                        <i class="fa fa-fw fa-star {{ $medium >= $i ? 'star-yellow' : 'star-default'  }}"></i>
                    @endfor
                </li>
            </ul>
        </div>
    </div>
</div>
