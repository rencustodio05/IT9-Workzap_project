<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Employer Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/css/admin.css'])
    @stack('styles')
</head>

<script src="//unpkg.com/alpinejs" defer></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')
</body>

<body class="admin-shell flex min-h-screen text-slate-900">
    <!-- Sidebar -->
    <aside id="employer-sidebar" class="admin-sidebar fixed inset-y-0 left-0 z-50 w-64 text-white flex flex-col p-6 space-y-4 h-screen overflow-y-auto shadow-xl transform -translate-x-full transition-transform duration-300 ease-out lg:translate-x-0">
        <div class="mb-5 pb-2">
            <div class="flex items-center justify-between gap-3">
                <span class="text-2xl font-bold tracking-wide">Workzap <span class="text-yellow-400">Employer</span></span>
                <button id="employer-sidebar-close" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-white/20 text-white lg:hidden" aria-label="Close sidebar">x</button>
            </div>
            <div class="mt-2 mb-3 h-px w-full rounded-full bg-white/20 shadow-[0_0_10px_rgba(255,255,255,0.18)] blur-[0.5px]"></div>
        </div>
        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('employer.dashboard') }}"
                class="admin-nav-link py-2.5 px-4 rounded-lg {{ request()->routeIs('employer.dashboard') ? 'active font-semibold shadow-sm' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('employer.jobs.index') }}"
                class="admin-nav-link py-2.5 px-4 rounded-lg {{ request()->routeIs('employer.jobs.*') ? 'active font-semibold shadow-sm' : '' }}">
                My Job Postings
            </a>
            <a href="{{ route('employer.applications.index') }}"
                class="admin-nav-link py-2.5 px-4 rounded-lg {{ request()->routeIs('employer.applications.*') ? 'active font-semibold shadow-sm' : '' }}">
                Applicants
            </a>
            <a href="{{ route('employer.interviews.index') }}"
                class="admin-nav-link py-2.5 px-4 rounded-lg {{ request()->routeIs('employer.interviews.*') ? 'active font-semibold shadow-sm' : '' }}">
                Interviews
            </a>
            <a href="{{ route('employer.subscription.index') }}"
                class="admin-nav-link py-2.5 px-4 rounded-lg {{ request()->routeIs('employer.subscription.*') ? 'active font-semibold shadow-sm' : '' }}">
                Subscription
            </a>
        </nav>
    </aside>

    <div id="employer-sidebar-overlay" class="fixed inset-0 z-40 bg-black/40 hidden lg:hidden"></div>

    <!-- Main Content -->
    <main class="flex-1 min-h-screen p-5 md:p-8 lg:ml-64">
        <div class="admin-surface sticky top-0 z-50 bg-white shadow-sm rounded-xl p-4 sm:p-5 mb-4 flex items-center justify-between gap-3" id="employer-global-topbar">
            <div class="min-w-0">
                <h1 class="text-xl font-black tracking-tight truncate">@yield('title', 'Employer Dashboard')</h1>
                <p class="text-sm text-slate-500">@yield('subtitle', 'Manage job postings and applicants with confidence.')</p>
            </div>

            <button id="employer-sidebar-toggle" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 lg:hidden" aria-label="Open sidebar">
                <span class="text-xl leading-none">☰</span>
            </button>

            <div class="relative" id="global-header-menu-root">
                <button type="button" id="global-header-menu-button" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" aria-label="Open account menu" aria-expanded="false">
                    <span class="sr-only">Toggle menu</span>
                    <span class="text-xl leading-none">☰</span>
                </button>

                <div id="global-header-menu" class="admin-surface hidden absolute right-0 mt-2 w-56 origin-top-right rounded-xl transition duration-150 ease-out opacity-0 scale-95 z-40">
                    <a href="{{ route('employer.profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">Logout</button>
                    </form>
                </div>
            </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('employer-sidebar');
            const sidebarToggle = document.getElementById('employer-sidebar-toggle');
            const sidebarClose = document.getElementById('employer-sidebar-close');
            const sidebarOverlay = document.getElementById('employer-sidebar-overlay');

            const menuRoot = document.getElementById('global-header-menu-root');
            const menuButton = document.getElementById('global-header-menu-button');
            const menu = document.getElementById('global-header-menu');
            if (!menuRoot || !menuButton || !menu) return;

            const openSidebar = () => {
                if (!sidebar || !sidebarOverlay) return;
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            };

            const closeSidebar = () => {
                if (!sidebar || !sidebarOverlay) return;
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            };

            sidebarToggle?.addEventListener('click', openSidebar);
            sidebarClose?.addEventListener('click', closeSidebar);
            sidebarOverlay?.addEventListener('click', closeSidebar);

            const openMenu = () => {
                menu.classList.remove('hidden', 'opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
                menuButton.setAttribute('aria-expanded', 'true');
            };

            const closeMenu = () => {
                menu.classList.add('opacity-0', 'scale-95');
                menu.classList.remove('opacity-100', 'scale-100');
                menuButton.setAttribute('aria-expanded', 'false');
                setTimeout(function() {
                    if (menuButton.getAttribute('aria-expanded') === 'false') {
                        menu.classList.add('hidden');
                    }
                }, 150);
            };

            menuButton.addEventListener('click', function(event) {
                event.stopPropagation();
                (menuButton.getAttribute('aria-expanded') === 'true') ? closeMenu(): openMenu();
            });

            document.addEventListener('click', function(event) {
                if (!menuRoot.contains(event.target)) closeMenu();
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') closeMenu();
            });

            window.addEventListener('resize', function() {
                if (!sidebar || !sidebarOverlay) return;
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>