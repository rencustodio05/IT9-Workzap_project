<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Employer Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/css/admin.css'])
</head>

<body class="admin-shell flex min-h-screen text-slate-900">
    <!-- Sidebar -->
    <aside class="admin-sidebar text-white w-64 flex flex-col p-6 space-y-4 h-screen sticky top-0 overflow-y-auto shadow-xl">
        <div class="mb-5 pb-2">
            <span class="text-2xl font-bold tracking-wide">Workzap <span class="text-yellow-400">Employer</span></span>
            <div class="mt-2 mb-3 h-px w-full rounded-full bg-white/20 shadow-[0_0_10px_rgba(255,255,255,0.18)] blur-[0.5px]"></div>
        </div>
        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('employer.dashboard') }}"
                class="admin-nav-link py-2.5 px-4 rounded-lg {{ request()->routeIs('employer.dashboard') ? 'active font-semibold shadow-sm' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('jobs.index') }}"
                class="admin-nav-link py-2.5 px-4 rounded-lg {{ request()->routeIs('jobs.index') ? 'active font-semibold shadow-sm' : '' }}">
                My Job Postings
            </a>
            <a href="{{ route('applications.index') }}"
                class="admin-nav-link py-2.5 px-4 rounded-lg {{ request()->routeIs('applications.index') ? 'active font-semibold shadow-sm' : '' }}">
                Applicants
            </a>
            <a href="{{ route('interviews.index') }}"
                class="admin-nav-link py-2.5 px-4 rounded-lg {{ request()->routeIs('interviews.index') ? 'active font-semibold shadow-sm' : '' }}">
                Interviews
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 min-h-screen p-5 md:p-8">
        <div class="admin-surface sticky top-0 z-50 bg-white shadow-sm rounded-xl p-4 sm:p-5 mb-4 flex items-center justify-between gap-3" id="employer-global-topbar">
            <div class="min-w-0">
                <h1 class="text-xl font-black tracking-tight truncate">@yield('title', 'Employer Dashboard')</h1>
                <p class="text-sm text-slate-500">@yield('subtitle', 'Manage job postings and applicants with confidence.')</p>
            </div>

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

        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuRoot = document.getElementById('global-header-menu-root');
            const menuButton = document.getElementById('global-header-menu-button');
            const menu = document.getElementById('global-header-menu');
            if (!menuRoot || !menuButton || !menu) return;

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
        });
    </script>

    @stack('scripts')
</body>

</html>