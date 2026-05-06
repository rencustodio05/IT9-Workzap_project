<header class="sticky top-0 z-30 px-4 sm:px-6 lg:px-8 pt-4 pb-0">
    <div class="admin-surface rounded-2xl px-4 sm:px-6 py-4 admin-fade-up">
        <div class="flex flex-wrap items-center gap-3 sm:flex-nowrap">
            <button id="admin-sidebar-toggle" type="button" class="admin-surface rounded-lg w-10 h-10 shrink-0 inline-flex items-center justify-center text-xl font-semibold lg:hidden" style="box-shadow:none;" aria-label="Open sidebar">
                ☰
            </button>

            <div class="min-w-0 flex-1">
                <h1 class="text-lg sm:text-xl font-bold">@yield('title', 'Admin Dashboard')</h1>
                <p class="text-sm" style="color: var(--admin-muted);">@yield('subtitle', 'Track platform performance and activities.')</p>
            </div>
        </div>
    </div>