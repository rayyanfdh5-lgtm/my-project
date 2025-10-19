@extends('admin.layouts.dashboard')

@section('content')
    <x-inventory-form>
        <x-history-feature :filter="$filter" />

        @if ($histories->isEmpty())
            <x-empty-states />
        @else
            <div class="overflow-x-auto rounded-lg border border-slate-200 bg-white shadow-sm">
                <table class="table-next">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="th-next">Date</th>
                            <th class="th-next">Item</th>
                            <th class="th-next">Supplier</th>
                            <th class="th-next">Quantity</th>
                            <th class="th-next">Status</th>
                            <th class="th-next">Condition</th>
                        </tr>
                    </thead>
                    <tbody class="tbody-next">
                        @forelse ($histories as $history)
                            <tr class="tr-next">
                                <td class="th-next">
                                    <span class="text-xs font-semibold tracking-tight text-green-600">
                                        {{ $history->created_at->format('d M Y - H:i') }}
                                    </span>
                                </td>
                                <td class="th-next">{{ $history->item->nama }}</td>
                                <td class="th-next font-semibold text-gray-600">{{ $history->item->supplier->nama ?? 'N/A' }}</td>
                                <td class="th-next">{{ $history->jumlah }}</td>
                                <td class="th-next capitalize">
                                    @if ($history->tipe == 'masuk')
                                        <span
                                            class="label border border-green-300 bg-green-100 text-green-700">{{ $history->tipe }}</span>
                                    @else
                                        <span
                                            class="label border border-red-200 bg-red-100 text-red-500">{{ $history->tipe }}</span>
                                    @endif
                                </td>
                                <td class="th-next">
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
            </div>
        @endif
    </x-inventory-form>
@endsection
