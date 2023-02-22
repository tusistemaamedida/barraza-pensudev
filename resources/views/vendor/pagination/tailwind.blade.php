@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="w-full mt-3 xl:mt-0 flex-1 pagination">
        <div class="sm:grid grid-cols-3 gap-2">
            <div>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-block px-6 py-2 border-2 border-blue-600 text-blue-600 font-medium text-xs leading-tight uppercase rounded-full hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out btn-ant">
                        << Ant.
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-block px-6 py-2 border-2 border-blue-600 text-blue-600 font-medium text-xs leading-tight uppercase rounded-full hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out btn-ant">
                        << Ant.
                    </a>
                @endif
            </div>

            <div>
                <span class="px-2 py-1 rounded-full bg-primary text-white mr-1">{{$paginator->total()}}</span>
            </div>

            <div >
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-block px-6 py-2 border-2 border-blue-600 text-blue-600 font-medium text-xs leading-tight uppercase rounded-full hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out btn-sig">
                        Sig. >>
                    </a>
                @else
                    <span class="inline-block px-6 py-2 border-2 border-blue-600 text-blue-600 font-medium text-xs leading-tight uppercase rounded-full hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out btn-sig">
                        Sig. >>
                    </span>
                @endif
            </div>
    </nav>
@endif
