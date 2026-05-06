@extends('page.layouts.page')
@section('title', 'Tin tức')
@section('style')
@stop
@section('content')
    @php
        $link_img = '';
        $title = 'Tin tức';
        $description = 'Cập nhật những bài viết mới nhất';
    @endphp
    @include('page.common.top_section', compact('title', 'description'))

    <section class="events-page-section spad">
        <div class="container">
            <div class="row">
                @foreach($articles as $article)
                <div class="col-lg-4 col-md-6">
                    <div class="blog-item">
                        @if(isset($article) && !empty($article->image))
                            <img src="{{ asset(pare_url_file($article->image)) }}" alt=""   id="image_render">
                        @else
                            <img src="{{ asset('admin/dist/img/no-image.png') }}" alt=""  id="image_render">
                        @endif
                        <div class="bi-text">
                            <h2><a href="{{ route('article.detail', ['id' => $article->id, 'slug' => $article->slug]) }}">{{ $article->name }}</a></h2>
                            <p>{{ $article->description }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-lg-12" style="margin-top: 15px">
                    {{ $articles->appends($query = '')->links('page.paginator.index') }}
                </div>
            </div>
        </div>
    </section>
    <!-- Service Section end -->
@stop
@section('script')
@stop