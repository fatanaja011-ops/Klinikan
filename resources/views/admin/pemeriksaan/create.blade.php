<x-app-layout>
    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('pemeriksaan.data') }}" class="hover:text-emerald-600 transition">Pemeriksaan Pasien</a>
                    <span>/</span>
                    <span class="text-gray-800 font-medium">Tambah Pemeriksaan</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Input Hasil Pemeriksaan Pasien</h2>
                <p class="text-sm text-gray-500">Catat detail keluhan, pemeriksaan fisik, diagnosis, dan tindakan medis.</p>
            </div>
        </div>

        <!-- Form Card Utama -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="p-6 sm:p-8">
                <form action="{{ route('pemeriksaan.add') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Pilih Antrean Pasien -->
                        <div>
                            <label for="antrean_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Pilih Antrean Pasien <span class="text-red-500">*</span>
                            </label>
                            <select name="antrean_id" id="antrean_id" required 
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-gray-800 font-medium py-3 px-4 transition">
                                <option value="">-- Pilih Pasien Yang Dipanggil / Diperiksa --</option>
                                @forelse($antreans as $antrean)
                                    <option value="{{ $antrean->id }}">
                                        [{{ $antrean->no_antrean }}] {{ $antrean->pasien->nama ?? '-' }} — Dokter: {{ $antrean->dokter->nama_dokter ?? '-' }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada pasien yang sedang mengantre</option>
                                @endforelse
                            </select>
                            @error('antrean_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keluhan -->
                        <div>
                            <label for="keluhan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Keluhan Pasien <span class="text-red-500">*</span>
                            </label>
                            <textarea name="keluhan" id="keluhan" rows="3" required
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-gray-800 text-sm p-4 transition"
                                placeholder="Tuliskan keluhan utama pasien..."></textarea>
                            @error('keluhan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pemeriksaan Fisik -->
                        <div>
                            <label for="pemeriksaan_fisik" class="block text-sm font-semibold text-gray-700 mb-2">
                                Pemeriksaan Fisik (Opsional)
                            </label>
                            <textarea name="pemeriksaan_fisik" id="pemeriksaan_fisik" rows="2"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-gray-800 text-sm p-4 transition"
                                placeholder="Contoh: Tekanan Darah: 120/80 mmHg, Suhu: 36.5°C, Berat Badan: 65 kg..."></textarea>
                        </div>

                        <!-- Diagnosis -->
                        <div>
                            <label for="diagnosis" class="block text-sm font-semibold text-gray-700 mb-2">
                                Diagnosis Dokter <span class="text-red-500">*</span>
                            </label>
                            <textarea name="diagnosis" id="diagnosis" rows="3" required
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-gray-800 text-sm p-4 transition"
                                placeholder="Hasil diagnosis medis..."></textarea>
                            @error('diagnosis')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tindakan Medis dari Data Master -->
                        <div>
                            <label for="tindakan_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tindakan Medis (Opsional)
                            </label>
                            <select name="tindakan_id" id="tindakan_id"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-gray-800 text-sm p-3 transition">
                                <option value="">-- Tidak ada tindakan / pilih nanti --</option>
                                @foreach($tindakans as $tindakan)
                                    <option value="{{ $tindakan->id }}">
                                        {{ $tindakan->nama_tindakan }} - Rp {{ number_format($tindakan->tarif, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Tarif tindakan terpilih akan otomatis masuk ke tagihan pembayaran.</p>
                        </div>

                        <!-- Catatan Pemeriksaan -->
                        <div>
                            <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Catatan Pemeriksaan (Opsional)
                            </label>
                            <textarea name="catatan" id="catatan" rows="2"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 text-gray-800 text-sm p-4 transition"
                                placeholder="Catatan tambahan untuk pasien atau kontrol ulang..."></textarea>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('pemeriksaan.data') }}" 
                           class="px-5 py-2.5 bg-white text-gray-700 font-medium text-sm rounded-xl border border-gray-300 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 bg-emerald-600 text-white font-medium text-sm rounded-xl shadow-sm hover:bg-emerald-700 transition">
                            Simpan Pemeriksaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>