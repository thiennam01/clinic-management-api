<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Clinic App')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-screen bg-slate-50">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('components.sidebar')


        {{-- Main area --}}
        <div class="flex min-w-0 flex-1 flex-col">

            {{-- Header --}}
            @include('components.header')


            {{-- Page content --}}
            <main class="flex-1 p-5 sm:p-6 lg:p-8">

                @yield('content')

            </main>

        </div>

    </div>

</body>

</html>