@extends('admin.layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- Clean Header Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Inventory Management</h1>
                <p class="text-gray-600">Manage and organize your inventory items efficiently</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.inventory.add') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Item
                </a>
            </div>
        </div>
    </div>

    {{-- Inventory Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Total Items Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-6a2 2 0 00-2 2v3a2 2 0 002 2h6a2 2 0 002-2v-3a2 2 0 00-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Items</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_items'] }}</p>
                </div>
            </div>
        </div>

        {{-- Total Stock Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Stock</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['total_stock']) }}</p>
                </div>
            </div>
        </div>

        {{-- Low Stock Items Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Low Stock</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['low_stock'] }}</p>
                </div>
            </div>
        </div>

        {{-- Out of Stock Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['out_of_stock'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Clean Filter Section --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        {{-- Filter Header --}}
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Filter & Search</h3>
                    <p class="text-sm text-gray-600 mt-1">Find items quickly using the filters below</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                        {{ $items->total() }} results
                    </span>
                </div>
            </div>
        </div>
        
        {{-- Filter Form --}}
        <div class="p-6">
            <form method="GET" action="{{ route('admin.inventory.index') }}">
                {{-- Search Row --}}
                <div class="mb-6">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-3">Search Items</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Search by name, code, or ITM-XXXX...">
                </div>

                {{-- Filters Row --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    {{-- Category Filter --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-3">Category</label>
                        <select name="category_id" id="category_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Supplier Filter --}}
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-3">Supplier</label>
                        <select name="supplier_id" id="supplier_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Type Filter --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-3">Item Type</label>
                        <select name="type" id="type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">All Types</option>
                            <option value="stok" {{ request('type') == 'stok' ? 'selected' : '' }}>Stock Items</option>
                            <option value="peminjaman" {{ request('type') == 'peminjaman' ? 'selected' : '' }}>Borrowing Items</option>
                        </select>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.inventory.index') }}"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200 text-center">
                        Reset Filters
                    </a>
                    <button type="submit"
                        class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Clean Items Grid --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Inventory Items</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">{{ $items->total() }} items</span>
            </div>
        </div>

        @if($items->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
                @foreach($items as $item)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                        {{-- Item Image --}}
                        <div class="relative h-48 bg-gray-50">
                            @if($item->gambar)
                                <img src="{{ asset($item->gambar) }}" 
                                     alt="{{ $item->nama }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            {{-- Type Badge --}}
                            @if($item->type)
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium {{ $item->type->value === 'stok' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $item->type->label() }}
                                    </span>
                                </div>
                            @endif

                            {{-- Stock Status --}}
                            <div class="absolute top-3 left-3">
                                @if($item->stok_total == 0)
                                    <span class="px-2 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                @elseif($item->stok_total <= 5)
                                    <span class="px-2 py-1 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $item->stok_total }} units (Low)
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-800">
                                        {{ $item->stok_total }} units
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Item Details --}}
                        <div class="p-4">
                            <div class="mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">
                                        ITM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $item->nama }}</h4>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <span class="truncate">{{ $item->category->name ?? 'No Category' }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="truncate">{{ $item->supplier->company_name ?? 'No Supplier' }}</span>
                                </div>

                                {{-- Unit Price Display --}}
                                @if($item->type->value === 'stok' && $item->harga > 0)
                                    <div class="flex items-center text-sm text-green-600 font-medium">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span>Rp {{ number_format($item->harga, 0, '.', '.') }}</span>
                                    </div>
                                @elseif($item->type->value === 'peminjaman')
                                    <div class="flex items-center text-sm text-purple-600 font-medium">
                                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        <span>Peminjaman</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span>Tidak ada harga</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-2">
                                <a href="{{ route('admin.inventory.show', $item->id) }}"
                                    class="flex-1 px-3 py-2 text-center text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                    View
                                </a>
                                <a href="{{ route('admin.inventory.edit', $item->id) }}"
                                    class="flex-1 px-3 py-2 text-center text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Clean Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-medium">{{ $items->firstItem() ?? 0 }}</span> to 
                        <span class="font-medium">{{ $items->lastItem() ?? 0 }}</span> of 
                        <span class="font-medium">{{ $items->total() }}</span> results
                    </div>
                    <div class="flex justify-center">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        @else
            {{-- Clean Empty State --}}
            <div class="text-center py-16 px-6">
                <div class="max-w-sm mx-auto">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-6a2 2 0 00-2 2v3a2 2 0 002 2h6a2 2 0 002-2v-3a2 2 0 00-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Items Found</h3>
                    <p class="text-gray-600 mb-6">Your inventory is empty or no items match your current filters.</p>
                    <div class="space-y-3">
                        <a href="{{ route('admin.inventory.add') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Add Your First Item
                        </a>
                        <div>
                            <a href="{{ route('admin.inventory.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                                or clear all filters
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
