<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attagest - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-slate-900 text-white">
    <div class="min-h-screen flex flex-col">
        <!-- Top navigation -->
        @include('layouts.navigation')

        <!-- Alertes stocks critiques -->
        @auth
        <livewire:alertes-stock />
        @endauth

        <!-- Main content -->
        <main class="flex-1">
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>