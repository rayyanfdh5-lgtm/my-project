<?php $__env->startSection('content'); ?>
<div class="form mx-auto space-y-8">
    
    <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <?php if (isset($component)) { $__componentOriginal26df7758e7d6f0f72a642df11f3034e4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26df7758e7d6f0f72a642df11f3034e4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin.components.partials.heading-section','data' => ['title' => 'Item Details','subtitle' => 'View detailed information about this item']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heading-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Item Details','subtitle' => 'View detailed information about this item']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal26df7758e7d6f0f72a642df11f3034e4)): ?>
<?php $attributes = $__attributesOriginal26df7758e7d6f0f72a642df11f3034e4; ?>
<?php unset($__attributesOriginal26df7758e7d6f0f72a642df11f3034e4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal26df7758e7d6f0f72a642df11f3034e4)): ?>
<?php $component = $__componentOriginal26df7758e7d6f0f72a642df11f3034e4; ?>
<?php unset($__componentOriginal26df7758e7d6f0f72a642df11f3034e4); ?>
<?php endif; ?>
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('admin.inventory.index')); ?>" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Inventory
            </a>
        </div>
    </div>

    
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900"><?php echo e($item->nama); ?></h3>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="space-y-4">
                    <?php if($item->gambar): ?>
                        
                        <div class="relative aspect-square w-full max-w-lg mx-auto overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                            <img src="<?php echo e(asset($item->gambar)); ?>" 
                                 alt="<?php echo e($item->nama); ?>" 
                                 class="h-full w-full object-cover">
                        </div>
                    <?php else: ?>
                        
                        <div class="aspect-square w-full max-w-lg mx-auto overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                            <div class="flex h-full w-full items-center justify-center">
                                <div class="rounded-full border-2 border-gray-200 p-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                        stroke-linecap="round" stroke-linejoin="round" class="text-gray-300">
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                        <circle cx="9" cy="9" r="2" />
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="space-y-6">
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                            <p class="text-lg font-semibold text-gray-900"><?php echo e($item->nama); ?></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <?php if($item->category): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <?php echo e($item->category->name); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">No category</span>
                                <?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <?php
                                    $rawType = null;
                                    try {
                                        $rawType = DB::table('items')->where('id', $item->id)->value('type');
                                    } catch (\Exception $e) {
                                        // Column might not exist yet
                                    }
                                    
                                    $typeLabel = 'Stok';
                                    $badgeClass = 'bg-orange-50 text-orange-700';
                                    
                                    if ($rawType === 'peminjaman') {
                                        $typeLabel = 'Peminjaman';
                                        $badgeClass = 'bg-blue-50 text-blue-700';
                                    }
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium <?php echo e($badgeClass); ?>">
                                    <?php echo e($typeLabel); ?>

                                </span>
                            </div>
                        </div>

                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock Information</label>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <?php if($rawType === 'peminjaman'): ?>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Borrowing Stock:</span>
                                        <span class="text-lg font-semibold text-blue-600"><?php echo e($item->stok_peminjaman ?? 0); ?> units</span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Regular Stock:</span>
                                        <span class="text-lg font-semibold text-green-600"><?php echo e($item->stok_reguler ?? 0); ?> units</span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex justify-between items-center border-t border-gray-200 pt-2">
                                    <span class="text-sm font-medium text-gray-700">Total Stock:</span>
                                    <span class="text-xl font-bold text-gray-900"><?php echo e($item->stok_total ?? $item->stok ?? 0); ?> units</span>
                                </div>
                            </div>
                        </div>

                        
                        <?php if($item->harga > 0): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <p class="text-2xl font-bold text-green-600">Rp <?php echo e(number_format($item->harga, 0, ',', '.')); ?></p>
                        </div>
                        <?php endif; ?>

                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <?php if($item->supplier): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <?php echo e($item->supplier->company_name); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-gray-400 italic">No supplier</span>
                            <?php endif; ?>
                        </div>

                        
                        <?php if($item->keterangan): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-600 leading-relaxed"><?php echo e($item->keterangan); ?></p>
                        </div>
                        <?php endif; ?>

                        
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                                <p class="text-sm text-gray-600"><?php echo e($item->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i')); ?> WIB</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                                <p class="text-sm text-gray-600"><?php echo e($item->updated_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i')); ?> WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
            <div class="flex items-center justify-between">
                <button type="button" onclick="openModal('delete-modal-<?php echo e($item->id); ?>')"
                    class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Item
                </button>

                <a href="<?php echo e(route('admin.inventory.edit', $item->id)); ?>"
                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Item
                </a>
            </div>
        </div>
    </div>

    
    <?php if (isset($component)) { $__componentOriginaleb8c89a7efda50450a3a76fb82e931fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaleb8c89a7efda50450a3a76fb82e931fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin.components.popup','data' => ['id' => 'delete-modal-'.e($item->id).'','title' => 'Delete Item','message' => 'Are you sure you want to delete \''.e($item->nama).'\'? This action cannot be undone and all data will be permanently removed.','formId' => 'delete-form-' . $item->id,'confirmText' => 'Delete','confirmClass' => 'text-sm link-primary delete-btn-color','cancelText' => 'Cancel']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('popup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'delete-modal-'.e($item->id).'','title' => 'Delete Item','message' => 'Are you sure you want to delete \''.e($item->nama).'\'? This action cannot be undone and all data will be permanently removed.','formId' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('delete-form-' . $item->id),'confirmText' => 'Delete','confirmClass' => 'text-sm link-primary delete-btn-color','cancelText' => 'Cancel']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaleb8c89a7efda50450a3a76fb82e931fd)): ?>
<?php $attributes = $__attributesOriginaleb8c89a7efda50450a3a76fb82e931fd; ?>
<?php unset($__attributesOriginaleb8c89a7efda50450a3a76fb82e931fd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaleb8c89a7efda50450a3a76fb82e931fd)): ?>
<?php $component = $__componentOriginaleb8c89a7efda50450a3a76fb82e931fd; ?>
<?php unset($__componentOriginaleb8c89a7efda50450a3a76fb82e931fd); ?>
<?php endif; ?>

    <form id="delete-form-<?php echo e($item->id); ?>" method="POST" action="<?php echo e(route('admin.inventory.destroy', $item->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>
</div>

<script>
// Image slider functionality removed - using single image system
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\artilia-main\resources\views/admin/contents/inventory/show.blade.php ENDPATH**/ ?>