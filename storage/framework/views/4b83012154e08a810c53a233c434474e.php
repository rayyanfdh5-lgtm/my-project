<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('build/assets/app.css')); ?>">
</head>
<body class="bg-gray-100">
    <nav class="bg-purple-600 p-4 text-white">
        <div class="container mx-auto">
            <span class="font-bold">Admin Panel</span>
        </div>
    </nav>
    <main class="container mx-auto py-8">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</body>
</html>
<?php /**PATH C:\laragon\www\artilia-main\resources\views/layouts/app.blade.php ENDPATH**/ ?>