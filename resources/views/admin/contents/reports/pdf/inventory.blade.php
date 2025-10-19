<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #2c3e50;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 15px;
        }
        
        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .company-logo {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .company-details {
            text-align: right;
            font-size: 9px;
            color: #7f8c8d;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .report-meta {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        
        .summary-section {
            background-color: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
        }
        
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 8px;
            border-right: 1px solid #ced4da;
            vertical-align: middle;
        }
        
        .summary-item:last-child {
            border-right: none;
        }
        
        .summary-label {
            font-size: 8px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .table-container {
            margin-top: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        th {
            background-color: #495057;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #343a40;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 6px;
            border: 1px solid #dee2e6;
            font-size: 9px;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-in-stock {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-low-stock {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-out-stock {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        .currency {
            font-family: 'Courier New', monospace;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .filter-info {
            background-color: #e3f2fd;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9px;
            border-left: 4px solid #2196f3;
        }
        
        .filter-info strong {
            color: #1976d2;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <div class="company-logo">PT. ARTILIA</div>
            <div class="company-details">
                Inventory Management System<br>
                Professional Report
            </div>
        </div>
        <div class="report-title">{{ $title }}</div>
        <div class="report-meta">
            <div>Generated on: {{ $date }}</div>
            <div>Report Period: {{ $date }}</div>
        </div>
    </div>

    @if($supplier_id || $category_id)
    <div class="filter-info">
        <strong>Filter Applied:</strong>
        @if($supplier_id)
            Supplier: {{ \App\Models\Supplier::find($supplier_id)->nama ?? 'All Suppliers' }}
        @endif
        @if($category_id)
            @if($supplier_id) | @endif
            Category: {{ \App\Models\Category::find($category_id)->name ?? 'All Categories' }}
        @endif
    </div>
    @endif

    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Items</div>
                <div class="summary-value">{{ $total_items }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Stock</div>
                <div class="summary-value">{{ number_format($total_stock) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Value</div>
                <div class="summary-value currency">Rp {{ number_format($total_value, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Low Stock Items</div>
                <div class="summary-value">{{ $items->filter(function($item) { return ($item->stok_total ?? 0) < 10 && ($item->stok_total ?? 0) > 0; })->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Out of Stock</div>
                <div class="summary-value">{{ $items->filter(function($item) { return ($item->stok_total ?? 0) == 0; })->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">In Stock</div>
                <div class="summary-value">{{ $items->filter(function($item) { return ($item->stok_total ?? 0) > 0; })->count() }}</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 8%;">Item Code</th>
                    <th style="width: 18%;">Item Name</th>
                    <th style="width: 13%;">Supplier</th>
                    <th style="width: 10%;">Category</th>
                    <th style="width: 7%;">Stock</th>
                    <th style="width: 11%;">Unit Price</th>
                    <th style="width: 11%;">Total Value</th>
                    <th style="width: 7%;">Status</th>
                    <th style="width: 11%;">Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-bold">{{ $item->kode_barang ?? 'N/A' }}</td>
                    <td class="font-bold">{{ $item->nama ?? 'N/A' }}</td>
                    <td>{{ $item->supplier->nama ?? 'N/A' }}</td>
                    <td>{{ $item->category->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($item->stok_total ?? 0) }}</td>
                    <td class="text-right currency">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format(($item->stok_total ?? 0) * ($item->harga ?? 0), 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if(($item->stok_total ?? 0) == 0)
                            <span class="status-badge status-out-stock">Out</span>
                        @elseif(($item->stok_total ?? 0) < 10)
                            <span class="status-badge status-low-stock">Low</span>
                        @else
                            <span class="status-badge status-in-stock">OK</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->type && method_exists($item->type, 'label'))
                            {{ $item->type->label() }}
                        @elseif($item->type)
                            {{ ucfirst($item->type) }}
                        @else
                            Stok
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>PT. ARTILIA - Inventory Management System</strong></p>
        <p>This report was generated automatically on {{ $date }} | Total Records: {{ $total_items }} items</p>
        <p>For questions regarding this report, please contact the IT Department</p>
    </div>
</body>
</html>