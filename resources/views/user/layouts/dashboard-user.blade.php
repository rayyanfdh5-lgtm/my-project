<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/artilia.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Sidebar Data Controller -->
    <script>
        function userSidebarData() {
            return {
                borrowingOpen: {{ Route::is('user.borrowing.*') ? 'true' : 'false' }},
                init() {
                    console.log("✅ User Sidebar Alpine.js siap dipakai");
                }
            }
        }
    </script>
    @stack('styles')
    @livewireStyles
    
    <style>
        /* Main content margin for sidebar */
        @media (min-width: 768px) {
            .main-content {
                margin-left: 16rem; /* 256px = w-64 */
            }
        }

        /* Prevent content from going under sidebar */
        .content-wrapper {
            transition: margin-left 0.3s ease-in-out;
        }

        /* Force scrolling behavior for sidebar navigation */
        .sidebar-navigation {
            height: calc(100vh - 64px - 80px); /* Full height minus header and footer */
            overflow-y: scroll !important;
            overflow-x: hidden;
            max-height: calc(100vh - 144px);
        }

        /* Enhanced scrollbar for navigation */
        .sidebar-navigation::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar-navigation::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .sidebar-navigation::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .sidebar-navigation::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Firefox scrollbar */
        .sidebar-navigation {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        /* Force sidebar container height */
        .sidebar-container {
            height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
        }
    </style>
</head>

<body class="min-h-screen bg-white">
    @include('user.components.sidebar-user')

    <!-- Main Content Area -->
    <div class="main-content content-wrapper">
        <x-header-user />

        <main class="p-4 bg-gray-50 min-h-screen">
            @yield('user')
        </main>
    </div>

    @stack('scripts')
    @livewireScripts
</body>

</html>
