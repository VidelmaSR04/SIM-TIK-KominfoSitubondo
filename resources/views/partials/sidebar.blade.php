<aside class="w-64 min-h-screen bg-slate-900 text-gray-300 flex flex-col">

    <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-800">
        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
            ST
        </div>
        <div>
            <p class="text-white font-semibold text-sm leading-tight">SIM TIK</p>
            <p class="text-xs text-gray-400 leading-tight">Admin Data Center</p>
        </div>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1">

        <div x-data="{ open: {{ request()->routeIs(['manajemen-server','server.*','detailserver']) ? 'true' : 'false' }} }">
            <div class="flex items-center justify-between rounded-lg
                        {{ request()->routeIs(['manajemen-server','server.*','detailserver']) ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800' }}">

                <a href="{{ route('manajemen-server') }}"
                   @click="open = true"
                   class="flex-1 flex items-center gap-3 px-3 py-2.5 text-sm font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" />
                    </svg>
                    Manajemen Server
                </a>

                <button @click="open = !open" class="px-3 py-2.5">
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div x-show="open" class="mt-1 ml-4 space-y-1">

                <a href="{{ route('server.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('server.*') || request()->routeIs('detailserver') ? 'text-blue-400 font-semibold' : 'text-gray-400 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="7" rx="1.5" stroke-width="2" />
                        <rect x="3" y="13" width="18" height="7" rx="1.5" stroke-width="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7.5h.01M7 16.5h.01" />
                    </svg>
                    Perangkat & Server
                </a>

                <div x-data="{ dokumenOpen: {{ request()->routeIs(['server.dokumen.*','server.foto.*']) ? 'true' : 'false' }} }">
                    <div class="flex items-center justify-between rounded-lg
                                {{ request()->routeIs(['server.dokumen.*','server.foto.*']) ? 'text-blue-400 font-semibold' : 'text-gray-400 hover:text-white' }}">

                        <a href="{{ route('server.dokumen.index') }}"
                           class="flex-1 flex items-center gap-2 px-3 py-2 rounded-lg text-sm">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                            </svg>
                            Manajemen Dokumen
                        </a>

                        <button @click="dokumenOpen = !dokumenOpen" class="px-2 py-2">
                            <svg :class="dokumenOpen ? 'rotate-180' : ''" class="w-4 h-4 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="dokumenOpen" class="mt-1 ml-4 space-y-1">
                        <a href="{{ route('server.foto.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('server.foto.*') ? 'text-blue-400 font-semibold' : 'text-gray-400 hover:text-white' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 17V7a2 2 0 012-2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h2" />
                            </svg>
                            Manajemen Photo
                        </a>
                    </div>
                </div>

                <a href="{{ route('server.master.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('server.master.*') ? 'text-blue-400 font-semibold' : 'text-gray-400 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <ellipse cx="12" cy="6" rx="8" ry="3" stroke-width="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6v6c0 1.657 3.582 3 8 3s8-1.343 8-3V6M4 12v6c0 1.657 3.582 3 8 3s8-1.343 8-3v-6" />
                    </svg>
                    Master Data
                </a>

            </div>
        </div>

        <a href="{{ route('splp') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                  {{ request()->routeIs('splp') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7h12m0 0l-4-4m4 4l-4 4M16 17H4m0 0l4 4m-4-4l4-4" />
            </svg>
            SPLP
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                  {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-slate-800' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-6.13a4 4 0 11-8 0 4 4 0 018 0zm6 3a4 4 0 10-8 0" />
            </svg>
            User Management
        </a>

    </nav>
</aside>