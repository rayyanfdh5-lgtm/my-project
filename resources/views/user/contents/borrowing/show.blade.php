@extends('user.layouts.dashboard-user')

@section('title', 'Detail Permintaan Peminjaman')

@section('user')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('user.borrowing.my-requests') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Permintaan Peminjaman</h1>
                <p class="text-gray-600 mt-2">Informasi lengkap tentang permintaan peminjaman Anda</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Request Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Permintaan</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">ID Permintaan:</span>
                        <span class="text-sm text-gray-900 font-medium">#{{ $request->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Tanggal Ajukan:</span>
                        <span class="text-sm text-gray-900">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Status:</span>
                        <span class="text-sm">
                            @switch($request->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Menunggu Persetujuan
                                    </span>
                                    @break
                                @case('approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Disetujui
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Selesai
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($request->status) }}
                                    </span>
                            @endswitch
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Jumlah:</span>
                        <span class="text-sm text-gray-900">{{ $request->jumlah }} unit</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Tanggal Pinjam:</span>
                        <span class="text-sm text-gray-900">{{ $request->tanggal_pinjam->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Tanggal Rencana Kembali:</span>
                        <span class="text-sm text-gray-900">{{ $request->tanggal_kembali_rencana->format('d/m/Y') }}</span>
                    </div>
                    @if($request->approved_by)
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Disetujui oleh:</span>
                        <span class="text-sm text-gray-900">{{ $request->approvedBy->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Tanggal Persetujuan:</span>
                        <span class="text-sm text-gray-900">{{ $request->approved_at ? $request->approved_at->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Item Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Barang</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Nama Barang:</span>
                        <span class="text-sm text-gray-900 font-medium">{{ $request->item->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Stok Peminjaman:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $request->item->stok_peminjaman }} unit
                        </span>
                    </div>
                    @if($request->item->keterangan)
                    <div class="pt-3 border-t border-gray-200">
                        <span class="text-sm font-medium text-gray-500 block mb-2">Deskripsi:</span>
                        <p class="text-sm text-gray-700">{{ $request->item->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        @if($request->keterangan)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Keterangan/Tujuan Peminjaman</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-700">{{ $request->keterangan }}</p>
            </div>
        </div>
        @endif

        @if($request->kondisi_pinjam)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Kondisi Barang Saat Dipinjam</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-700">{{ $request->kondisi_pinjam }}</p>
            </div>
        </div>
        @endif
    </div>

    @if($request->admin_notes)
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Catatan Admin</h3>
            </div>
            <div class="p-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-blue-800">{{ $request->admin_notes }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Status Messages -->
    @if($request->status === 'pending')
    <div class="mt-8">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-yellow-800 font-medium">Status: Menunggu Persetujuan</h4>
                    <p class="text-yellow-700 mt-1">Permintaan Anda sedang menunggu persetujuan dari admin. Anda akan mendapat notifikasi setelah admin memproses permintaan ini.</p>
                </div>
            </div>
        </div>
    </div>
    @elseif($request->status === 'approved')
    <div class="mt-8">
        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-green-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-green-800 font-medium">Status: Disetujui</h4>
                    <p class="text-green-700 mt-1">Permintaan Anda telah disetujui! Silakan hubungi admin untuk mengambil barang sesuai jadwal yang telah ditentukan.</p>
                </div>
            </div>
        </div>
    </div>
    @elseif($request->status === 'rejected')
    <div class="mt-8">
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-red-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-red-800 font-medium">Status: Ditolak</h4>
                    <p class="text-red-700 mt-1">
                        Permintaan Anda ditolak.
                        @if($request->admin_notes)
                            Silakan lihat catatan admin di atas untuk informasi lebih lanjut.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
