<!--begin::Pagination-->
@if ($paginator->hasPages())
    @php
        $first = $paginator->currentPage() - 3 ;
        $first = $first > 1 ? $first : 1;
        $last = $first + 6;
        $last = ($last < $paginator->lastPage()) ? $last : $paginator->lastPage();
    @endphp
    <ul class="pagination">
        @if ($paginator->onFirstPage())
            <li class="paginate_button page-item previous disabled">
                <a href="javascript:void(0)" class="page-link">
                </a>
            </li>
        @else
            <li class="paginate_button page-item previous" id="kt_datatable_2_previous">
                <a href="{{ $paginator->previousPageUrl() }}" aria-controls="kt_datatable_2" tabindex="0"
                   class="page-link">
                </a>
            </li>
        @endif
        @if($first > 1)
            <li class="paginate_button page-item disabled">
                <a href="javascript:void(0)" class="page-link">
                    ...
                </a>
            </li>
        @endif
        @for ($page = $first; $page <= $last; $page++)
            @if($page == $paginator->currentPage())
                <li class="paginate_button page-item active">
                    <a href="javascript:void(0)" class="page-link">{{ $page }}</a>
                </li>
            @else
                <li class="paginate_button page-item">
                    <a href="{{ $paginator->url($page) }}" class="page-link">{{ $page }}</a>
                </li>
            @endif
        @endfor
        @if($last < $paginator->lastPage())
            <li class="paginate_button page-item disabled">
                <a href="javascript:void(0)" class="page-link">
                    ...
                </a>
            </li>
        @endif
        @if($paginator->lastPage() == $paginator->currentPage())
            <li class="paginate_button page-item next disabled">
                <a href="javascript:void(0)" class="page-link">
                </a>
            </li>
        @else
            <li class="paginate_button page-item next">
                <a href="{{ $paginator->url($paginator->currentPage() + 1) }}" class="page-link">
                </a>
            </li>
        @endif
    </ul>
@endif
<!--end:: Pagination-->
