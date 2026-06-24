<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <a
    href="{{ auth()->check() ? route('projects.index') : route('login') }}"
                    class="text-xl font-bold text-slate-900"
                >
                    PRITECH Issue Tracker
                </a>

              @auth
    <nav class="flex items-center gap-4">
        <a
            href="{{ route('projects.index') }}"
            class="text-sm font-medium text-slate-600 hover:text-slate-900"
        >
            Projects
        </a>

        <a
            href="{{ route('issues.index') }}"
            class="text-sm font-medium text-slate-600 hover:text-slate-900"
        >
            Issues
        </a>

        <a
            href="{{ route('tags.index') }}"
            class="text-sm font-medium text-slate-600 hover:text-slate-900"
        >
            Tags
        </a>

        <span class="hidden text-sm text-slate-500 md:inline">
            {{ auth()->user()->name }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="text-sm font-medium text-red-600 hover:text-red-800"
            >
                Log out
            </button>
        </form>
    </nav>
@endauth
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 py-8">
            @include('partials.flash')

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>