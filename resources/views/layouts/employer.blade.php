<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Employer Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/css/admin.css'])
    @stack('styles')
</head>

<body class="admin-shell flex min-h-screen text-slate-900">
    <aside
        id="employer-sidebar"
        aria-label="Employer navigation"
        class="admin-sidebar fixed inset-y-0 left-0 z-50 flex h-screen w-64 flex-col overflow-y-auto border-r border-white/10 bg-slate-900 px-6 py-6 text-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-out lg:translate-x-0">
        <div class="mb-6">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <img src="/images/workzap-logo.png" alt="Workzap logo" style="width: 32px; height: 40px;" class="object-contain">
                    <div class="leading-tight">
                        <div class="text-2xl font-bold tracking-wide">Workzap</div>
                        <div class="text-sm text-yellow-400">Employer</div>
                    </div>
                </div>
                <button
                    id="employer-sidebar-close"
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/20 text-white transition hover:bg-white/10 lg:hidden"
                    aria-label="Close sidebar">
                    x
                </button>
            </div>
            <div class="mt-3 h-px w-full rounded-full bg-white/20"></div>
        </div>

        <nav class="flex flex-1 flex-col gap-1.5" aria-label="Main navigation">
            <a href="{{ route('employer.dashboard') }}"
                class="group admin-nav-link flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm transition {{ request()->routeIs('employer.dashboard') ? 'active font-semibold shadow-sm' : 'hover:bg-white/10 hover:text-white/95' }}">
                <span aria-hidden="true" class="h-2 w-2 rounded-full bg-white/40 transition group-hover:bg-white/80 {{ request()->routeIs('employer.dashboard') ? 'bg-yellow-300' : '' }}"></span>
                <span class="truncate">Dashboard</span>
            </a>
            <a href="{{ route('employer.jobs.index') }}"
                class="group admin-nav-link flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm transition {{ request()->routeIs('employer.jobs.*') ? 'active font-semibold shadow-sm' : 'hover:bg-white/10 hover:text-white/95' }}">
                <span aria-hidden="true" class="h-2 w-2 rounded-full bg-white/40 transition group-hover:bg-white/80 {{ request()->routeIs('employer.jobs.*') ? 'bg-yellow-300' : '' }}"></span>
                <span class="truncate">My Job Postings</span>
            </a>
            <a href="{{ route('employer.applications.index') }}"
                class="group admin-nav-link flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm transition {{ request()->routeIs('employer.applications.*') ? 'active font-semibold shadow-sm' : 'hover:bg-white/10 hover:text-white/95' }}">
                <span aria-hidden="true" class="h-2 w-2 rounded-full bg-white/40 transition group-hover:bg-white/80 {{ request()->routeIs('employer.applications.*') ? 'bg-yellow-300' : '' }}"></span>
                <span class="truncate">Applicants</span>
            </a>
            <a href="{{ route('employer.interviews.index') }}"
                class="group admin-nav-link flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm transition {{ request()->routeIs('employer.interviews.*') ? 'active font-semibold shadow-sm' : 'hover:bg-white/10 hover:text-white/95' }}">
                <span aria-hidden="true" class="h-2 w-2 rounded-full bg-white/40 transition group-hover:bg-white/80 {{ request()->routeIs('employer.interviews.*') ? 'bg-yellow-300' : '' }}"></span>
                <span class="truncate">Interviews</span>
            </a>
            <a href="{{ route('employer.subscription.index') }}"
                class="group admin-nav-link flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm transition {{ request()->routeIs('employer.subscription.*') ? 'active font-semibold shadow-sm' : 'hover:bg-white/10 hover:text-white/95' }}">
                <span aria-hidden="true" class="h-2 w-2 rounded-full bg-white/40 transition group-hover:bg-white/80 {{ request()->routeIs('employer.subscription.*') ? 'bg-yellow-300' : '' }}"></span>
                <span class="truncate">Subscription</span>
            </a>
            <a href="{{ route('employer.profile') }}"
                class="group admin-nav-link flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm transition {{ request()->routeIs('employer.profile') ? 'active font-semibold shadow-sm' : 'hover:bg-white/10 hover:text-white/95' }}">
                <span aria-hidden="true" class="h-2 w-2 rounded-full bg-white/40 transition group-hover:bg-white/80 {{ request()->routeIs('employer.profile') ? 'bg-yellow-300' : '' }}"></span>
                <span class="truncate">Profile</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="admin-nav-link flex w-full items-center gap-3 rounded-lg px-4 py-2.5 text-left text-sm text-red-200 transition hover:bg-red-500/10 hover:text-red-100">
                    <span class="h-2 w-2 rounded-full bg-red-200/70"></span>
                    <span class="truncate">Logout</span>
                </button>
            </form>
        </nav>
    </aside>

    <div id="employer-sidebar-overlay" class="fixed inset-0 z-40 bg-black/40 hidden lg:hidden" aria-hidden="true"></div>

    <main class="flex-1 min-h-screen p-5 md:p-8 lg:ml-64">
        <div id="employer-global-topbar" class="admin-surface sticky top-0 z-50 mb-4 flex items-center justify-between gap-3 rounded-xl bg-white p-4 shadow-sm sm:p-5">
            <div class="min-w-0">
                <h1 class="truncate text-xl font-black tracking-tight">@yield('title', 'Employer Dashboard')</h1>
                <p class="text-sm text-slate-500">@yield('subtitle', 'Manage job postings and applicants with confidence.')</p>
            </div>

            <button
                id="employer-sidebar-toggle"
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50 lg:hidden"
                aria-label="Open sidebar"
                aria-controls="employer-sidebar"
                aria-expanded="false">
                <span class="text-xl leading-none">☰</span>
            </button>

        </div>

        @if(session('success'))
        <div class="admin-surface border border-emerald-200 bg-emerald-50 text-emerald-800 rounded-xl px-4 py-3 mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="admin-surface border border-red-200 bg-red-50 text-red-800 rounded-xl px-4 py-3 mb-4">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="admin-surface border border-red-200 bg-red-50 text-red-800 rounded-xl px-4 py-3 mb-4">
            {{ $errors->first() }}
        </div>
        @endif

        @yield('content')
    </main>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('employer-sidebar');
            const sidebarToggle = document.getElementById('employer-sidebar-toggle');
            const sidebarClose = document.getElementById('employer-sidebar-close');
            const sidebarOverlay = document.getElementById('employer-sidebar-overlay');

            const setSidebarExpanded = (expanded) => {
                sidebarToggle?.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            };

            const openSidebar = () => {
                if (!sidebar || !sidebarOverlay) return;
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                setSidebarExpanded(true);
            };

            const closeSidebar = () => {
                if (!sidebar || !sidebarOverlay) return;
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                setSidebarExpanded(false);
            };

            sidebarToggle?.addEventListener('click', openSidebar);
            sidebarClose?.addEventListener('click', closeSidebar);
            sidebarOverlay?.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            window.addEventListener('resize', function() {
                if (!sidebar || !sidebarOverlay) return;
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                    setSidebarExpanded(true);
                } else {
                    sidebar.classList.add('-translate-x-full');
                    setSidebarExpanded(false);
                }
            });

            if (window.innerWidth >= 1024) {
                setSidebarExpanded(true);
            }
        });
    </script>

    @stack('scripts')
</body>

</html>