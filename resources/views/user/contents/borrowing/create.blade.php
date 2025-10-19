@extends('user.layouts.dashboard-user')

@section('title', 'Ajukan Peminjaman Barang')

@section('user')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Ajukan Peminjaman Barang</h1>
            <p class="mt-2 text-gray-600">Lengkapi form di bawah untuk mengajukan peminjaman barang</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Item Information Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Item Details -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Barang</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Nama Barang</span>
                                <span class="text-sm text-gray-900 font-medium">{{ $item->nama }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-500">Stok Tersedia</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $item->stok_peminjaman }} unit
                                </span>
                            </div>
                            @if($item->keterangan)
                            <div class="pt-3 border-t border-gray-200">
                                <span class="text-sm font-medium text-gray-500 block mb-1">Deskripsi</span>
                                <p class="text-sm text-gray-700">{{ $item->keterangan }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrowing Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Form Peminjaman</h3>
                        <p class="text-sm text-gray-600 mt-1">Isi data peminjaman dengan lengkap dan benar</p>
                    </div>
                    
                    <form action="{{ route('user.borrowing.store') }}" method="POST" class="p-6">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jumlah Pinjam -->
                            <div>
                                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah yang Dipinjam <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jumlah') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                       id="jumlah" 
                                       name="jumlah" 
                                       min="1" 
                                       max="{{ $item->stok_peminjaman }}"
                                       value="{{ old('jumlah') }}" 
                                       placeholder="Masukkan jumlah"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">Maksimal: {{ $item->stok_peminjaman }} unit</p>
                                @error('jumlah')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Pinjam -->
                            <div>
                                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Pinjam <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_pinjam') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                       id="tanggal_pinjam" 
                                       name="tanggal_pinjam" 
                                       min="{{ date('Y-m-d') }}"
                                       value="{{ old('tanggal_pinjam') }}" 
                                       required>
                                @error('tanggal_pinjam')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Kembali -->
                            <div class="md:col-span-2">
                                <label for="tanggal_kembali_rencana" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Rencana Kembali <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_kembali_rencana') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                       id="tanggal_kembali_rencana" 
                                       name="tanggal_kembali_rencana" 
                                       value="{{ old('tanggal_kembali_rencana') }}" 
                                       required>
                                @error('tanggal_kembali_rencana')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mt-6">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan/Tujuan Peminjaman
                            </label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                      id="keterangan" 
                                      name="keterangan" 
                                      rows="3" 
                                      placeholder="Jelaskan tujuan atau keperluan peminjaman barang ini...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kondisi Barang -->
                        <div class="mt-6">
                            <label for="kondisi_pinjam" class="block text-sm font-medium text-gray-700 mb-2">
                                Kondisi Barang Saat Dipinjam
                            </label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kondisi_pinjam') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                                      id="kondisi_pinjam" 
                                      name="kondisi_pinjam" 
                                      rows="2" 
                                      placeholder="Catat kondisi barang saat dipinjam (opsional)">{{ old('kondisi_pinjam') }}</textarea>
                            @error('kondisi_pinjam')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('user.borrowing.index') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Ajukan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali_rencana');
    
    tanggalPinjam.addEventListener('change', function() {
        const pinjamDate = new Date(this.value);
        const nextDay = new Date(pinjamDate);
        nextDay.setDate(nextDay.getDate() + 1);
        
        tanggalKembali.min = nextDay.toISOString().split('T')[0];
        
        if (tanggalKembali.value && new Date(tanggalKembali.value) <= pinjamDate) {
            tanggalKembali.value = '';
        }
    });
});
</script>
@endpush
