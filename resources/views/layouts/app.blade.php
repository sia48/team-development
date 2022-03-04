<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        @if(config('app.env') === 'production')
            <link rel="stylesheet" href="{{ secure_asset('team-developmet/public/css/app.css') }}">
        @else
            <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @endif
            
        @livewireStyles

        <!-- Scripts -->
        @if(config('app.env') === 'production')
            <script src="{{ secure_asset('team-developmet/public/js/app.css') }}" defer></script>
        @else
            <script src="{{ mix('js/app.js') }}" defer></script>
        @endif

    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
