<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="icon" href="/artilia.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Sidebar Data Controller -->
    <script>
        function userSidebarData() {
            return {
                borrowingOpen: <?php echo e(Route::is('user.borrowing.*') ? 'true' : 'false'); ?>,
                init() {
                    console.log("✅ User Sidebar Alpine.js siap dipakai");
                }
            }
        }
    </script>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    
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
    <?php echo $__env->make('user.components.sidebar-user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Main Content Area -->
    <div class="main-content content-wrapper">
        <?php if (isset($component)) { $__componentOriginal964772bcf4504d310083bfc6ea248e5a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal964772bcf4504d310083bfc6ea248e5a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'user.components.header-user','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header-user'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal964772bcf4504d310083bfc6ea248e5a)): ?>
<?php $attributes = $__attributesOriginal964772bcf4504d310083bfc6ea248e5a; ?>
<?php unset($__attributesOriginal964772bcf4504d310083bfc6ea248e5a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal964772bcf4504d310083bfc6ea248e5a)): ?>
<?php $component = $__componentOriginal964772bcf4504d310083bfc6ea248e5a; ?>
<?php unset($__componentOriginal964772bcf4504d310083bfc6ea248e5a); ?>
<?php endif; ?>

        <main class="p-4 bg-gray-50 min-h-screen">
            <?php echo $__env->yieldContent('user'); ?>
        </main>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>

</html>
<?php /**PATH C:\laragon\www\artilia-main\resources\views/user/layouts/dashboard-user.blade.php ENDPATH**/ ?>