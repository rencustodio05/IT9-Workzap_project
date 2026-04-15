<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Employer Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css'])
</head>

<body class="bg-slate-100 flex min-h-screen text-slate-900">
    <!-- Sidebar -->
    <aside class="bg-[#1e3a5f] text-white w-64 flex flex-col p-6 space-y-4 h-screen sticky top-0 overflow-y-auto shadow-xl">
        <div class="mb-7">
            <span class="text-2xl font-bold tracking-wide">Workzap <span class="text-yellow-400">Employer</span></span>
        </div>
        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('employer.dashboard') }}"
                class="py-2.5 px-4 rounded-lg {{ request()->routeIs('employer.dashboard') ? 'bg-[#2a4f7a] font-semibold shadow-sm' : 'hover:bg-[#27486f]' }}">
                Dashboard
            </a>
            <a href="{{ route('jobs.index') }}"
                class="py-2.5 px-4 rounded-lg {{ request()->routeIs('jobs.index') ? 'bg-[#2a4f7a] font-semibold shadow-sm' : 'hover:bg-[#27486f]' }}">
                My Job Postings
            </a>
            <a href="{{ route('applications.index') }}"
                class="py-2.5 px-4 rounded-lg {{ request()->routeIs('applications.index') ? 'bg-[#2a4f7a] font-semibold shadow-sm' : 'hover:bg-[#27486f]' }}">
                Applicants
            </a>
            <a href="{{ route('interviews.index') }}"
                class="py-2.5 px-4 rounded-lg {{ request()->routeIs('interviews.index') ? 'bg-[#2a4f7a] font-semibold shadow-sm' : 'hover:bg-[#27486f]' }}">
                Interviews
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 min-h-screen p-5 md:p-8 bg-[#F8FAFC]">
        <div class="js-shell-topbar" id="employer-global-topbar">
            <div class="min-w-0">
                <h1 class="js-shell-title truncate">@yield('title', 'Employer Dashboard')</h1>
                <p class="js-shell-subtitle">@yield('subtitle', 'Manage job postings and applicants with confidence.')</p>
            </div>

            <div class="relative" id="global-header-menu-root">
                <button type="button" id="global-header-menu-button" class="js-menu-button inline-flex items-center justify-center" aria-label="Open account menu" aria-expanded="false">
                    <span class="sr-only">Toggle menu</span>
                    <span class="text-xl leading-none">☰</span>
                </button>

                <div id="global-header-menu" class="js-menu-dropdown hidden absolute right-0 mt-2 w-56 origin-top-right transition duration-150 ease-out opacity-0 scale-95 z-40">
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