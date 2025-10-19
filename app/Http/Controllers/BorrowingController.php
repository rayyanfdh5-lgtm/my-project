<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Hanya tampilkan barang yang sedang dipinjam (dipinjam & terlambat)
        $query = Borrowing::with(['item', 'item.supplier', 'item.category', 'user'])
            ->whereIn('status', ['dipinjam', 'terlambat']);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }

        // Check for overdue items and update status
        Borrowing::where('status', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', now()->toDateString())
            ->update(['status' => 'terlambat']);

        $borrowings = $query->latest()->paginate(15);
        $users = User::select('id', 'name')
            ->where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return view('admin.contents.borrowings.index', compact('borrowings', 'users'));
    }


    /**
     * Display borrowing history (dikembalikan)
     */
    public function history(Request $request)
    {
        $query = Borrowing::with(['item', 'item.supplier', 'item.category', 'user'])
            ->where('status', 'dikembalikan');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }

        // Filter by return date range
        if ($request->filled('return_date_from')) {
            $query->whereDate('tanggal_kembali_aktual', '>=', $request->return_date_from);
        }
        if ($request->filled('return_date_to')) {
            $query->whereDate('tanggal_kembali_aktual', '<=', $request->return_date_to);
        }

        $borrowings = $query->latest('tanggal_kembali_aktual')->paginate(15);
        $users = User::select('id', 'name')
            ->where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return view('admin.contents.borrowings.history', compact('borrowings', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::with(['supplier:id,nama,company_name', 'category:id,name'])
            ->select('id', 'nama', 'stok_peminjaman', 'supplier_id', 'category_id', 'keterangan')
            ->where('stok_peminjaman', '>', 0)
            ->orderBy('nama')
            ->get();
        $users = User::select('id', 'name', 'email')
            ->where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return view('admin.contents.borrowings.create', compact('items', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:users,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'keterangan' => 'nullable|string',
            'kondisi_pinjam' => 'nullable|string',
        ]);

        // Check if item has enough borrowing stock
        $item = Item::findOrFail($request->item_id);
        $availableStock = $item->getAvailableStokForBorrowing();
        if ($availableStock < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok peminjaman tidak mencukupi. Stok tersedia: '.$availableStock]);
        }

        // Create borrowing record
        Borrowing::create($request->all());

        // Reduce borrowing stock
        $item->reduceStok($request->jumlah, 'peminjaman');

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Peminjaman berhasil dicatat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['item', 'item.supplier', 'item.category', 'user']);

        return view('admin.contents.borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        $items = Item::with(['supplier', 'category'])->orderBy('nama')->get();
        $users = User::select('id', 'name', 'email')
            ->where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return view('admin.contents.borrowings.edit', compact('borrowing', 'items', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'tanggal_kembali_aktual' => 'nullable|date',
            'status' => 'required|in:dipinjam,dikembalikan,terlambat',
            'kondisi_kembali' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $oldStatus = $borrowing->status;

        $borrowing->update($request->all());

        // If item is returned, add back to borrowing stock
        if ($oldStatus !== 'dikembalikan' && $request->status === 'dikembalikan') {
            $borrowing->item->addStok($borrowing->jumlah, 'peminjaman');
            $borrowing->update(['tanggal_kembali_aktual' => now()]);
        }

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Data peminjaman berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        // If item is still borrowed, return borrowing stock
        if ($borrowing->status === 'dipinjam' || $borrowing->status === 'terlambat') {
            $borrowing->item->addStok($borrowing->jumlah, 'peminjaman');
        }

        $borrowing->delete();

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Data peminjaman berhasil dihapus');
    }

    /**
     * Return borrowed item
     */
    public function returnItem(Borrowing $borrowing)
    {
        if ($borrowing->status === 'dikembalikan') {
            return back()->withErrors(['error' => 'Barang sudah dikembalikan']);
        }

        $borrowing->update([
            'status' => 'dikembalikan',
            'tanggal_kembali_aktual' => now(),
        ]);

        // Add back to borrowing stock
        $borrowing->item->addStok($borrowing->jumlah, 'peminjaman');

        return back()->with('success', 'Barang berhasil dikembalikan');
    }
}
