@extends('admin.layouts.dashboard')

@section('content')
    <x-inventory-form>
        <x-history-feature :filter="$filter" />

        <div class="w-full rounded-lg border border-slate-200 bg-white shadow-sm">
            @if ($histories->isEmpty())
                <x-empty-states />
            @else
            <table class="min-w-full text-left text-sm">
                <thead class="border-b bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Item</th>
                        <th class="px-4 py-3">Supplier</th>
                        <th class="px-4 py-3">Quantity</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Condition</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($histories as $history)
                        <tr>
                            <td class="px-4 py-3">
                                <span class="text-xs font-semibold tracking-tight text-green-600">
                                    {{ $history->created_at->format('d M Y - H:i') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $history->item->nama }}</td>
                            <td class="text-xs px-4 py-3 font-semibold text-gray-600">{{ $history->item->supplier->nama ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $history->jumlah }}</td>
                            <td class="px-4 py-3 capitalize">
                                @if ($history->tipe == 'masuk')
                                    <span
                                        class="label border border-green-300 bg-green-100 text-green-700">{{ $history->tipe }}</span>
                                @else
                                    <span
                                        class="label border border-red-200 bg-red-100 text-red-500">{{ $history->tipe }}</span>
                                @endif


                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="@if ($history->status === 'sold') bg-green-100 text-green-800
                                              @elseif($history->status === 'damaged') bg-red-100 text-red-600
                                              @elseif($history->status === 'expired') bg-orange-100 text-orange-700
                                              @elseif($history->status === 'lost') bg-yellow-100 text-yellow-700
                                              @else bg-slate-100 text-slate-700 @endif inline-flex rounded-full px-2 py-0.5 text-xs font-semibold">
                                    {{ ucfirst($history->status ?? 'good') }}
                                </span>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-500">No history available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @endif
        </div>
        <script type="module">
            import './js/app.js';

            document.getElementById('excelFile').addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    window.readExcelFile(file);
                }
            });
        </script>
    </x-inventory-form>
@endsection
