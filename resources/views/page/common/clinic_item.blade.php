<div class="trainer-item">
    <div class="ti-img">
        @if(!empty($clinic->logo))
        <img src="{{ !empty($clinic->logo) ? asset(pare_url_file($clinic->logo)) : '' }}" style="width: 100%; height: 200px" alt="">
        @endif
    </div>
    <div class="ti-text">
        <h4><a class="description-2lines" href="{{ route('clinic.detail', ['id' => $clinic->id, 'slug' => safeTitle($clinic->name)]) }}">{{ $clinic->name }}</a></h4>
        <h6>{{ $clinic->address }}</h6>
        <p class="description-2lines">{!! $clinic->description !!}</p>
        <div class="ti-social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
        </div>
    </div>
</div>
