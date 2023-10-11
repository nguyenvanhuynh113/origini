<style>
    .pagination li {
        margin: 0 5px; /* Khoảng cách giữa các thẻ li */
    }
    .pagination li.active a {
        color: rgba(122, 182, 14, 0.99); /* Màu của trang hiện tại */
    }
</style>
@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Liên kết đến trang trước --}}
        <li class="{{ $paginator->onFirstPage() ? 'disabled first-page' : '' }}">
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&laquo;</a>
        </li>

        {{-- Các phần tử của phân trang --}}
        @foreach ($elements as $element)
            {{-- Dấu "..." phân cách --}}
            @if (is_string($element))
                <li><span>{{ $element }}</span></li>
            @endif

            {{-- Mảng các liên kết --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    {{-- Liên kết đến từng trang --}}
                    <li class="{{ $page == $paginator->currentPage() ? 'active' : '' }}">
                        <a href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach
            @endif
        @endforeach

        {{-- Liên kết đến trang tiếp theo --}}
        <li class="{{ $paginator->hasMorePages() ? '' : 'disabled' }}">
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&raquo;</a>
        </li>
    </ul>
@endif

