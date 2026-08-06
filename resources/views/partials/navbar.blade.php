<header class="bg-white h-topbar-height border-b border-outline-variant flex items-center justify-between px-container-padding sticky top-0 z-10 no-print">
    <div class="flex items-center gap-4">
        <button class="md:hidden text-on-surface-variant p-2 rounded-full hover:bg-surface-container-low">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="relative hidden sm:block">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
            <input class="pl-10 pr-4 h-10 w-72 border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary bg-surface-container-low placeholder:text-secondary"
                   placeholder="Cari server, IP, atau domain..." type="text" />
        </div>
    </div>
    <div class="flex items-center gap-1">
        <button class="text-on-surface-variant hover:bg-surface-container-low w-10 h-10 rounded-full transition-colors relative flex items-center justify-center">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
        </button>
        <div class="w-px h-6 bg-outline-variant mx-2"></div>

        <!-- Dropdown User -->
        <div class="relative" id="user-menu">
            <button id="user-menu-button" class="w-9 h-9 rounded-full bg-primary-container text-white flex items-center justify-center font-semibold text-sm focus:outline-none hover:ring-2 hover:ring-primary/30 transition-all">
                {{ auth()->check() ? strtoupper(substr(auth()->user()->name ?? 'Guest', 0, 2)) : 'GU' }}
            </button>
            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-outline-variant py-1 z-20">
                <div class="px-4 py-2 text-sm text-secondary border-b border-outline-variant">
                    {{ auth()->user()->name ?? 'Guest' }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-on-surface hover:bg-surface-container-low transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">logout</span> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    (function() {
        const button = document.getElementById('user-menu-button');
        const dropdown = document.getElementById('user-dropdown');

        if (button && dropdown) {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!dropdown.classList.contains('hidden') && !button.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    })();
</script>