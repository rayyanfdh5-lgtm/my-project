<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/artilia.png">

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Sidebar Controller -->
    <script>
        function sidebarData() {
            return {
                masterDataOpen: {{ Route::is('admin.categories.*', 'admin.suppliers.*') ? 'true' : 'false' }},
                inventoryOpen: {{ Route::is('admin.inventory.*', 'admin.incoming.*', 'admin.outgoing.*') ? 'true' : 'false' }},
                borrowingOpen: {{ Route::is('admin.borrowings.*', 'admin.borrowing-requests.*') ? 'true' : 'false' }},
                usersOpen: {{ Route::is('admin.content.listusers', 'admin.content.createusers') ? 'true' : 'false' }},
                init() {
                    console.log("✅ Sidebar Alpine.js siap dipakai");
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#0d6efd">

    @stack('styles')

    <style>
        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #000; border-radius: 20px; }

        /* Sidebar Container */
        .sidebar-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 16rem; /* w-64 */
            height: 100vh;
            background: white;
            border-right: 1px solid #e5e7eb;
            z-index: 50;
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            right: 0;
            height: 64px;
            z-index: 40;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Content Area */
        .main-content {
            transition: margin-left 0.3s ease-in-out;
        }

        /* Desktop layout (>=768px) */
        @media (min-width: 768px) {
            header {
                margin-left: 16rem; /* same as sidebar width */
                width: calc(100% - 16rem);
            }
            .main-content {
                margin-left: 16rem;
                padding-top: 5rem; /* header height */
            }
        }

        /* Mobile layout (<768px) */
        @media (max-width: 767px) {
            .sidebar-container {
                display: none;
            }
            header {
                margin-left: 0;
                width: 100%;
            }
            .main-content {
                margin-left: 0;
                padding-top: 4.5rem;
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Sidebar -->
    <aside class="sidebar-container">
        @include('admin.components.sidebar-new')
    </aside>

    <!-- Header -->
    <header>
        <x-header />
    </header>

    <!-- Main Content -->
    <main class="main-content p-4">
        @yield('content')
    </main>

    @stack('scripts')

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(() => console.log('Service Worker registered'))
                .catch(error => console.log('SW failed:', error));
        }
    </script>
</body>
</html>
