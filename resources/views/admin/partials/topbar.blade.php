<header class="sticky top-0 z-30 px-4 sm:px-6 lg:px-8 pt-4 pb-0">
    <div class="admin-surface rounded-2xl px-4 sm:px-6 py-4 admin-fade-up">
        <div class="flex items-center justify-between gap-3">
            <button id="admin-sidebar-toggle" type="button" class="admin-surface rounded-lg w-10 h-10 inline-flex items-center justify-center text-xl font-semibold lg:hidden" style="box-shadow:none;" aria-label="Open sidebar">
                ☰
            </button>

            <div>
                <h1 class="text-lg sm:text-xl font-bold">@yield('title', 'Admin Dashboard')</h1>
                <p class="text-sm" style="color: var(--admin-muted);">@yield('subtitle', 'Track platform performance and activities.')</p>
            </div>

            <div class="relative" id="admin-hamburger-menu-root">
                <button id="admin-hamburger-menu-button" type="button" class="admin-surface rounded-lg w-10 h-10 inline-flex items-center justify-center text-xl font-semibold" style="box-shadow:none;" aria-expanded="false" aria-controls="admin-hamburger-menu" aria-label="Open navigation menu">
                    ☰
                </button>

                <div id="admin-hamburger-menu" class="hidden absolute right-0 mt-2 w-64 admin-surface rounded-xl overflow-hidden z-40 opacity-0 -translate-y-2 pointer-events-none transition duration-200 ease-out">
                    <nav class="p-2">
                        <a href="{{ route('admin.profile') }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-100">Profile</a>
                        <a href="{{ route('admin.archive.index') }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-100">Archived</a>
                    </nav>

                    <form method="POST" action="{{ route('logout') }}" class="border-t p-2" style="border-color: var(--admin-border);" onsubmit="return confirm('Are you sure you want to logout?');">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium text-red-700 hover:bg-red-50">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('admin-hamburger-menu-button');
        const menuRoot = document.getElementById('admin-hamburger-menu-root');
        const menu = document.getElementById('admin-hamburger-menu');

        if (!menuButton || !menuRoot || !menu) return;

        const openMenu = function() {
            menu.classList.remove('hidden', 'opacity-0', '-translate-y-2', 'pointer-events-none');
            menu.classList.add('opacity-100', 'translate-y-0');
            menuButton.setAttribute('aria-expanded', 'true');
        };

        const closeMenu = function() {
            menu.classList.add('opacity-0', '-translate-y-2', 'pointer-events-none');
            menu.classList.remove('opacity-100', 'translate-y-0');
            menuButton.setAttribute('aria-expanded', 'false');

            setTimeout(function() {
                if (menuButton.getAttribute('aria-expanded') === 'false') {
                    menu.classList.add('hidden');
                }
            }, 180);
        };

        menuButton.addEventListener('click', function(event) {
            event.stopPropagation();
            const expanded = menuButton.getAttribute('aria-expanded') === 'true';
            if (expanded) {
                closeMenu();
                return;
            }

            openMenu();
        });

        document.addEventListener('click', function(event) {
            if (!menuRoot.contains(event.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') closeMenu();
        });
    });
</script>
@endpush