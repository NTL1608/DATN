@extends('page.layouts.page')
@section('title', isset($article) ? $article->name : '')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Tin tức';
        $description = isset($article) ? $article->name : '';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))

    <section class="events-page-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="blog-details">
                        <div class="blog-preview">
                            @if ($article->image)
                            <img src="{{ asset(pare_url_file($article->image)) }}" alt="">
                            @endif
                        </div>
                        <h2>{{ $article->name }}</h2>
                        <div class="blog-meta"><p><i class="material-icons">alarm_on</i>{{ date('Y-m-d', strtotime($article->created_at)) }} </p></div>

                        {!! $article->content !!}
                    </div>

                </div>
                <div class="col-lg-3 col-md-5 col-sm-8 sidebar">
                    <div class="sb-widget">
                        <h2 class="sb-title">BÀI VIẾT MỚI</h2>
                        <div class="latest-post-widget">
                            @foreach($articles as $art)
                            <div class="lp-item">
                                <div class="lp-thumb set-bg" data-setbg="{{ asset(pare_url_file($art->image)) }}" style="background-image: url({{ asset(pare_url_file($art->image)) }});"></div>
                                <div class="lp-text">
                                    <a href="{{ route('article.detail', ['id' => $art->id, 'slug' => $art->slug]) }}"><h3>{{ $art->name }}</h3></a>
                                    <p><i class="material-icons">event_available</i>{{ date('Y-m-d', strtotime($art->created_at)) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Service Section end -->
@stop
@section('script')
@stop