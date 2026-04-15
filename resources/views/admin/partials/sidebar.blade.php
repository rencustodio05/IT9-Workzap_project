<aside class="admin-sidebar fixed inset-y-0 left-0 z-40 w-72 p-5 border-r border-slate-800/60 hidden lg:flex flex-col">
    <div class="mb-8">
        <div class="text-2xl font-black tracking-tight text-white">Workzap <span class="text-yellow-400">Admin</span></div>
        <div class="text-xs text-slate-400 mt-1">Control center</div>
    </div>

    <nav class="space-y-2">
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
    </nav>
</aside>