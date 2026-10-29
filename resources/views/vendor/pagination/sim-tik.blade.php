{{--
    Pagination bergaya Manajemen Dokumen: satu grup tombol menyatu, halaman aktif abu-abu.
    Dipakai: {{ $paginator->links('vendor.pagination.sim-tik') }} pada seluruh halaman.
    Teks "Menampilkan x hingga y dari z entri" ditulis di view pemanggil, jadi tidak diulang di sini.
--}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman">
        <ul class="inline-flex items-stretch rounded-md border border-outline-variant bg-white overflow-hidden text-sm divide-x divide-outline-variant">

            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="Sebelumnya">
                    <span class="flex items-center justify-center px-2.5 py-1.5 text-gray-300 cursor-not-allowed select-none">&lsaquo;</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya"
                       class="flex items-center justify-center px-2.5 py-1.5 text-gray-600 hover:bg-gray-100 transition-colors">&lsaquo;</a>
                </li>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span class="flex items-center justify-center px-3 py-1.5 text-gray-400 select-none">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span class="flex items-center justify-center min-w-[32px] px-3 py-1.5 bg-gray-200 text-gray-800 font-medium">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" aria-label="Ke halaman {{ $page }}"
                                   class="flex items-center justify-center min-w-[32px] px-3 py-1.5 text-gray-600 hover:bg-gray-100 transition-colors">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya"
                       class="flex items-center justify-center px-2.5 py-1.5 text-gray-600 hover:bg-gray-100 transition-colors">&rsaquo;</a>
                </li>
            @else
                <li aria-disabled="true" aria-label="Berikutnya">
                    <span class="flex items-center justify-center px-2.5 py-1.5 text-gray-300 cursor-not-allowed select-none">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
