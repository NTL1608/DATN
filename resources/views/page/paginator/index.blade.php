@if ($paginator->hasPages())
    <!-- Pagination -->
    <div class="site-pagination pt-3">
        @if ($paginator->onFirstPage())
            <a class="disabled">
                <span><i class="fa fa-angle-double-left"></i></span>
            </a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">
                <span><i class="fa fa-angle-double-left"></i></span>
            </a>
        @endif

        @foreach ($elements as $element)
            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="active">{{ $page }}</a>
                    @elseif (($page == $paginator->currentPage() + 1 || $page == $paginator->currentPage() + 2) || $page == $paginator->lastPage())
                        <a href="{{ $url }}">{{ $page }}</a>
                    @elseif ($page == $paginator->lastPage() - 1)
                        <a class="disabled"><span><i class="fa fa-ellipsis-h"></i></span></a>
                    @endif
                @endforeach
            @endif
        @endforeach
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">
                <span><i class="fa fa-angle-double-right"></i></span>
            </a>
        @else
            <a class="disabled">
                <span><i class="fa fa-angle-double-right"></i></span>
            </a>
        @endif
    </div>
@endif
