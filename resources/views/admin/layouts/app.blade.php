<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
</head>

<body class="admin-shell h-full">
    <div class="min-h-screen flex">
        @include('admin.partials.sidebar')

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

    @stack('scripts')
</body>

</html>