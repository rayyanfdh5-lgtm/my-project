<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.contents.reports.index');
    }

    public function inventoryReport(Request $request)
    {
        $query = Item::with(['supplier', 'category'])
            ->select('items.*');

        // Filter by supplier if provided
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by category if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by type if provided
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Handle sorting
        $sortBy = $request->get('sort_by', 'nama');
        $sortDirection = $request->get('sort_direction', 'asc');

        switch ($sortBy) {
            case 'nama':
                $query->orderBy('nama', $sortDirection);
                break;
            case 'supplier':
                $query->leftJoin('suppliers', 'items.supplier_id', '=', 'suppliers.id')
                    ->orderBy('suppliers.nama', $sortDirection)
                    ->select('items.*');
                break;
            case 'category':
                $query->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                    ->orderBy('categories.name', $sortDirection)
                    ->select('items.*');
                break;
            case 'stok':
                $query->orderBy('stok_total', $sortDirection);
                break;
            default:
                $query->orderBy('nama', $sortDirection);
        }

        $items = $query->get();
        
        // Reload relationships if JOIN was used
        if (in_array($sortBy, ['supplier', 'category'])) {
            $items->load(['supplier', 'category']);
        }
        
        $suppliers = Supplier::all();
        $categories = Category::all();

        if ($request->has('export_pdf')) {
            return $this->exportInventoryPDF($items, $request);
        }

        if ($request->has('export_excel')) {
            return $this->exportInventoryExcel($items, $request);
        }

        return view('admin.contents.reports.inventory', compact('items', 'suppliers', 'categories', 'sortBy', 'sortDirection'));
    }

    public function outgoingReport(Request $request)
    {
        $query = Inventory::with(['item', 'item.supplier', 'item.category', 'user'])
            ->where('tipe', 'keluar');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by status if needed (commented out to show all outgoing items)
        // $query->where('status', 'to_production');

        $outgoingItems = $query->latest()->get();

        if ($request->has('export_pdf')) {
            return $this->exportOutgoingPDF($outgoingItems, $request);
        }

        if ($request->has('export_excel')) {
            return $this->exportOutgoingExcel($outgoingItems, $request);
        }

        return view('admin.contents.reports.outgoing', compact('outgoingItems'));
    }

    public function incomingReport(Request $request)
    {
        $query = Inventory::with(['item', 'item.supplier', 'item.category'])
            ->where('tipe', 'masuk');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $incomingItems = $query->latest()->get();

        if ($request->has('export_pdf')) {
            return $this->exportIncomingPDF($incomingItems, $request);
        }

        if ($request->has('export_excel')) {
            return $this->exportIncomingExcel($incomingItems, $request);
        }

        return view('admin.contents.reports.incoming', compact('incomingItems'));
    }

    public function suppliersReport(Request $request)
    {
        $suppliers = Supplier::withCount('items')->get();

        if ($request->has('export_pdf')) {
            return $this->exportSuppliersPDF($suppliers);
        }

        if ($request->has('export_excel')) {
            return $this->exportSuppliersExcel($suppliers);
        }

        return view('admin.contents.reports.suppliers', compact('suppliers'));
    }

    private function exportInventoryPDF($items, $request)
    {
        // Add item codes to items
        $items->each(function ($item) {
            $item->kode_barang = 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
        });

        $data = [
            'title' => 'Laporan Persediaan Barang',
            'date' => Carbon::now()->format('d F Y'),
            'items' => $items,
            'supplier_id' => $request->supplier_id,
            'category_id' => $request->category_id,
            'total_items' => $items->count(),
            'total_stock' => $items->sum(function ($item) {
                return $item->stok_total ?? 0;
            }),
            'total_value' => $items->sum(function ($item) {
                return ($item->stok_total ?? 0) * ($item->harga ?? 0);
            }),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.inventory', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);

        return $pdf->download('laporan-persediaan-'.date('Y-m-d').'.pdf');
    }

    private function exportOutgoingPDF($items, $request)
    {
        // Add item codes to items
        $items->each(function ($item) {
            if ($item->item) {
                $item->item->kode_barang = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT);
            }
        });

        $data = [
            'title' => 'Laporan Barang Keluar',
            'date' => Carbon::now()->format('d F Y'),
            'items' => $items,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'status' => $request->status,
            'total_items' => $items->count(),
            'total_quantity' => $items->sum('jumlah'),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.outgoing', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);

        return $pdf->download('laporan-barang-keluar-'.date('Y-m-d').'.pdf');
    }

    private function exportIncomingPDF($items, $request)
    {
        // Add item codes to items
        $items->each(function ($item) {
            if ($item->item) {
                $item->item->kode_barang = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT);
            }
        });

        $data = [
            'title' => 'Laporan Barang Masuk',
            'date' => Carbon::now()->format('d F Y'),
            'items' => $items,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'total_items' => $items->count(),
            'total_quantity' => $items->sum('jumlah'),
            'unique_items' => $items->pluck('item_id')->unique()->count(),
            'total_value' => $items->sum(function ($item) {
                return $item->jumlah * $item->item->harga;
            }),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.incoming', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);

        return $pdf->download('laporan-barang-masuk-'.date('Y-m-d').'.pdf');
    }

    private function exportSuppliersPDF($suppliers)
    {
        $data = [
            'title' => 'Laporan Supplier',
            'date' => Carbon::now()->format('d F Y'),
            'suppliers' => $suppliers,
            'total_suppliers' => $suppliers->count(),
            'active_suppliers' => $suppliers->where('status', 'active')->count(),
            'inactive_suppliers' => $suppliers->where('status', 'inactive')->count(),
            'total_items' => $suppliers->sum('items_count'),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.suppliers', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);

        return $pdf->download('laporan-supplier-'.date('Y-m-d').'.pdf');
    }

    // Excel Export Methods (Format Tabel Profesional)
    private function exportInventoryExcel($items, $request)
    {
        $filename = 'laporan-persediaan-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($items, $request) {
            // Start HTML Excel format
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<style>';
            echo 'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }';
            echo '.header-company { background-color: #2E86AB; color: white; font-weight: bold; text-align: center; padding: 8px; font-size: 14px; }';
            echo '.header-info { background-color: #A23B72; color: white; font-weight: bold; text-align: center; padding: 6px; font-size: 12px; }';
            echo '.header-title { background-color: #F18F01; color: white; font-weight: bold; text-align: center; padding: 10px; font-size: 16px; }';
            echo '.summary { background-color: #C73E1D; color: white; font-weight: bold; padding: 6px; font-size: 11px; }';
            echo '.table-header { background-color: #4472C4; color: white; font-weight: bold; text-align: center; padding: 8px; border: 1px solid #333; }';
            echo '.table-data { padding: 6px; border: 1px solid #ccc; text-align: center; }';
            echo '.table-data-left { padding: 6px; border: 1px solid #ccc; text-align: left; }';
            echo '.table-data-right { padding: 6px; border: 1px solid #ccc; text-align: right; }';
            echo '.footer-note { background-color: #E8F4FD; padding: 6px; font-size: 10px; border: 1px solid #ccc; }';
            echo '.total-row { background-color: #FFE699; font-weight: bold; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<table>';
            
            // Company Header
            echo '<tr><td colspan="10" class="header-company">PT. ARTILIA - INVENTORY MANAGEMENT SYSTEM</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Jl. Cipendeu No. 123, Jakarta Selatan 12345</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Telp: (021) 1234567 | Email: info@artilia.com</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Website: www.artilia.com | NPWP: 01.234.567.8-901.000</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            // Report Title
            echo '<tr><td colspan="10" class="header-title">LAPORAN PERSEDIAAN BARANG</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Tanggal Cetak: ' . date('d F Y H:i:s') . '</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Dicetak Oleh: ' . (auth()->user()->name ?? 'SYSTEM') . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            // Summary Statistics
            $totalItems = $items->count();
            $totalStock = $items->sum(function($item) { return $item->stok_total ?? 0; });
            $totalValue = $items->sum(function($item) { return ($item->stok_total ?? 0) * ($item->harga ?? 0); });
            $lowStockItems = $items->filter(function($item) { return ($item->stok_total ?? 0) < 10 && ($item->stok_total ?? 0) > 0; })->count();
            $outOfStockItems = $items->filter(function($item) { return ($item->stok_total ?? 0) == 0; })->count();
            
            // Summary Statistics
            echo '<tr><td colspan="10" class="summary">RINGKASAN LAPORAN:</td></tr>';
            echo '<tr><td colspan="10" class="summary">Total Item: ' . number_format($totalItems) . '</td></tr>';
            echo '<tr><td colspan="10" class="summary">Total Stok: ' . number_format($totalStock) . '</td></tr>';
            echo '<tr><td colspan="10" class="summary">Total Nilai: Rp ' . number_format($totalValue, 0, ',', '.') . '</td></tr>';
            echo '<tr><td colspan="10" class="summary">Item Stok Rendah: ' . number_format($lowStockItems) . '</td></tr>';
            echo '<tr><td colspan="10" class="summary">Item Habis: ' . number_format($outOfStockItems) . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 15px;"></td></tr>';
            
            // Table Header
            echo '<tr>';
            echo '<td class="table-header">No</td>';
            echo '<td class="table-header">Kode Barang</td>';
            echo '<td class="table-header">Nama Barang</td>';
            echo '<td class="table-header">Supplier</td>';
            echo '<td class="table-header">Kategori</td>';
            echo '<td class="table-header">Stok Total</td>';
            echo '<td class="table-header">Harga Satuan</td>';
            echo '<td class="table-header">Total Nilai</td>';
            echo '<td class="table-header">Status</td>';
            echo '<td class="table-header">Tipe</td>';
            echo '</tr>';

            // Data
            $totalNilaiKeseluruhan = 0;
            foreach ($items as $index => $item) {
                $kodeBarang = 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
                $stokTotal = $item->stok_total ?? 0;
                $harga = $item->harga ?? 0;
                $totalNilai = $stokTotal * $harga;
                $totalNilaiKeseluruhan += $totalNilai;

                // Status
                if ($stokTotal == 0) {
                    $status = 'Habis';
                    $statusClass = 'background-color: #FF6B6B; color: white;';
                } elseif ($stokTotal < 10) {
                    $status = 'Stok Rendah';
                    $statusClass = 'background-color: #FFE66D; color: black;';
                } else {
                    $status = 'Tersedia';
                    $statusClass = 'background-color: #4ECDC4; color: white;';
                }

                // Type
                $type = 'Stok';
                if ($item->type) {
                    if (method_exists($item->type, 'label')) {
                        $type = $item->type->label();
                    } elseif (is_object($item->type) && property_exists($item->type, 'value')) {
                        $type = ucfirst($item->type->value);
                    } else {
                        $type = ucfirst($item->type);
                    }
                }

                echo '<tr>';
                echo '<td class="table-data">' . ($index + 1) . '</td>';
                echo '<td class="table-data">' . $kodeBarang . '</td>';
                echo '<td class="table-data-left">' . ($item->nama ?? 'N/A') . '</td>';
                echo '<td class="table-data-left">' . ($item->supplier->nama ?? 'N/A') . '</td>';
                echo '<td class="table-data-left">' . ($item->category->name ?? 'N/A') . '</td>';
                echo '<td class="table-data">' . number_format($stokTotal) . '</td>';
                echo '<td class="table-data-right">Rp ' . number_format($harga, 0, ',', '.') . '</td>';
                echo '<td class="table-data-right">Rp ' . number_format($totalNilai, 0, ',', '.') . '</td>';
                echo '<td class="table-data" style="' . $statusClass . '">' . $status . '</td>';
                echo '<td class="table-data">' . $type . '</td>';
                echo '</tr>';
            }

            // Footer
            echo '<tr><td colspan="10" style="height: 15px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer-note"><strong>KETERANGAN:</strong></td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Status Tersedia: Stok >= 10 unit</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Status Stok Rendah: Stok 1-9 unit</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Status Habis: Stok 0 unit</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            echo '<tr><td colspan="10" class="footer-note">Laporan ini digenerate otomatis oleh sistem pada: ' . date('d F Y H:i:s') . '</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">Total record yang ditampilkan: ' . number_format($totalItems) . ' item</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            echo '<tr><td colspan="10" class="footer-note"><strong>PT. ARTILIA - Inventory Management System</strong></td></tr>';
            echo '<tr><td colspan="10" class="footer-note">Untuk pertanyaan terkait laporan ini, hubungi IT Department</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">Email: support@artilia.com | Telp: (021) 1234567 ext. 101</td></tr>';
            
            echo '</table>';
            echo '</body>';
            echo '</html>';
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportOutgoingExcel($items, $request)
    {
        $filename = 'laporan-barang-keluar-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($items, $request) {
            // Start HTML Excel format
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<style>';
            echo 'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }';
            echo '.header-company { background-color: #2E86AB; color: white; font-weight: bold; text-align: center; padding: 8px; font-size: 14px; }';
            echo '.header-info { background-color: #A23B72; color: white; font-weight: bold; text-align: center; padding: 6px; font-size: 12px; }';
            echo '.header-title { background-color: #F18F01; color: white; font-weight: bold; text-align: center; padding: 10px; font-size: 16px; }';
            echo '.summary { background-color: #C73E1D; color: white; font-weight: bold; padding: 6px; font-size: 11px; }';
            echo '.table-header { background-color: #E74C3C; color: white; font-weight: bold; text-align: center; padding: 8px; border: 1px solid #333; }';
            echo '.table-data { padding: 6px; border: 1px solid #ccc; text-align: center; }';
            echo '.table-data-left { padding: 6px; border: 1px solid #ccc; text-align: left; }';
            echo '.table-data-right { padding: 6px; border: 1px solid #ccc; text-align: right; }';
            echo '.footer-note { background-color: #E8F4FD; padding: 6px; font-size: 10px; border: 1px solid #ccc; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<table>';
            
            // Company Header
            echo '<tr><td colspan="10" class="header-company">PT. ARTILIA - INVENTORY MANAGEMENT SYSTEM</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Jl. Cipendeu No. 123, Jakarta Selatan 12345</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Telp: (021) 1234567 | Email: info@artilia.com</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Website: www.artilia.com | NPWP: 01.234.567.8-901.000</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            // Report Title
            echo '<tr><td colspan="10" class="header-title">LAPORAN BARANG KELUAR</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Tanggal Cetak: ' . date('d F Y H:i:s') . '</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Dicetak Oleh: ' . (auth()->user()->name ?? 'SYSTEM') . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            // Summary Statistics
            $totalTransactions = $items->count();
            $totalQuantity = $items->sum('jumlah');
            $uniqueItems = $items->pluck('item_id')->unique()->count();
            
            // Summary Statistics
            echo '<tr><td colspan="10" class="summary">RINGKASAN LAPORAN:</td></tr>';
            echo '<tr><td colspan="10" class="summary">Total Transaksi: ' . number_format($totalTransactions) . '</td></tr>';
            echo '<tr><td colspan="10" class="summary">Total Quantity: ' . number_format($totalQuantity) . '</td></tr>';
            echo '<tr><td colspan="10" class="summary">Jenis Item Berbeda: ' . number_format($uniqueItems) . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 15px;"></td></tr>';
            
            // Table Header
            echo '<tr>';
            echo '<td class="table-header">No</td>';
            echo '<td class="table-header">Tanggal</td>';
            echo '<td class="table-header">Kode Barang</td>';
            echo '<td class="table-header">Nama Barang</td>';
            echo '<td class="table-header">Diambil Oleh</td>';
            echo '<td class="table-header">Supplier</td>';
            echo '<td class="table-header">Kategori</td>';
            echo '<td class="table-header">Jumlah</td>';
            echo '<td class="table-header">Status</td>';
            echo '<td class="table-header">Keterangan</td>';
            echo '</tr>';

            // Data
            $totalJumlah = 0;
            foreach ($items as $index => $item) {
                $kodeBarang = 'N/A';
                if ($item->item) {
                    $kodeBarang = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT);
                }

                $tanggal = $item->created_at ? $item->created_at->format('d F Y') : date('d F Y');
                
                // Status mapping with colors
                $statusMap = [
                    'sold' => ['text' => 'Terjual', 'color' => 'background-color: #4ECDC4; color: white;'],
                    'damaged' => ['text' => 'Rusak', 'color' => 'background-color: #FF6B6B; color: white;'],
                    'expired' => ['text' => 'Kadaluarsa', 'color' => 'background-color: #FFE66D; color: black;'],
                    'lost' => ['text' => 'Hilang', 'color' => 'background-color: #FF6B6B; color: white;'],
                    'borrowed' => ['text' => 'Dipinjam', 'color' => 'background-color: #A8E6CF; color: black;'],
                    'to_production' => ['text' => 'Ke Produksi', 'color' => 'background-color: #87CEEB; color: black;'],
                ];
                
                $statusInfo = $statusMap[$item->status] ?? ['text' => ucfirst($item->status ?? 'N/A'), 'color' => ''];
                $jumlah = $item->jumlah ?? 0;
                $totalJumlah += $jumlah;

                echo '<tr>';
                echo '<td class="table-data">' . ($index + 1) . '</td>';
                echo '<td class="table-data">' . $tanggal . '</td>';
                echo '<td class="table-data">' . $kodeBarang . '</td>';
                echo '<td class="table-data-left">' . ($item->item->nama ?? 'N/A') . '</td>';
                echo '<td class="table-data-left">' . ($item->user->name ?? 'N/A') . '</td>';
                echo '<td class="table-data-left">' . ($item->item->supplier->nama ?? 'N/A') . '</td>';
                echo '<td class="table-data-left">' . ($item->item->category->name ?? 'N/A') . '</td>';
                echo '<td class="table-data">' . number_format($jumlah) . '</td>';
                echo '<td class="table-data" style="' . $statusInfo['color'] . '">' . $statusInfo['text'] . '</td>';
                echo '<td class="table-data-left">' . ($item->keterangan ?? '-') . '</td>';
                echo '</tr>';
            }

            // Footer
            echo '<tr><td colspan="10" style="height: 15px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer-note"><strong>KETERANGAN STATUS:</strong></td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Terjual: Barang telah dijual kepada customer</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Rusak: Barang mengalami kerusakan</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Kadaluarsa: Barang melewati tanggal expired</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Hilang: Barang tidak ditemukan</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Dipinjam: Barang sedang dipinjam</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Ke Produksi: Barang digunakan untuk produksi</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            echo '<tr><td colspan="10" class="footer-note">Laporan ini digenerate otomatis oleh sistem pada: ' . date('d F Y H:i:s') . '</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">Total record yang ditampilkan: ' . number_format($totalTransactions) . ' transaksi</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            echo '<tr><td colspan="10" class="footer-note"><strong>PT. ARTILIA - Inventory Management System</strong></td></tr>';
            echo '<tr><td colspan="10" class="footer-note">Untuk pertanyaan terkait laporan ini, hubungi IT Department</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">Email: support@artilia.com | Telp: (021) 1234567 ext. 101</td></tr>';
            
            echo '</table>';
            echo '</body>';
            echo '</html>';
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportIncomingExcel($items, $request)
    {
        $filename = 'laporan-barang-masuk-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($items, $request) {
            // Start HTML Excel format
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<style>';
            echo 'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }';
            echo '.header-company { background-color: #2E86AB; color: white; font-weight: bold; text-align: center; padding: 8px; font-size: 14px; }';
            echo '.header-info { background-color: #A23B72; color: white; font-weight: bold; text-align: center; padding: 6px; font-size: 12px; }';
            echo '.header-title { background-color: #F18F01; color: white; font-weight: bold; text-align: center; padding: 10px; font-size: 16px; }';
            echo '.summary { background-color: #C73E1D; color: white; font-weight: bold; padding: 6px; font-size: 11px; }';
            echo '.table-header { background-color: #27AE60; color: white; font-weight: bold; text-align: center; padding: 8px; border: 1px solid #333; }';
            echo '.table-data { padding: 6px; border: 1px solid #ccc; text-align: center; }';
            echo '.table-data-left { padding: 6px; border: 1px solid #ccc; text-align: left; }';
            echo '.table-data-right { padding: 6px; border: 1px solid #ccc; text-align: right; }';
            echo '.footer-note { background-color: #E8F4FD; padding: 6px; font-size: 10px; border: 1px solid #ccc; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<table>';
            
            // Company Header
            echo '<tr><td colspan="10" class="header-company">PT. ARTILIA - INVENTORY MANAGEMENT SYSTEM</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Jl. Cipendeu No. 123, Jakarta Selatan 12345</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Telp: (021) 1234567 | Email: info@artilia.com</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Website: www.artilia.com | NPWP: 01.234.567.8-901.000</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            // Report Title
            echo '<tr><td colspan="10" class="header-title">LAPORAN BARANG MASUK</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Tanggal Cetak: ' . date('d F Y H:i:s') . '</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Dicetak Oleh: ' . (auth()->user()->name ?? 'SYSTEM') . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            // Summary Statistics
            $totalTransactions = $items->count();
            $totalQuantity = $items->sum('jumlah');
            $uniqueItems = $items->pluck('item_id')->unique()->count();
            $totalValue = $items->sum(function($item) { 
                return ($item->jumlah ?? 0) * ($item->item->harga ?? 0); 
            });
            
            echo '<tr><td colspan="10" class="summary">RINGKASAN LAPORAN:</td></tr>';
            echo '<tr><td colspan="10" class="summary">Total Transaksi: ' . number_format($totalTransactions) . '</td></tr>';
            echo '<tr><td colspan="10" class="summary">Total Quantity: ' . number_format($totalQuantity) . '</td></tr>';
            echo '<tr><td colspan="10" class="summary">Jenis Item Berbeda: ' . number_format($uniqueItems) . '</td></tr>';
            echo '<tr><td colspan="10" class="summary">Total Nilai: Rp ' . number_format($totalValue, 0, ',', '.') . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 15px;"></td></tr>';
            
            // Table Header
            echo '<tr>';
            echo '<td class="table-header">No</td>';
            echo '<td class="table-header">Tanggal</td>';
            echo '<td class="table-header">Kode Barang</td>';
            echo '<td class="table-header">Nama Barang</td>';
            echo '<td class="table-header">Supplier</td>';
            echo '<td class="table-header">Kategori</td>';
            echo '<td class="table-header">Jumlah</td>';
            echo '<td class="table-header">Harga Satuan</td>';
            echo '<td class="table-header">Total Nilai</td>';
            echo '<td class="table-header">Diinput Oleh</td>';
            echo '</tr>';

            // Data
            $totalNilaiKeseluruhan = 0;
            foreach ($items as $index => $item) {
                $kodeBarang = 'N/A';
                $harga = 0;
                if ($item->item) {
                    $kodeBarang = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT);
                    $harga = $item->item->harga ?? 0;
                }

                $tanggal = $item->created_at ? $item->created_at->format('d F Y') : date('d F Y');
                $jumlah = $item->jumlah ?? 0;
                $totalNilai = $jumlah * $harga;
                $totalNilaiKeseluruhan += $totalNilai;

                echo '<tr>';
                echo '<td class="table-data">' . ($index + 1) . '</td>';
                echo '<td class="table-data">' . $tanggal . '</td>';
                echo '<td class="table-data">' . $kodeBarang . '</td>';
                echo '<td class="table-data-left">' . ($item->item->nama ?? 'N/A') . '</td>';
                echo '<td class="table-data-left">' . ($item->item->supplier->nama ?? 'N/A') . '</td>';
                echo '<td class="table-data-left">' . ($item->item->category->name ?? 'N/A') . '</td>';
                echo '<td class="table-data">' . number_format($jumlah) . '</td>';
                echo '<td class="table-data-right">Rp ' . number_format($harga, 0, ',', '.') . '</td>';
                echo '<td class="table-data-right">Rp ' . number_format($totalNilai, 0, ',', '.') . '</td>';
                echo '<td class="table-data-left">' . ($item->user->name ?? 'System') . '</td>';
                echo '</tr>';
            }

            // Footer
            echo '<tr><td colspan="10" style="height: 15px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer-note"><strong>KETERANGAN:</strong></td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Laporan ini menampilkan semua barang yang masuk ke inventory</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Total nilai dihitung berdasarkan jumlah x harga satuan</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">- Data diurutkan berdasarkan tanggal terbaru</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            echo '<tr><td colspan="10" class="footer-note">Laporan ini digenerate otomatis oleh sistem pada: ' . date('d F Y H:i:s') . '</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">Total record yang ditampilkan: ' . number_format($totalTransactions) . ' transaksi</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            echo '<tr><td colspan="10" class="footer-note"><strong>PT. ARTILIA - Inventory Management System</strong></td></tr>';
            echo '<tr><td colspan="10" class="footer-note">Untuk pertanyaan terkait laporan ini, hubungi IT Department</td></tr>';
            echo '<tr><td colspan="10" class="footer-note">Email: support@artilia.com | Telp: (021) 1234567 ext. 101</td></tr>';
            
            echo '</table>';
            echo '</body>';
            echo '</html>';
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportSuppliersExcel($suppliers)
    {
        $filename = 'laporan-supplier-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($suppliers) {
            // Start HTML Excel format
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<style>';
            echo 'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }';
            echo '.header-company { background-color: #2E86AB; color: white; font-weight: bold; text-align: center; padding: 8px; font-size: 14px; }';
            echo '.header-info { background-color: #A23B72; color: white; font-weight: bold; text-align: center; padding: 6px; font-size: 12px; }';
            echo '.header-title { background-color: #F18F01; color: white; font-weight: bold; text-align: center; padding: 10px; font-size: 16px; }';
            echo '.summary { background-color: #C73E1D; color: white; font-weight: bold; padding: 6px; font-size: 11px; }';
            echo '.table-header { background-color: #8E44AD; color: white; font-weight: bold; text-align: center; padding: 8px; border: 1px solid #333; }';
            echo '.table-data { padding: 6px; border: 1px solid #ccc; text-align: center; }';
            echo '.table-data-left { padding: 6px; border: 1px solid #ccc; text-align: left; }';
            echo '.table-data-right { padding: 6px; border: 1px solid #ccc; text-align: right; }';
            echo '.footer-note { background-color: #E8F4FD; padding: 6px; font-size: 10px; border: 1px solid #ccc; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<table>';
            
            // Company Header
            echo '<tr><td colspan="9" class="header-company">PT. ARTILIA - INVENTORY MANAGEMENT SYSTEM</td></tr>';
            echo '<tr><td colspan="9" class="header-info">Jl. Cipendeu No. 123, Jakarta Selatan 12345</td></tr>';
            echo '<tr><td colspan="9" class="header-info">Telp: (021) 1234567 | Email: info@artilia.com</td></tr>';
            echo '<tr><td colspan="9" class="header-info">Website: www.artilia.com | NPWP: 01.234.567.8-901.000</td></tr>';
            echo '<tr><td colspan="9" style="height: 10px;"></td></tr>';
            
            // Report Title
            echo '<tr><td colspan="9" class="header-title">LAPORAN DATA SUPPLIER</td></tr>';
            echo '<tr><td colspan="9" class="header-info">Tanggal Cetak: ' . date('d F Y H:i:s') . '</td></tr>';
            echo '<tr><td colspan="9" class="header-info">Dicetak Oleh: ' . (auth()->user()->name ?? 'SYSTEM') . '</td></tr>';
            echo '<tr><td colspan="9" style="height: 10px;"></td></tr>';
            
            // Summary Statistics
            $totalSuppliers = $suppliers->count();
            $activeSuppliers = $suppliers->where('status', 'active')->count();
            $inactiveSuppliers = $suppliers->where('status', 'inactive')->count();
            $totalItems = $suppliers->sum('items_count');
            
            echo '<tr><td colspan="9" class="summary">RINGKASAN LAPORAN:</td></tr>';
            echo '<tr><td colspan="9" class="summary">Total Supplier: ' . number_format($totalSuppliers) . '</td></tr>';
            echo '<tr><td colspan="9" class="summary">Supplier Aktif: ' . number_format($activeSuppliers) . '</td></tr>';
            echo '<tr><td colspan="9" class="summary">Supplier Tidak Aktif: ' . number_format($inactiveSuppliers) . '</td></tr>';
            echo '<tr><td colspan="9" class="summary">Total Item dari Semua Supplier: ' . number_format($totalItems) . '</td></tr>';
            echo '<tr><td colspan="9" style="height: 15px;"></td></tr>';
            
            // Table Header
            echo '<tr>';
            echo '<td class="table-header">No</td>';
            echo '<td class="table-header">Nama Supplier</td>';
            echo '<td class="table-header">Kontak</td>';
            echo '<td class="table-header">Email</td>';
            echo '<td class="table-header">Alamat</td>';
            echo '<td class="table-header">Jumlah Item</td>';
            echo '<td class="table-header">Status</td>';
            echo '<td class="table-header">Contact Person</td>';
            echo '<td class="table-header">Tanggal Bergabung</td>';
            echo '</tr>';

            // Data
            foreach ($suppliers as $index => $supplier) {
                $kontak = $supplier->phone ?? 'N/A';
                $email = $supplier->email ?? 'N/A';
                $alamat = $supplier->address ?? 'N/A';
                $contactPerson = $supplier->contact_person ?? 'N/A';
                $status = $supplier->status === 'active' ? 'Aktif' : 'Tidak Aktif';
                
                $tanggalBergabung = 'N/A';
                if ($supplier->created_at) {
                    $tanggalBergabung = $supplier->created_at->format('d F Y');
                } else {
                    $tanggalBergabung = date('d F Y');
                }

                $jumlahItem = $supplier->items_count ?? $supplier->items->count() ?? 0;
                
                // Status dengan warna
                $statusClass = $supplier->status === 'active' ? 'background-color: #4ECDC4; color: white;' : 'background-color: #FF6B6B; color: white;';

                echo '<tr>';
                echo '<td class="table-data">' . ($index + 1) . '</td>';
                echo '<td class="table-data-left">' . ($supplier->nama ?? 'N/A') . '</td>';
                echo '<td class="table-data-left">' . $kontak . '</td>';
                echo '<td class="table-data-left">' . $email . '</td>';
                echo '<td class="table-data-left">' . $alamat . '</td>';
                echo '<td class="table-data">' . number_format($jumlahItem) . '</td>';
                echo '<td class="table-data" style="' . $statusClass . '">' . $status . '</td>';
                echo '<td class="table-data-left">' . $contactPerson . '</td>';
                echo '<td class="table-data">' . $tanggalBergabung . '</td>';
                echo '</tr>';
            }

            // Footer
            echo '<tr><td colspan="9" style="height: 15px;"></td></tr>';
            echo '<tr><td colspan="9" class="footer-note"><strong>KETERANGAN STATUS:</strong></td></tr>';
            echo '<tr><td colspan="9" class="footer-note">- Aktif: Supplier masih aktif bekerjasama dengan perusahaan</td></tr>';
            echo '<tr><td colspan="9" class="footer-note">- Tidak Aktif: Supplier sudah tidak bekerjasama</td></tr>';
            echo '<tr><td colspan="9" style="height: 10px;"></td></tr>';
            echo '<tr><td colspan="9" class="footer-note"><strong>KETERANGAN LAINNYA:</strong></td></tr>';
            echo '<tr><td colspan="9" class="footer-note">- Jumlah Item: Total jenis barang yang disuplai oleh supplier</td></tr>';
            echo '<tr><td colspan="9" class="footer-note">- Contact Person: Nama PIC (Person In Charge) dari supplier</td></tr>';
            echo '<tr><td colspan="9" style="height: 10px;"></td></tr>';
            
            echo '<tr><td colspan="9" class="footer-note">Laporan ini digenerate otomatis oleh sistem pada: ' . date('d F Y H:i:s') . '</td></tr>';
            echo '<tr><td colspan="9" class="footer-note">Total record yang ditampilkan: ' . number_format($totalSuppliers) . ' supplier</td></tr>';
            echo '<tr><td colspan="9" style="height: 10px;"></td></tr>';
            
            echo '<tr><td colspan="9" class="footer-note"><strong>PT. ARTILIA - Inventory Management System</strong></td></tr>';
            echo '<tr><td colspan="9" class="footer-note">Untuk pertanyaan terkait laporan ini, hubungi IT Department</td></tr>';
            echo '<tr><td colspan="9" class="footer-note">Email: support@artilia.com | Telp: (021) 1234567 ext. 101</td></tr>';
            
            echo '</table>';
            echo '</body>';
            echo '</html>';
        };

        return response()->stream($callback, 200, $headers);
    }
}
