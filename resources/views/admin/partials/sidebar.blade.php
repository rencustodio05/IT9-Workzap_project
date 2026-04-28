<aside id="admin-sidebar" class="admin-sidebar fixed inset-y-0 left-0 z-50 w-72 p-5 border-r border-slate-800/60 flex flex-col transform -translate-x-full transition-transform duration-300 ease-out lg:translate-x-0">
    <div class="mb-6 pb-2">
        <div class="flex items-center justify-between gap-3">
            <div class="text-2xl font-black tracking-tight text-white">Workzap <span class="text-yellow-400">Admin</span></div>
            <button id="admin-sidebar-close" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/20 text-white lg:hidden" aria-label="Close sidebar">
                x
            </button>
        </div>
        <div class="text-xs text-slate-400 mt-1">Control center</div>
        <div class="mt-2 mb-3 h-px w-full rounded-full bg-white/20 shadow-[0_0_10px_rgba(255,255,255,0.18)] blur-[0.5px]"></div>
    </div>

    <nav class="flex h-full flex-col justify-between">
        <div class="space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-lg">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-lg">
                <span>Users</span>
            </a>
            <a href="{{ route('admin.jobs.index') }}" class="admin-nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-lg">
                <span>Jobs</span>
            </a>
            <a href="{{ route('admin.employers.index') }}" class="admin-nav-link {{ request()->routeIs('admin.employers.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-lg">
                <span>Employers</span>
            </a>
            <a href="{{ route('admin.subscriptions.index') }}" class="admin-nav-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-lg">
                <span>Subscriptions</span>
            </a>
            <a href="{{ route('admin.subscription-payments.index') }}" class="admin-nav-link {{ request()->routeIs('admin.subscription-payments.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-lg">
                <span>Subscription Payments</span>
            </a>
            <a href="{{ route('admin.profile') }}" class="admin-nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-lg">
                <span>Profile</span>
            </a>
            <a href="{{ route('admin.archive.index') }}" class="admin-nav-link {{ request()->routeIs('admin.archive.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-lg">
                <span>Archived</span>
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="admin-nav-link flex w-full items-center gap-3 rounded-lg px-4 py-2.5 text-left text-sm text-red-200 hover:bg-red-500/10 hover:text-red-100">
                <span class="h-2 w-2 rounded-full bg-red-200/70"></span>
                Logout
            </button>
        </form>
    </nav>
</aside>