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
            border-bottom: 3px solid #dc2626;
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
            color: #dc2626;
        }
        
        .company-details {
            text-align: right;
            font-size: 9px;
            color: #7f8c8d;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #dc2626;
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
        
        .filter-info {
            background-color: #fef2f2;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9px;
            border-left: 4px solid #dc2626;
        }
        
        .filter-info strong {
            color: #991b1b;
        }
        
        .summary-section {
            background-color: #fef2f2;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
        }
        
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 8px;
            border-right: 1px solid #fca5a5;
            vertical-align: middle;
        }
        
        .summary-item:last-child {
            border-right: none;
        }
        
        .summary-label {
            font-size: 8px;
            color: #991b1b;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #7f1d1d;
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
            background-color: #dc2626;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #991b1b;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 6px;
            border: 1px solid #fecaca;
            font-size: 9px;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background-color: #fef2f2;
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
        
        .status-sold {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .status-damaged {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .status-expired {
            background-color: #fed7aa;
            color: #9a3412;
            border: 1px solid #fdba74;
        }
        
        .status-lost {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        
        .status-borrowed {
            background-color: #e0e7ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
        }
        
        .status-to-production {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #fecaca;
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
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <div class="company-logo">PT. ARTILIA</div>
            <div class="company-details">
                Inventory Management System<br>
                Outgoing Items Report
            </div>
        </div>
        <div class="report-title">{{ $title }}</div>
        <div class="report-meta">
            <div>Generated on: {{ $date }}</div>
            <div>Report Period: {{ $date }}</div>
        </div>
    </div>

    @if($date_from || $date_to || $status)
    <div class="filter-info">
        <strong>Filter Applied:</strong>
        @if($date_from)
            From: {{ \Carbon\Carbon::parse($date_from)->format('d M Y') }}
        @endif
        @if($date_to)
            @if($date_from) | @endif
            To: {{ \Carbon\Carbon::parse($date_to)->format('d M Y') }}
        @endif
        @if($status)
            @if($date_from || $date_to) | @endif
            Status: {{ ucfirst($status) }}
        @endif
    </div>
    @endif

    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Items Out</div>
                <div class="summary-value">{{ $total_items }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Quantity</div>
                <div class="summary-value">{{ number_format($total_quantity) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Items Sold</div>
                <div class="summary-value">{{ $items->where('status', 'sold')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Items Damaged</div>
                <div class="summary-value">{{ $items->where('status', 'damaged')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Items Lost</div>
                <div class="summary-value">{{ $items->where('status', 'lost')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Items Expired</div>
                <div class="summary-value">{{ $items->where('status', 'expired')->count() }}</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 8%;">Date</th>
                    <th style="width: 8%;">Item Code</th>
                    <th style="width: 15%;">Item Name</th>
                    <th style="width: 11%;">Taken By</th>
                    <th style="width: 11%;">Supplier</th>
                    <th style="width: 9%;">Category</th>
                    <th style="width: 7%;">Quantity</th>
                    <th style="width: 7%;">Status</th>
                    <th style="width: 6%;">User ID</th>
                    <th style="width: 14%;">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->created_at ? $item->created_at->format('d/m/Y') : date('d/m/Y') }}</td>
                    <td class="text-center font-bold">{{ $item->item->kode_barang ?? 'N/A' }}</td>
                    <td class="font-bold">{{ $item->item->nama }}</td>
                    <td>{{ $item->user->name ?? 'N/A' }}</td>
                    <td>{{ $item->item->supplier->nama ?? 'N/A' }}</td>
                    <td>{{ $item->item->category->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($item->jumlah) }}</td>
                    <td class="text-center">
                        @if($item->status === 'sold')
                            <span class="status-badge status-sold">Sold</span>
                        @elseif($item->status === 'damaged')
                            <span class="status-badge status-damaged">Damaged</span>
                        @elseif($item->status === 'expired')
                            <span class="status-badge status-expired">Expired</span>
                        @elseif($item->status === 'lost')
                            <span class="status-badge status-lost">Lost</span>
                        @elseif($item->status === 'borrowed')
                            <span class="status-badge status-borrowed">Borrowed</span>
                        @elseif($item->status === 'to_production')
                            <span class="status-badge status-to-production">To Production</span>
                        @else
                            <span class="status-badge">{{ ucfirst($item->status) }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->user_id ?? 'N/A' }}</td>
                    <td class="text-center">{{ $item->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>PT. ARTILIA - Inventory Management System</strong></p>
        <p>This report was generated automatically on {{ $date }} | Total Records: {{ $total_items }} outgoing items</p>
        <p>For questions regarding this report, please contact the IT Department</p>
    </div>
</body>
</html>