<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" 
         x-data="{ 
             selectedId: '{{ old('pendaftaran_id', '') }}',
             pendaftarans: {{ json_encode($pendaftarans) }},
             get selectedData() {
                 return this.pendaftarans.find(p => p.id == this.selectedId) || null;
             }
         }">
        
        <!-- Header & Breadcrumb -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('admin.antrean.index') }}" class="hover:text-emerald-600 transition">Manajemen Antrean</a>
                    <span>/</span>
                    <span class="text-gray-800 font-medium">Buat Antrean Baru</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Pendaftaran Antrean Pasien</h2>
                <p class="text-sm text-gray-500">Pilih pendaftaran aktif untuk menerbitkan nomor urut antrean klinik.</p>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                    {{ $pendaftarans->count() }} Pendaftaran Siap Diantrekan
                </span>
            </div>
        </div>

        <!-- Form Card Utama -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="p-6 sm:p-8">
                <form action="{{ route('admin.antrean.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Dropdown Select Pendaftaran -->
                        <div>
                            <label for="pendaftaran_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Registrasi Pasien Hari Ini <span class="text-red-500">*</span>
                            </label>
                            
                            <select 
                                name="pendaftaran_id" 
                                id="pendaftaran_id" 
                                x-model="selectedId"
                                required 
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-gray-800 font-medium py-3 px-4 transition">
                                <option value="">-- Pilih Pendaftaran Pasien --</option>
                                @forelse($pendaftarans as $pendaftaran)
                                    <option value="{{ $pendaftaran->id }}">
                                        [{{ $pendaftaran->no_pendaftaran }}] {{ $pendaftaran->pasien->nama ?? '-' }} — {{ $pendaftaran->dokter->nama_dokter ?? 'Dokter' }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada pendaftaran pasien baru hari ini</option>
                                @endforelse
                            </select>

                            @error('pendaftaran_id')
                                <p class="mt-2 text-xs text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Card Preview Informasi Pasien -->
                        <div x-show="selectedData !== null" x-transition.opacity.duration.300ms class="mt-4 p-5 rounded-xl bg-slate-50 border border-slate-200">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Ringkasan Pendaftaran</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500 block text-xs">Nomor Registrasi:</span>
                                    <span class="font-mono font-semibold text-gray-800" x-text="selectedData?.no_pendaftaran || '-'"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block text-xs">Nama Pasien:</span>
                                    <span class="font-semibold text-gray-800" x-text="selectedData?.pasien?.nama || '-'"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block text-xs">Dokter Tujuan:</span>
                                    <span class="font-semibold text-emerald-700" x-text="selectedData?.dokter?.nama_dokter || '-'"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block text-xs">Keluhan Awal:</span>
                                    <span class="text-gray-700 italic" x-text="selectedData?.keluhan_awal || 'Tidak ada keluhan'"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.antrean.index') }}" 
                           class="px-5 py-2.5 bg-white text-gray-700 font-medium text-sm rounded-xl border border-gray-300 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        
                        <button type="submit" 
                                {{ $pendaftarans->isEmpty() ? 'disabled' : '' }}
                                class="inline-flex items-center px-6 py-2.5 bg-emerald-600 text-white font-medium text-sm rounded-xl shadow-sm hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Terbitkan Antrean
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>