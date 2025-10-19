<?php $__env->startSection('content'); ?>
    <div class="form mx-auto space-y-8">
        
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">

            <?php if (isset($component)) { $__componentOriginal26df7758e7d6f0f72a642df11f3034e4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal26df7758e7d6f0f72a642df11f3034e4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin.components.partials.heading-section','data' => ['title' => 'Add Item','subtitle' => 'Create a new inventory item with all necessary details']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heading-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Add Item','subtitle' => 'Create a new inventory item with all necessary details']); ?>
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

        
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <form method="POST" action="<?php echo e(route('admin.inventory.store')); ?>"
                  enctype="multipart/form-data"
                  x-data="{ 
                      selectedType: 'stok',
                      resetPriceOnTypeChange() {
                          if (this.selectedType === 'peminjaman') {
                              document.getElementById('harga').value = '';
                          }
                      }
                  }">
                <?php echo csrf_field(); ?>
                
                <?php if($errors->any()): ?>
                    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Ada kesalahan:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Item Information</h3>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div class="space-y-2">
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                Item Name
                            </label>
                            <input type="text" 
                                   name="nama" 
                                   id="nama" 
                                   value="<?php echo e(old('nama')); ?>"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="Enter item name">
                            <?php $__errorArgs = ['nama'];
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

                        <div class="space-y-2">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">
                                Category
                                <span x-show="selectedType === 'stok'" class="text-red-500">*</span>
                                <span x-show="selectedType === 'peminjaman'" class="text-gray-400">(Optional)</span>
                            </label>
                            <select id="category_id" name="category_id" class="input-form">
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700">
                                Supplier
                                <span x-show="selectedType === 'stok'" class="text-red-500">*</span>
                                <span x-show="selectedType === 'peminjaman'" class="text-gray-400">(Optional)</span>
                            </label>
                            <select id="supplier_id" name="supplier_id" class="input-form">
                                <option value="">Select Supplier</option>
                                <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($supplier->id); ?>" <?php echo e(old('supplier_id') == $supplier->id ? 'selected' : ''); ?>>
                                        <?php echo e($supplier->company_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['supplier_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="space-y-2">
                            <label for="type" class="block text-sm font-medium text-gray-700">
                                Item Type <span class="text-red-500">*</span>
                            </label>
                            <select id="type" 
                                    name="type" 
                                    x-model="selectedType"
                                    @change="resetPriceOnTypeChange()"
                                    required
                                    class="input-form">
                                <option value="stok">Stok (For Sale)</option>
                                <option value="peminjaman">Peminjaman (For Lending)</option>
                            </select>
                            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>    
                        <p class="text-xs text-gray-500">
                            Pilih "Stok" untuk barang yang dijual atau "Peminjaman" untuk barang yang dipinjamkan
                        </p>
                    </div>

                    
                    <div class="space-y-2" x-show="selectedType === 'stok'" x-transition>
                        <label for="harga" class="block text-sm font-medium text-gray-700">
                            Price <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" 
                                   id="harga"
                                   name="harga" 
                                   min="0"
                                   max="999999999"
                                   step="0.01"
                                   value="<?php echo e(old('harga')); ?>"
                                   class="input-form pl-12"
                                   placeholder="0.00">
                        </div>
                        <?php $__errorArgs = ['harga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="rounded-md bg-blue-50 p-4 border border-blue-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Informasi Stok</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Item baru akan dibuat dengan stok awal 0. Untuk menambahkan stok, gunakan menu <strong>"Barang Masuk"</strong> setelah item berhasil ditambahkan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Product Image
                            <span class="text-sm text-gray-500 font-normal">(Single image)</span>
                        </label>
                        
                        
                        <div id="imagePreview" class="hidden mb-4">
                            <img id="previewImg" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-500 mt-1">Selected image</p>
                        </div>

                        
                        <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-2 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="text-sm text-gray-600">
                                    <label for="gambarInput" class="relative cursor-pointer rounded-md font-medium text-gray-900 hover:text-gray-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-gray-500">
                                        <span>Upload files</span>
                                        <input id="gambarInput" 
                                               name="gambar" 
                                               type="file" 
                                               accept="image/*"
                                               onchange="previewImage(this)"
                                               class="sr-only">
                                    </label>
                                    <span class="pl-1">or drag and drop</span>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                                <p class="text-xs text-blue-600">Select single image for this item</p>
                            </div>
                        </div>
                        <?php $__errorArgs = ['gambar'];
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

                    
                    <div class="space-y-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea id="keterangan"
                                  name="keterangan" 
                                  rows="4" 
                                  class="input-form resize-none"
                                  placeholder="Enter item description..."><?php echo e(old('keterangan')); ?></textarea>
                        <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-xs text-gray-500">Provide a detailed description of the item (optional)</p>
                    </div>
                </div>

                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button type="button" 
                                    onclick="window.history.back()"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel
                            </button>
                            
                            
                            <?php if(isset($updatedItem)): ?>
                                <button type="button" 
                                        onclick="document.getElementById('resetForm').submit()"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Reset
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="flex items-center space-x-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add Item
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Validate price input
        document.addEventListener('DOMContentLoaded', function() {
            const hargaInput = document.getElementById('harga');
            if (hargaInput) {
                hargaInput.addEventListener('input', function() {
                    const value = parseFloat(this.value);
                    if (value > 999999999) {
                        this.setCustomValidity('Harga tidak boleh lebih dari Rp 999.999.999');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            }
        });
    </script>

    <style>
        /* Custom file input styling */
        input[type="file"]::-webkit-file-upload-button {
            visibility: hidden;
        }

        input[type="file"]::before {
            content: 'Choose file';
            display: inline-block;
            background: linear-gradient(top, #f9f9f9, #e3e3e3);
            border: 1px solid #999;
            border-radius: 3px;
            padding: 5px 8px;
            outline: none;
            white-space: nowrap;
            cursor: pointer;
            text-shadow: 1px 1px #fff;
            font-weight: 700;
            font-size: 10pt;
        }

        input[type="file"]:hover::before {
            border-color: black;
        }

        input[type="file"]:active::before {
            background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
        }

        /* Remove number input arrows */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\artilia-main\resources\views/admin/contents/inventory/AddItems.blade.php ENDPATH**/ ?>