<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="admin-shell h-full">
    <div class="min-h-screen flex">
        @include('admin.partials.sidebar')

        <div id="admin-sidebar-overlay" class="fixed inset-0 z-40 bg-black/40 hidden lg:hidden"></div>

        <div class="flex-1 min-w-0 lg:ml-72">
            @include('admin.partials.topbar')

            <main class="p-4 sm:p-6 lg:p-8 space-y-6">
                @if(session('success'))
                <div class="admin-surface border border-emerald-200 bg-emerald-50 text-emerald-800 rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="admin-surface border border-red-200 bg-red-50 text-red-800 rounded-xl px-4 py-3">
                    {{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="admin-surface border border-red-200 bg-red-50 text-red-800 rounded-xl px-4 py-3">
                    {{ $errors->first() }}
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-sidebar-overlay');
            const toggle = document.getElementById('admin-sidebar-toggle');
            const closeBtn = document.getElementById('admin-sidebar-close');

            if (!sidebar || !overlay) {
                return;
            }

            const openSidebar = function() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            };

            const closeSidebar = function() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            };

            if (toggle) {
                toggle.addEventListener('click', openSidebar);
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', closeSidebar);
            }

            overlay.addEventListener('click', closeSidebar);

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    overlay.classList.add('hidden');
                    sidebar.classList.remove('-translate-x-full');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>