<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminBorrowingRequestController extends Controller
{
    public function index()
    {
        $requests = BorrowingRequest::with(['user', 'item', 'item.supplier', 'approvedBy'])
            ->latest()
            ->paginate(15);

        // Get status counts for all records (not just paginated)
        $pendingCount = BorrowingRequest::where('status', 'pending')->count();
        $approvedCount = BorrowingRequest::where('status', 'approved')->count();
        $rejectedCount = BorrowingRequest::where('status', 'rejected')->count();
        $completedCount = BorrowingRequest::where('status', 'completed')->count();

        return view('admin.contents.borrowing-requests.index', compact(
            'requests',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'completedCount'
        ));
    }

    public function show($id)
    {
        $request = BorrowingRequest::with(['user', 'item', 'item.supplier', 'item.category', 'approvedBy'])
            ->findOrFail($id);

        return view('admin.contents.borrowing-requests.show', compact('request'));
    }

    public function approve(Request $request, $id)
    {
        $borrowingRequest = BorrowingRequest::findOrFail($id);

        // Check if item has enough borrowing stock
        $item = $borrowingRequest->item;
        if ($item->stok_peminjaman < $borrowingRequest->jumlah) {
            return back()->withErrors(['error' => 'Stok peminjaman tidak mencukupi. Stok tersedia: '.$item->stok_peminjaman]);
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Update borrowing request status
        $borrowingRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        // Reduce borrowing stock
        $item->decrement('stok_peminjaman', $borrowingRequest->jumlah);

        return redirect()->route('admin.borrowing-requests.index')
            ->with('success', 'Permintaan peminjaman berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $borrowingRequest = BorrowingRequest::findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $borrowingRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        return redirect()->route('admin.borrowing-requests.index')
            ->with('success', 'Permintaan peminjaman berhasil ditolak.');
    }

    public function complete($id)
    {
        $borrowingRequest = BorrowingRequest::findOrFail($id);

        if ($borrowingRequest->status !== 'approved') {
            return back()->withErrors(['error' => 'Hanya permintaan yang disetujui yang dapat diselesaikan.']);
        }

        $borrowingRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Return borrowing stock
        $borrowingRequest->item->increment('stok_peminjaman', $borrowingRequest->jumlah);

        return redirect()->route('admin.borrowing-requests.index')
            ->with('success', 'Peminjaman berhasil diselesaikan dan stok dikembalikan.');
    }

    public function pending()
    {
        $requests = BorrowingRequest::with(['user', 'item', 'item.supplier'])
            ->pending()
            ->latest()
            ->paginate(15);

        return view('admin.contents.borrowing-requests.pending', compact('requests'));
    }
}
