<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Keluar</h1>
                    <p class="text-sm text-gray-600 mt-1">Catat barang yang keluar dari inventory</p>
                </div>
                <a href="<?php echo e(route('admin.outgoing.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form action="<?php echo e(route('admin.outgoing.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Barang Keluar</h3>
            </div>

            <div class="px-6 py-6 space-y-6">
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="user_select" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih User <span class="text-red-500">*</span>
                        </label>
                        <select name="user_select" id="user_select" required 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">-- Pilih User --</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" 
                                        data-name="<?php echo e($user->name); ?>"
                                        data-email="<?php echo e($user->email); ?>"
                                        <?php echo e(old('user_select') == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->name); ?> (ID: <?php echo e($user->id); ?>) - <?php echo e($user->email); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['user_select'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div id="selected_user_info" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">User Terpilih</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-blue-600 font-medium">Nama:</span>
                                <span id="selected_user_name" class="ml-2 font-semibold text-blue-900"></span>
                            </div>
                            <div>
                                <span class="text-blue-600 font-medium">ID:</span>
                                <span id="selected_user_id" class="ml-2 font-semibold text-blue-900"></span>
                            </div>
                            <div>
                                <span class="text-blue-600 font-medium">Email:</span>
                                <span id="selected_user_email" class="ml-2 font-semibold text-blue-900"></span>
                            </div>
                        </div>
                    </div>

                    
                    <input type="hidden" name="user_name" id="user_name" value="<?php echo e(old('user_name')); ?>">
                    <input type="hidden" name="user_id_input" id="user_id_input" value="<?php echo e(old('user_id_input')); ?>">
                    
                    <?php $__errorArgs = ['user_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['user_id_input'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700">
                            Daftar Barang <span class="text-red-500">*</span>
                        </label>
                        <button type="button" id="addItemBtn" 
                                class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Item
                        </button>
                    </div>

                    
                    <div id="itemsContainer" class="space-y-4">
                        
                    </div>

                    
                    <div id="summarySection" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Ringkasan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 font-medium">Total Item:</span>
                                <span id="totalItems" class="ml-2 font-semibold text-gray-900">0</span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">Total Quantity:</span>
                                <span id="totalQuantity" class="ml-2 font-semibold text-gray-900">0</span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">Total Nilai:</span>
                                <span id="grandTotal" class="ml-2 font-semibold text-green-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                
                <input type="hidden" name="status" value="to_production">

                
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan (Opsional)
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                              placeholder="Tambahkan keterangan jika diperlukan..."><?php echo e(old('keterangan')); ?></textarea>
                    <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <div class="flex items-center justify-end space-x-3">
                    <a href="<?php echo e(route('admin.outgoing.index')); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Barang Keluar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const elements = {
        userSelect: document.getElementById('user_select'),
        selectedUserInfo: document.getElementById('selected_user_info'),
        selectedUserName: document.getElementById('selected_user_name'),
        selectedUserId: document.getElementById('selected_user_id'),
        selectedUserEmail: document.getElementById('selected_user_email'),
        userNameInput: document.getElementById('user_name'),
        userIdInput: document.getElementById('user_id_input'),
        addItemBtn: document.getElementById('addItemBtn'),
        itemsContainer: document.getElementById('itemsContainer'),
        summarySection: document.getElementById('summarySection'),
        totalItems: document.getElementById('totalItems'),
        totalQuantity: document.getElementById('totalQuantity'),
        grandTotal: document.getElementById('grandTotal')
    };

    let itemCounter = 0;
    let selectedItems = {};

    // Available items data
    const itemsData = <?php echo json_encode($items->map(function($item) {
        return [
            'id' => $item->id,
            'name' => $item->nama,
            'stock' => $item->stok_total,
            'supplier' => $item->supplier->company_name ?? $item->supplier->nama ?? 'Tidak ada supplier',
            'category' => $item->category->name ?? 'Tidak ada kategori',
            'code' => 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
            'price' => $item->harga ?? 0,
            'description' => $item->keterangan ?? 'Tidak ada deskripsi'
        ];
    })); ?>;

    // User selection handler
    elements.userSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const hasValue = option.value;
        
        elements.selectedUserInfo.classList.toggle('hidden', !hasValue);
        
        if (hasValue) {
            elements.selectedUserName.textContent = option.dataset.name;
            elements.selectedUserId.textContent = option.value;
            elements.selectedUserEmail.textContent = option.dataset.email;
            elements.userNameInput.value = option.dataset.name;
            elements.userIdInput.value = option.value;
        } else {
            elements.userNameInput.value = '';
            elements.userIdInput.value = '';
        }
    });

    // Add item button handler
    elements.addItemBtn.addEventListener('click', function() {
        addItemRow();
    });

    function addItemRow() {
        itemCounter++;
        const rowId = `item-row-${itemCounter}`;
        
        const itemRow = document.createElement('div');
        itemRow.className = 'bg-white border border-gray-200 rounded-lg p-4';
        itemRow.id = rowId;
        
        itemRow.innerHTML = `
            <div class="flex items-start justify-between mb-4">
                <h5 class="text-sm font-medium text-gray-900">Item #${itemCounter}</h5>
                <button type="button" onclick="removeItemRow('${rowId}')" 
                        class="text-red-600 hover:text-red-800 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang</label>
                    <select name="items[${itemCounter}][item_id]" class="item-select block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" data-counter="${itemCounter}" required>
                        <option value="">-- Pilih Barang --</option>
                        ${itemsData.map(item => `
                            <option value="${item.id}" 
                                    data-stock="${item.stock}"
                                    data-price="${item.price}"
                                    data-name="${item.name}"
                                    data-code="${item.code}"
                                    data-supplier="${item.supplier}"
                                    data-category="${item.category}">
                                ${item.name} (Stok: ${item.stock}) - ${item.supplier}
                            </option>
                        `).join('')}
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="items[${itemCounter}][quantity]" 
                           class="quantity-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                           data-counter="${itemCounter}" min="1" placeholder="0" required>
                    <p class="stock-warning-${itemCounter} mt-1 text-sm text-red-600 hidden">Melebihi stok tersedia!</p>
                </div>
            </div>
            
            <div class="item-details-${itemCounter} hidden mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-sm">
                    <div>
                        <span class="text-blue-600 font-medium">Kode:</span>
                        <span class="item-code-${itemCounter} ml-1 font-semibold text-blue-900"></span>
                    </div>
                    <div>
                        <span class="text-blue-600 font-medium">Stok:</span>
                        <span class="item-stock-${itemCounter} ml-1 font-semibold text-green-600"></span>
                    </div>
                    <div>
                        <span class="text-blue-600 font-medium">Harga:</span>
                        <span class="item-price-${itemCounter} ml-1 font-semibold text-green-600"></span>
                    </div>
                    <div>
                        <span class="text-blue-600 font-medium">Total:</span>
                        <span class="item-total-${itemCounter} ml-1 font-semibold text-green-600">Rp 0</span>
                    </div>
                </div>
            </div>
        `;
        
        elements.itemsContainer.appendChild(itemRow);
        
        // Add event listeners for the new row
        const itemSelect = itemRow.querySelector('.item-select');
        const quantityInput = itemRow.querySelector('.quantity-input');
        
        itemSelect.addEventListener('change', function() {
            handleItemSelection(this);
        });
        
        quantityInput.addEventListener('input', function() {
            handleQuantityChange(this);
        });
        
        updateSummary();
    }

    function handleItemSelection(selectElement) {
        const counter = selectElement.dataset.counter;
        const option = selectElement.options[selectElement.selectedIndex];
        const hasValue = option.value;
        
        const detailsDiv = document.querySelector(`.item-details-${counter}`);
        detailsDiv.classList.toggle('hidden', !hasValue);
        
        if (hasValue) {
            const itemData = {
                id: option.value,
                name: option.dataset.name,
                stock: parseInt(option.dataset.stock) || 0,
                price: parseFloat(option.dataset.price) || 0,
                code: option.dataset.code,
                supplier: option.dataset.supplier,
                category: option.dataset.category
            };
            
            selectedItems[counter] = itemData;
            
            // Update display
            document.querySelector(`.item-code-${counter}`).textContent = itemData.code;
            document.querySelector(`.item-stock-${counter}`).textContent = itemData.stock + ' unit';
            document.querySelector(`.item-price-${counter}`).textContent = itemData.price > 0 ? 'Rp ' + itemData.price.toLocaleString('id-ID') : 'Rp 0';
            
            // Set quantity max
            const quantityInput = document.querySelector(`input[data-counter="${counter}"]`);
            quantityInput.max = itemData.stock;
            
            handleQuantityChange(quantityInput);
        } else {
            delete selectedItems[counter];
        }
        
        updateSummary();
    }

    function handleQuantityChange(quantityInput) {
        const counter = quantityInput.dataset.counter;
        const quantity = parseInt(quantityInput.value) || 0;
        const itemData = selectedItems[counter];
        
        if (itemData) {
            const total = quantity * itemData.price;
            document.querySelector(`.item-total-${counter}`).textContent = total > 0 ? 'Rp ' + total.toLocaleString('id-ID') : 'Rp 0';
            
            // Validate stock
            const isOverStock = quantity > itemData.stock;
            const warningElement = document.querySelector(`.stock-warning-${counter}`);
            warningElement.classList.toggle('hidden', !isOverStock);
            
            quantityInput.classList.toggle('border-red-300', isOverStock);
            quantityInput.classList.toggle('focus:border-red-500', isOverStock);
            quantityInput.classList.toggle('focus:ring-red-500', isOverStock);
            quantityInput.classList.toggle('border-gray-300', !isOverStock);
            quantityInput.classList.toggle('focus:border-blue-500', !isOverStock);
            quantityInput.classList.toggle('focus:ring-blue-500', !isOverStock);
        }
        
        updateSummary();
    }

    function updateSummary() {
        const itemRows = elements.itemsContainer.querySelectorAll('[id^="item-row-"]');
        let totalItems = itemRows.length;
        let totalQuantity = 0;
        let grandTotalValue = 0;
        
        itemRows.forEach(row => {
            const quantityInput = row.querySelector('.quantity-input');
            const quantity = parseInt(quantityInput.value) || 0;
            const counter = quantityInput.dataset.counter;
            const itemData = selectedItems[counter];
            
            if (itemData && quantity > 0) {
                totalQuantity += quantity;
                grandTotalValue += quantity * itemData.price;
            }
        });
        
        elements.totalItems.textContent = totalItems;
        elements.totalQuantity.textContent = totalQuantity;
        elements.grandTotal.textContent = grandTotalValue > 0 ? 'Rp ' + grandTotalValue.toLocaleString('id-ID') : 'Rp 0';
        
        elements.summarySection.classList.toggle('hidden', totalItems === 0);
    }

    // Global function to remove item row
    window.removeItemRow = function(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            const counter = row.querySelector('.item-select').dataset.counter;
            delete selectedItems[counter];
            row.remove();
            updateSummary();
        }
    };

    // Initialize with one item row
    addItemRow();
    
    // Initialize user selection if old value exists
    if (elements.userSelect.value) {
        elements.userSelect.dispatchEvent(new Event('change'));
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\artilia-main\resources\views/admin/contents/outgoing/create.blade.php ENDPATH**/ ?>