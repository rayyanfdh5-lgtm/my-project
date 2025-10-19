<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\Item;

class InvoiceService
{
    public function updateStatus(Invoice $invoice, array $data): void
    {
        $invoice->update([
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? null,
        ]);

        if (in_array($invoice->status, ['approved', 'paid'])) {
            $alreadyLogged = Inventory::where('item_id', $invoice->item_id)
                ->where('jumlah', $invoice->quantity)
                ->where('status', 'sold')
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if (! $alreadyLogged) {
                Inventory::create([
                    'item_id' => $invoice->item_id,
                    'tipe' => 'keluar',
                    'jumlah' => $invoice->quantity,
                    'status' => 'sold',
                ]);

                $invoice->item->decrement('stok', $invoice->quantity);
            }
        }
    }

    public function createInvoice(int $userId, int $itemId, int $quantity, ?string $notes = null): array
    {
        $item = Item::find($itemId);

        if (! $item) {
            return ['error' => true, 'message' => 'Item not found.'];
        }

        if ($quantity > $item->stok) {
            return ['error' => true, 'message' => 'Quantity exceeds available stock.'];
        }

        $latest = Invoice::latest('id')->first();
        $nextNumber = $latest ? $latest->id + 1 : 1;
        $nomorInvoice = 'INV/'.date('Y').'/'.date('m').'/'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Invoice::create([
            'user_id' => $userId,
            'item_id' => $itemId,
            'quantity' => $quantity,
            'nomor' => $nomorInvoice,
            'notes' => $notes,
            'status' => 'pending',
            'unit_price' => $item->harga ?? 0,
            'total_amount' => ($item->harga ?? 0) * $quantity,
        ]);

        return ['error' => false];
    }
}
