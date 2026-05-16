<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-slider owl-carousel">
        @if ($slides->count() > 0)
            @foreach($slides as $key => $slide)
                <div class="hs-item">
                    <div class="hs-style-1 text-center">
                        <img src="{{ !empty($slide->image) ? asset(pare_url_file($slide->image)) : asset('page/img/hero-slider/slider_default.jpg') }}" alt="">
                    </div>
                </div>
            @endforeach
        @else
            <div class="hs-item">
                <div class="hs-style-1 text-center">
                    <img src="{{ !empty($slide->image) ? asset(pare_url_file($slide->image)) : asset('page/img/hero-slider/slider_default.jpg') }}" alt="">
                </div>
            </div>
        @endif
    </div>
</section>
<!-- Hero Section end -->
