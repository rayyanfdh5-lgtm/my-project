@extends('admin.layouts.dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Barang Keluar</h1>
                    <p class="text-sm text-gray-600 mt-1">Edit data barang yang keluar dari inventory</p>
                </div>
                <a href="{{ route('admin.outgoing.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form action="{{ route('admin.outgoing.update', $outgoingItem->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Edit Informasi Barang Keluar</h3>
            </div>
            

            <div class="px-6 py-6 space-y-6">

            {{-- User Selection --}}
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Diambil Oleh <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" 
                                    {{ old('user_id', $outgoingItem->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} - {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Item Selection --}}
                <div>
                    <label for="item_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Barang <span class="text-red-500">*</span>
                    </label>
                    <select name="item_id" id="item_id" required 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" 
                                    data-stock="{{ $item->stok_total }}"
                                    data-code="{{ $item->id ? 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT) : 'N/A' }}"
                                    data-supplier="{{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }}"
                                    data-category="{{ $item->category->name ?? 'N/A' }}"
                                    data-price="{{ $item->harga ?? 0 }}"
                                    data-description="{{ $item->keterangan ?? 'Tidak ada deskripsi' }}"
                                    {{ old('item_id', $outgoingItem->item_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }} (Stok: {{ $item->stok_total }}) - {{ $item->supplier->company_name ?? $item->supplier->nama ?? 'N/A' }} | {{ $item->category->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Item Details Display --}}
                <div id="itemDetails" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-900 mb-3">Detail Barang</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-blue-600 font-medium">Kode Barang:</span>
                            <span id="itemCode" class="ml-2 font-semibold text-blue-900"></span>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Supplier:</span>
                            <span id="supplierName" class="ml-2 font-semibold text-blue-900"></span>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Kategori:</span>
                            <span id="categoryName" class="ml-2 font-semibold text-blue-900"></span>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Stok Tersedia:</span>
                            <span id="availableStock" class="ml-2 font-semibold text-green-600"></span>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Harga Satuan:</span>
                            <span id="itemPrice" class="ml-2 font-semibold text-green-600"></span>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Total Nilai:</span>
                            <span id="totalValue" class="ml-2 font-semibold text-green-600"></span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-blue-200">
                        <div>
                            <span class="text-blue-600 font-medium">Deskripsi:</span>
                            <p id="itemDescription" class="mt-1 text-sm text-blue-800 italic"></p>
                        </div>
                    </div>
                </div>

                {{-- Quantity --}}
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Keluar <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" min="1" required
                           value="{{ old('jumlah', $outgoingItem->jumlah) }}"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           placeholder="Masukkan jumlah barang yang keluar">
                    @error('jumlah')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p id="stockWarning" class="mt-1 text-sm text-red-600 hidden">Jumlah melebihi stok yang tersedia!</p>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan (Opsional)
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                              placeholder="Tambahkan keterangan jika diperlukan...">{{ old('keterangan', $outgoingItem->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.outgoing.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Barang Keluar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM elements
    const elements = {
        itemSelect: document.getElementById('item_id'),
        itemDetails: document.getElementById('itemDetails'),
        itemCode: document.getElementById('itemCode'),
        supplierName: document.getElementById('supplierName'),
        categoryName: document.getElementById('categoryName'),
        availableStock: document.getElementById('availableStock'),
        itemPrice: document.getElementById('itemPrice'),
        totalValue: document.getElementById('totalValue'),
        itemDescription: document.getElementById('itemDescription'),
        jumlahInput: document.getElementById('jumlah'),
        stockWarning: document.getElementById('stockWarning')
    };

    let currentStock = 0;
    let currentPrice = 0;
    const originalQuantity = {{ $outgoingItem->jumlah }};
    const originalItemId = {{ $outgoingItem->item_id }};

    // Item selection handler
    function handleItemSelection() {
        const selectedOption = elements.itemSelect.options[elements.itemSelect.selectedIndex];
        
        if (selectedOption.value) {
            showItemDetails(selectedOption);
        } else {
            hideItemDetails();
        }
    }

    function showItemDetails(option) {
        // Show item details
        elements.itemDetails.classList.remove('hidden');
        
        // Update basic details
        elements.itemCode.textContent = option.dataset.code || 'N/A';
        elements.supplierName.textContent = option.dataset.supplier || 'N/A';
        elements.categoryName.textContent = option.dataset.category || 'N/A';
        elements.itemDescription.textContent = option.dataset.description || 'Tidak ada deskripsi';
        
        // Calculate available stock (add back original quantity if same item)
        let availableStockValue = parseInt(option.dataset.stock) || 0;
        if (option.value == originalItemId) {
            availableStockValue += originalQuantity;
        }
        
        elements.availableStock.textContent = availableStockValue + ' unit';
        currentStock = availableStockValue;
        
        // Update price
        currentPrice = parseInt(option.dataset.price) || 0;
        elements.itemPrice.textContent = currentPrice > 0 ? 'Rp ' + currentPrice.toLocaleString('id-ID') : 'Tidak ada harga';
        
        // Set max quantity
        elements.jumlahInput.max = currentStock;
        
        // Calculate total value
        calculateTotal();
        
        // Validate current quantity
        validateQuantity();
    }

    function hideItemDetails() {
        elements.itemDetails.classList.add('hidden');
        currentStock = 0;
        currentPrice = 0;
        elements.jumlahInput.max = '';
    }

    // Event listeners
    elements.itemSelect.addEventListener('change', handleItemSelection);

    // Handle quantity input
    elements.jumlahInput.addEventListener('input', function() {
        calculateTotal();
        validateQuantity();
    });

    function calculateTotal() {
        const quantity = parseInt(elements.jumlahInput.value) || 0;
        
        if (currentPrice > 0 && quantity > 0) {
            const total = currentPrice * quantity;
            elements.totalValue.textContent = 'Rp ' + total.toLocaleString('id-ID');
        } else {
            elements.totalValue.textContent = 'Rp 0';
        }
    }

    function validateQuantity() {
        const quantity = parseInt(elements.jumlahInput.value) || 0;
        
        if (currentStock > 0 && quantity > currentStock) {
            elements.stockWarning.classList.remove('hidden');
            elements.jumlahInput.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
            elements.jumlahInput.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
        } else {
            elements.stockWarning.classList.add('hidden');
            elements.jumlahInput.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
            elements.jumlahInput.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
        }
    }

    // Initialize if item already selected
    if (elements.itemSelect.value) {
        handleItemSelection();
    }
});
</script>
@endsection
