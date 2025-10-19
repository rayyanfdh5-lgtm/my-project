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
            border-bottom: 3px solid #ea580c;
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
            color: #ea580c;
        }
        
        .company-details {
            text-align: right;
            font-size: 9px;
            color: #7f8c8d;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #ea580c;
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
            background-color: #fff7ed;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #fed7aa;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
        }
        
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 8px;
            border-right: 1px solid #fdba74;
            vertical-align: middle;
        }
        
        .summary-item:last-child {
            border-right: none;
        }
        
        .summary-label {
            font-size: 8px;
            color: #9a3412;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #7c2d12;
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
            background-color: #ea580c;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #c2410c;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 6px;
            border: 1px solid #fed7aa;
            font-size: 9px;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background-color: #fff7ed;
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
        
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #fed7aa;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        .contact-info {
            font-size: 8px;
            color: #6b7280;
            margin-top: 2px;
            line-height: 1.2;
        }
        
        .address-info {
            font-size: 8px;
            color: #374151;
            line-height: 1.2;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <div class="company-logo">PT. ARTILIA</div>
            <div class="company-details">
                Inventory Management System<br>
                Supplier Report
            </div>
        </div>
        <div class="report-title">{{ $title }}</div>
        <div class="report-meta">
            <div>Generated on: {{ $date }}</div>
            <div>Report Period: {{ $date }}</div>
        </div>
    </div>

    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Suppliers</div>
                <div class="summary-value">{{ $total_suppliers }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Active Suppliers</div>
                <div class="summary-value">{{ $active_suppliers }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Inactive Suppliers</div>
                <div class="summary-value">{{ $inactive_suppliers }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Items</div>
                <div class="summary-value">{{ $suppliers->sum(function($supplier) { return $supplier->items->count(); }) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Avg Items/Supplier</div>
                <div class="summary-value">{{ $total_suppliers > 0 ? number_format($suppliers->sum(function($supplier) { return $supplier->items->count(); }) / $total_suppliers, 1) : 0 }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Latest Joined</div>
                <div class="summary-value">{{ $suppliers->max('created_at') ? $suppliers->max('created_at')->format('d/m/Y') : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 18%;">Supplier Name</th>
                    <th style="width: 15%;">Contact</th>
                    <th style="width: 20%;">Address</th>
                    <th style="width: 8%;">Items</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 8%;">Contact Person</th>
                    <th style="width: 9%;">Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $index => $supplier)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $supplier->nama }}</td>
                    <td class="text-left">
                        @if($supplier->phone)
                            <div>{{ $supplier->phone }}</div>
                        @endif
                        @if($supplier->email)
                            <div class="contact-info">{{ $supplier->email }}</div>
                        @endif
                        @if(!$supplier->phone && !$supplier->email)
                            <div class="contact-info">N/A</div>
                        @endif
                    </td>
                    <td class="text-left">
                        @if($supplier->address)
                            <div class="address-info">{{ $supplier->address }}</div>
                        @else
                            <div class="address-info">N/A</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $supplier->items->count() }}</td>
                    <td class="text-center">
                        @if($supplier->status === 'active')
                            <span class="status-badge status-active">Active</span>
                        @else
                            <span class="status-badge status-inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($supplier->contact_person)
                            <div class="contact-info">{{ $supplier->contact_person }}</div>
                        @else
                            <div class="contact-info">N/A</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $supplier->created_at ? $supplier->created_at->format('d/m/Y') : date('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>PT. ARTILIA - Inventory Management System</strong></p>
        <p>This report was generated automatically on {{ $date }} | Total Suppliers: {{ $total_suppliers }}</p>
        <p>For questions regarding this report, please contact the IT Department</p>
    </div>
</body>
</html>