<aside class="fixed left-0 top-0 h-full w-sidebar-width bg-[#0F172A] flex-col z-20 hidden md:flex no-print">
  <div class="px-6 py-6 flex items-center gap-3 border-b border-white/10">
    <div class="w-11 h-11 rounded-full bg-primary-container flex items-center justify-center text-white font-bold text-lg flex-shrink-0">ST</div>
    <div class="min-w-0">
      <h1 class="text-white font-bold text-base leading-tight truncate">SIM TIK</h1>
      <p class="text-slate-400 text-xs leading-tight mt-0.5">Admin Data Center</p>
    </div>
  </div>

  <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
    <!-- Menu 1: Dashboard -->
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-container/15 text-white font-semibold border-l-4 border-primary-container -ml-px' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">dashboard</span>
      <span class="text-sm font-medium">Dashboard</span>
    </a>

    <!-- Menu 2: Perangkat & Server -->
  <a href="{{ route('server.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('server.*') ? 'bg-primary-container/15 text-white font-semibold border-l-4 border-primary-container -ml-px' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
    <span class="material-symbols-outlined text-[20px]">dns</span>
    <span class="text-sm font-medium">Perangkat & Server</span>
</a>
    <!-- Menu 3: cPanel -->
    <a href="{{ route('cpanel') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('cpanel') ? 'bg-primary-container/15 text-white font-semibold border-l-4 border-primary-container -ml-px' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">settings_applications</span>
      <span class="text-sm font-medium">cPanel</span>
    </a>

    <!-- Menu 4: Aplikasi -->
    <a href="{{ route('aplikasi') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('aplikasi') ? 'bg-primary-container/15 text-white font-semibold border-l-4 border-primary-container -ml-px' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
      <span class="material-symbols-outlined text-[20px]">apps</span>
      <span class="text-sm font-medium">Aplikasi</span>
    </a>

    <!-- Menu 5: SPLP -->
    <div class="pt-4 mt-4 border-t border-white/10 space-y-1">
      <a href="{{ route('splp') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-slate-300 hover:bg-white/5 hover:text-white">
        <span class="material-symbols-outlined text-[20px]">swap_horiz</span>
        <span class="text-sm font-medium">SPLP</span>
      </a>
    </div>
  </nav>
</aside>