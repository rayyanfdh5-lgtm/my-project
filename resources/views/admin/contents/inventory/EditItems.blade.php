@extends('admin.layouts.dashboard')

@section('content')
    <div class="form mx-auto space-y-8">
        {{-- Header Section --}}
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <x-heading-section title="Edit Items" subtitle="Change the old data into new Data" title-color="text-blue-600" subtitle-color="text-blue-400" />
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.inventory.index') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Inventory
                </a>
            </div>
        </div>

        {{-- Main Form --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <form method="POST"
                  action="{{ route('admin.inventory.update', $item->id) }}"
                  enctype="multipart/form-data"
                  x-data="{ 
                      selectedType: '{{ old('type', $item->type ?? 'stok') }}',
                      resetPriceOnTypeChange() {
                          if (this.selectedType === 'peminjaman') {
                              document.getElementById('harga').value = '';
                          }
                      }
                  }">
                @csrf
                @method('PUT')
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Update Item Information</h3>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div class="space-y-2">
                            <label for="nama" class="block text-sm font-medium text-gray-700">
                                Item Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nama"
                                   name="nama" 
                                   value="{{ old('nama', $item->nama) }}"
                                   class="input-form border-bluw-500"
                                   placeholder="Enter item name">
                            @error('nama')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2" x-show="selectedType === 'stok' || selectedType === 'peminjaman'" x-transition>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">
                                Category 
                                <span x-show="selectedType === 'stok'" class="text-red-500">*</span>
                                <span x-show="selectedType === 'peminjaman'" class="text-gray-400">(Optional)</span>
                            </label>
                            <select id="category_id" name="category_id" class="input-form border-bluw-500" :required="selectedType === 'stok'">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p x-show="selectedType === 'peminjaman'" class="text-xs text-gray-500">
                                Category is optional for borrowing items but can help with organization
                            </p>
                        </div>

                        <div class="space-y-2" x-show="selectedType === 'stok' || selectedType === 'peminjaman'" x-transition>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700">
                                Supplier 
                                <span x-show="selectedType === 'stok'" class="text-red-500">*</span>
                                <span x-show="selectedType === 'peminjaman'" class="text-gray-400">(Optional)</span>
                            </label>
                            <select id="supplier_id" name="supplier_id" class="input-form border-bluw-500" :required="selectedType === 'stok'">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $item->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->company_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p x-show="selectedType === 'peminjaman'" class="text-xs text-gray-500">
                                Supplier is optional for borrowing items but can help track item source
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label for="type" class="block text-sm font-medium text-gray-700">
                                Tipe Barang <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" class="input-form border-bluw-500" required x-model="selectedType" @change="resetPriceOnTypeChange()">
                                <option value="">Pilih Tipe Barang</option>
                                <option value="stok" {{ old('type', $item->type ?? '') == 'stok' ? 'selected' : '' }}>Stok</option>
                                <option value="peminjaman" {{ old('type', $item->type ?? '') == 'peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                            </select>
                            @error('type')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500">
                                Pilih "Stok" untuk barang yang dijual atau "Peminjaman" untuk barang yang dipinjamkan
                            </p>
                        </div>

                    </div>

                    {{-- Price Section --}}
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
                                   value="{{ old('harga', $item->harga ?? '') }}"
                                   class="input-form border-bluw-500 pl-12"
                                   placeholder="0.00">
                        </div>
                        @error('harga')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Multiple Image Upload Section --}}
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Product Image
                            <span class="text-sm text-gray-500 font-normal">(Single image)</span>
                        </label>
                        
                        {{-- Current Image Preview --}}
                        @if($item->gambar)
                            <div class="mb-4">
                                <img src="{{ asset($item->gambar) }}" alt="Current image" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-500 mt-1">Current image</p>
                            </div>
                        @endif

                        {{-- New Image Preview --}}
                        <div id="imagePreview" class="hidden mb-4">
                            <img id="previewImg" src="" alt="New image preview" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-500 mt-1">New image preview</p>
                        </div>
                        
                        {{-- Upload Area --}}
                        <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-2 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="text-sm text-gray-600">
                                    <label for="gambarInput" class="relative cursor-pointer rounded-md font-medium text-gray-900 hover:text-gray-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-gray-500">
                                        <span>Upload new image</span>
                                        <input id="gambarInput" 
                                               name="gambar" 
                                               type="file" 
                                               accept="image/*"
                                               onchange="previewImage(this)"
                                               class="sr-only">
                                    </label>
                                    <span class="pl-1">or drag and drop</span>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                <p class="text-xs text-blue-600">Replace current image</p>
                            </div>
                        </div>
                        @error('gambar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Multiple image preview disabled --}}
                    </div>

                    {{-- Description --}}
                    <div class="space-y-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea id="keterangan"
                                  name="keterangan" 
                                  rows="4" 
                                  class="input-form border-bluw-500 resize-none"
                                  placeholder="Enter item description...">{{ old('keterangan', $item->keterangan ?? '') }}</textarea>
                        @error('keterangan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">Provide a detailed description of the item (optional)</p>
                    </div>
                </div>

                {{-- Form Footer --}}
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
                            
                        </div>

                        <div class="flex items-center space-x-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Update Item
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

@endsection