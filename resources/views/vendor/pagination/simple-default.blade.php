@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true"><span>ANT.</span></li>
            @else
                <li><a href="#" rel="prev">ANT.</a></li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="#" rel="next">SIG.</a></li>
            @else
                <li class="disabled" aria-disabled="true"><span>SIG.</span></li>
            @endif
        </ul>
    </nav>
@endif
