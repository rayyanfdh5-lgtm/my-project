<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'formId' => null,
    'onConfirm' => null,
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel',
    'confirmClass' => 'text-sm link-primary ',
    'cancelClass' => 'text-sm link-secondary'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'formId' => null,
    'onConfirm' => null,
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel',
    'confirmClass' => 'text-sm link-primary ',
    'cancelClass' => 'text-sm link-secondary'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div id="<?php echo e($id); ?>" class="flex fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-70">
    <div class="w-96 rounded-lg bg-white p-6 shadow-lg">
        <div class="mb-2 flex items-center gap-x-2">
            <h3 class="text-left text-lg font-bold text-gray-800"><?php echo e($title); ?></h3>
        </div>
        <p class="text-left text-sm font-light text-gray-500"><?php echo e($message); ?></p>
        <hr class="my-2">
        <div class="mt-4 flex justify-end gap-2">
            <button onclick="closeModal('<?php echo e($id); ?>')" class="<?php echo e($cancelClass); ?>"><?php echo e($cancelText); ?></button>
            <?php if($onConfirm): ?>
                <button onclick="(<?php echo $onConfirm; ?>)(); closeModal('<?php echo e($id); ?>')" class="<?php echo e($confirmClass); ?>">
                    <?php echo e($confirmText); ?>

                </button>
            <?php elseif($formId): ?>
                <button onclick="document.getElementById('<?php echo e($formId); ?>').submit(); closeModal('<?php echo e($id); ?>')" class="<?php echo e($confirmClass); ?>">
                    <?php echo e($confirmText); ?>

                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('b3709fb1-f102-4235-94fe-8614043b73cb')): $__env->markAsRenderedOnce('b3709fb1-f102-4235-94fe-8614043b73cb'); ?>
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
<?php endif; ?>
<?php /**PATH C:\laragon\www\artilia-main\resources\views/admin/components/popup.blade.php ENDPATH**/ ?>