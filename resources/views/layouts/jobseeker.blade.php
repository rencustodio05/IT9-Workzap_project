<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Jobseeker Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 flex min-h-screen">
    <!-- Sidebar -->
    <aside class="bg-blue-900 text-white w-64 flex flex-col p-6 space-y-4 min-h-screen">
        <div class="mb-8">
            <span class="text-2xl font-bold tracking-wide">Workzap <span class="text-yellow-400">Job seeker</span></span>
        </div>
        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('jobseeker.dashboard') }}"
                class="py-2 px-4 rounded {{ request()->routeIs('jobseeker.dashboard') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-800' }}">
                Dashboard
            </a>
            <a href="{{ route('jobseeker.jobs.index') }}"
                class="py-2 px-4 rounded {{ request()->routeIs('jobseeker.jobs.index') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-800' }}">
                Browse Jobs
            </a>
            <a href="{{ route('jobseeker.applications.index') }}"
                class="py-2 px-4 rounded {{ request()->routeIs('jobseeker.applications.index') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-800' }}">
                My Applications
            </a>
            <a href="{{ route('jobseeker.saved.index') }}"
                class="py-2 px-4 rounded {{ request()->routeIs('jobseeker.saved.index') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-800' }}">
                Saved Jobs
            </a>
            <a href="{{ route('jobseeker.profile') }}"
                class="py-2 px-4 rounded {{ request()->routeIs('jobseeker.profile') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-800' }}">
                My Profile
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit"
                    class="w-full py-2 px-4 rounded text-left hover:bg-red-600 {{ request()->routeIs('logout') ? 'bg-red-700 font-semibold' : '' }}">
                    Logout
                </button>
            </form>
        </nav>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 p-8">
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>