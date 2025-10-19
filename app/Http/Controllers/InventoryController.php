<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;

use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Supplier;
// use App\Models\ItemImage; // Disabled - using single image system
use App\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'supplier']);

        // Handle search (support both 'query' and 'search' parameters)
        $searchTerm = $request->get('search') ?: $request->get('query');
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('keterangan', 'like', "%{$searchTerm}%")
                    ->orWhere('id', 'like', "%{$searchTerm}%"); // For direct ID search
                
                // Handle ITM-XXXX format search
                if (preg_match('/ITM-(\d+)/i', $searchTerm, $matches)) {
                    $itemId = (int) $matches[1]; // Extract number from ITM-0001
                    $q->orWhere('id', $itemId);
                }
                
                $q->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
                        $categoryQuery->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('supplier', function ($supplierQuery) use ($searchTerm) {
                        $supplierQuery->where('company_name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Handle type filter
        if ($request->has('type') && !empty($request->get('type'))) {
            $query->where('type', $request->get('type'));
        }

        // Handle category filter
        if ($request->has('category_id') && !empty($request->get('category_id'))) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Handle supplier filter
        if ($request->has('supplier_id') && !empty($request->get('supplier_id'))) {
            $query->where('supplier_id', $request->get('supplier_id'));
        }

        // Handle sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        switch ($sortBy) {
            case 'nama':
                $query->orderBy('nama', $sortDirection);
                break;
            case 'supplier':
                $query->join('suppliers', 'items.supplier_id', '=', 'suppliers.id')
                    ->orderBy('suppliers.nama', $sortDirection)
                    ->select('items.*');
                break;
            case 'category':
                $query->join('categories', 'items.category_id', '=', 'categories.id')
                    ->orderBy('categories.name', $sortDirection)
                    ->select('items.*');
                break;
            case 'stok':
                $query->orderBy('stok_total', $sortDirection);
                break;
            default:
                $query->orderBy('created_at', $sortDirection);
        }

        $items = $query->paginate(12)->withQueryString();

        // Get categories and suppliers for filter dropdowns
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('company_name')->get();

        // Calculate statistics for overview cards (from all items, not just paginated)
        $allItemsQuery = Item::query();
        
        // Apply same filters for statistics
        if (!empty($searchTerm)) {
            $allItemsQuery->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('keterangan', 'like', "%{$searchTerm}%")
                    ->orWhere('id', 'like', "%{$searchTerm}%");
                
                if (preg_match('/ITM-(\d+)/i', $searchTerm, $matches)) {
                    $itemId = (int) $matches[1];
                    $q->orWhere('id', $itemId);
                }
                
                $q->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
                        $categoryQuery->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('supplier', function ($supplierQuery) use ($searchTerm) {
                        $supplierQuery->where('company_name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        if ($request->has('type') && !empty($request->get('type'))) {
            $allItemsQuery->where('type', $request->get('type'));
        }

        if ($request->has('category_id') && !empty($request->get('category_id'))) {
            $allItemsQuery->where('category_id', $request->get('category_id'));
        }

        if ($request->has('supplier_id') && !empty($request->get('supplier_id'))) {
            $allItemsQuery->where('supplier_id', $request->get('supplier_id'));
        }

        $allItems = $allItemsQuery->get();
        
        // Calculate statistics
        $statistics = [
            'total_items' => $allItems->count(),
            'total_stock' => $allItems->sum('stok_total'),
            'low_stock' => $allItems->where('stok_total', '<=', 5)->where('stok_total', '>', 0)->count(),
            'out_of_stock' => $allItems->where('stok_total', 0)->count(),
        ];

        return view('admin.contents.inventory.index', [
            'items' => $items,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'statistics' => $statistics,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'searchQuery' => $request->get('query', ''),
        ]);
    }

    // Stock updates are now only allowed through the IncomingItemsController

    public function additems()
    {
        $categories = \App\Models\Category::active()->get();
        $suppliers = \App\Models\Supplier::active()->get();

        return view('admin.contents.inventory.AddItems', compact('categories', 'suppliers'));
    }

    public function store(StoreItemRequest $request)
    {
        $validatedData = $request->validated();
        
        // Set default values for new item
        $itemData = [
            'nama' => $validatedData['nama'],
            'category_id' => $validatedData['category_id'] ?? null,
            'supplier_id' => $validatedData['supplier_id'] ?? null,
            'keterangan' => $validatedData['keterangan'] ?? null,
            'type' => $validatedData['type'] ?? 'stok', // Default to 'stok' if not provided
            'stok_total' => 0, // Always start with 0 stock
            'stok_reguler' => 0,
            'stok_peminjaman' => 0,
            'harga' => ($validatedData['type'] ?? 'stok') === 'stok' ? ($validatedData['harga'] ?? 0) : 0,
        ];

        // Create the new item first
        $item = Item::create($itemData);

        // Handle multiple image uploads - disabled for now
        // if ($request->hasFile('images')) {
        //     $this->handleMultipleImageUpload($request->file('images'), $item);
        // }
        
        // Handle legacy single image upload for backward compatibility
        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            $gambar = $request->file('gambar');

            // Create images directory if it doesn't exist
            $imagesPath = public_path('images');
            if (!file_exists($imagesPath)) {
                mkdir($imagesPath, 0755, true);
            }

            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $gambar->getClientOriginalExtension();

            // Move file to public/images
            $gambar->move($imagesPath, $filename);

            // Update item with legacy image path
            $item->update(['gambar' => 'images/' . $filename]);
        }

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Item berhasil ditambahkan. Gunakan menu Barang Masuk untuk menambahkan stok.');
    }

    public function update(UpdateItemRequest $request, $id)
    {
        $item = Item::findOrFail($id);
        $validated = $request->validated();

        // Prepare update data
        $updateData = [
            'nama' => $validated['nama'],
            'keterangan' => $validated['keterangan'] ?? null,
            'type' => $validated['type'] ?? 'stok',
            'category_id' => $validated['category_id'] ?? null,
            'supplier_id' => $validated['supplier_id'] ?? null,
        ];

        // Handle price based on item type
        if (($validated['type'] ?? 'stok') === 'stok') {
            $updateData['harga'] = $validated['harga'] ?? 0;
        } else {
            $updateData['harga'] = 0; // Always 0 for borrowing items
        }

        // Update the item first
        $item->update($updateData);

        // Handle multiple image deletions - disabled for now
        // if ($request->has('delete_images') && is_array($request->delete_images)) {
        //     foreach ($request->delete_images as $imageId) {
        //         $image = ItemImage::where('id', $imageId)->where('item_id', $item->id)->first();
        //         if ($image) {
        //             // Delete physical file
        //             if (file_exists(public_path($image->image_path))) {
        //                 unlink(public_path($image->image_path));
        //             }
        //             // Delete database record
        //             $image->delete();
        //         }
        //     }
        // }

        // Handle multiple new image uploads - disabled for now
        // if ($request->hasFile('images')) {
        //     $this->handleMultipleImageUpload($request->file('images'), $item);
        // }

        // Handle legacy single image upload for backward compatibility
        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            // Delete old image if exists
            if ($item->gambar && file_exists(public_path($item->gambar))) {
                unlink(public_path($item->gambar));
            }
            
            // Create images directory if it doesn't exist
            $imagesPath = public_path('images');
            if (!file_exists($imagesPath)) {
                mkdir($imagesPath, 0755, true);
            }

            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $request->file('gambar')->getClientOriginalExtension();
            
            // Move file to public/images
            $request->file('gambar')->move($imagesPath, $filename);
            
            // Update item with legacy image path
            $item->update(['gambar' => 'images/' . $filename]);
        }

        // Check if request came from detail page
        $referer = $request->header('referer');
        if ($referer && str_contains($referer, '/inventory/tabs/detail')) {
            return redirect()->route('admin.inventory.tab.detail')
                ->with('success', 'Item berhasil diperbarui');
        }

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Item berhasil diperbarui');
    }

    public function show($id)
    {
        $item = Item::with(['category', 'supplier'])->findOrFail($id);
        return view('admin.contents.inventory.show', compact('item'));
    }
    
    public function edit($id)
    {
        $item = Item::with(['category', 'supplier'])->findOrFail($id);
        $categories = \App\Models\Category::active()->get();
        $suppliers = \App\Models\Supplier::active()->get();
        
        return view('admin.contents.inventory.EditItems', [
            'item' => $item,
            'categories' => $categories,
            'suppliers' => $suppliers
        ]);
    }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);
            
            \Log::info('Attempting to delete item', [
                'item_id' => $id,
                'item_name' => $item->nama,
                'stok_total' => $item->stok_total,
                'has_inventories' => $item->inventories()->exists()
            ]);
            
            // Check if item has stock or transactions
            if (($item->stok_total ?? 0) > 0) {
                \Log::warning('Delete blocked: item has stock', ['item_id' => $id, 'stock' => $item->stok_total]);
                return redirect()->route('admin.inventory.index')
                    ->with('error', 'Tidak dapat menghapus item yang masih memiliki stok. Stok saat ini: ' . $item->stok_total);
            }
            
            if ($item->inventories()->exists()) {
                \Log::warning('Delete blocked: item has transactions', ['item_id' => $id]);
                return redirect()->route('admin.inventory.index')
                    ->with('error', 'Tidak dapat menghapus item yang memiliki riwayat transaksi.');
            }
            
            // Delete image if exists
            if ($item->gambar && file_exists(public_path($item->gambar))) {
                unlink(public_path($item->gambar));
                \Log::info('Image deleted', ['image_path' => $item->gambar]);
            }
            
            // Hard delete the item
            $deleted = $item->delete();
            
            \Log::info('Item deleted', [
                'item_id' => $id,
                'delete_result' => $deleted,
                'item_exists' => Item::where('id', $id)->exists()
            ]);

            return redirect()->route('admin.inventory.index')
                ->with('success', 'Item "' . $item->nama . '" berhasil dihapus permanen');
                
        } catch (\Exception $e) {
            \Log::error('Error deleting item', [
                'item_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.inventory.index')
                ->with('error', 'Terjadi kesalahan saat menghapus item: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $items = Item::when($query, function ($q) use ($query) {
            $q->where('nama', 'like', "%{$query}%")
                ->orWhere('kode', 'like', "%{$query}%");
        })->latest()->get();

        if ($request->ajax()) {
            return view('admin.components.partials.itemlist', compact('items'))->render();
        }

        return redirect()->route('admin.inventory.index', ['query' => $query]);
    }

    /**
     * Handle multiple image upload for an item - DISABLED
     * Using single image system with 'gambar' column instead
     */
    // private function handleMultipleImageUpload($images, $item)
    // {
    //     // Create images directory if it doesn't exist
    //     $imagesPath = public_path('images/items');
    //     if (!file_exists($imagesPath)) {
    //         mkdir($imagesPath, 0755, true);
    //     }

    //     foreach ($images as $index => $image) {
    //         if ($image->isValid()) {
    //             // Generate unique filename
    //             $filename = $item->id . '_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                
    //             // Move file to public/images/items
    //             $image->move($imagesPath, $filename);
                
    //             // Create ItemImage record
    //             ItemImage::create([
    //                 'item_id' => $item->id,
    //                 'image_path' => 'images/items/' . $filename,
    //                 'sort_order' => $index,
    //                 'is_primary' => $index === 0 // First image is primary
    //             ]);
    //         }
    //     }
    // }
}
