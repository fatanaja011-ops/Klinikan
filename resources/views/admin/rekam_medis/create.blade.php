<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Input Hasil Pemeriksaan & Resep</h2>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.rekam-medis.store') }}" method="POST">
                @csrf
                
                <!-- Informasi Antrean Pasien -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Antrean Pasien (Status: Dipanggil)</label>
                    <select name="antrean_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">-- Pilih Pasien Yang Dipanggil --</option>
                        @foreach($antreans as $antrean)
                            <option value="{{ $antrean->id }}" {{ (request('antrean_id') == $antrean->id || old('antrean_id') == $antrean->id) ? 'selected' : '' }}>
                                [{{ $antrean->no_antrean }}] {{ $antrean->pasien->nama }} (Dokter: {{ $antrean->dokter->nama_dokter }})
                            </option>
                        @endforeach
                    </select>
                    @error('antrean_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Hasil Diagnosis & Tindakan -->
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Diagnosa Dokter</label>
                        <textarea name="diagnosa" rows="3" required placeholder="Hasil diagnosa medis..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('diagnosa') }}</textarea>
                        @error('diagnosa') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tindakan Medis (Opsional)</label>
                        <textarea name="tindakan" rows="2" placeholder="Tindakan yang diberikan (misal: suntik, jahit luka, dsb)..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('tindakan') }}</textarea>
                    </div>
                </div>

                <!-- Section Input Dynamic Resep Obat -->
                <div class="border-t border-gray-200 pt-6 mb-6" x-data="{ rows: [{ obat_id: '', jumlah: 1, aturan_pakai: '' }] }">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-bold text-gray-800">Resep Obat Pasien</h3>
                        <button type="button" @click="rows.push({ obat_id: '', jumlah: 1, aturan_pakai: '' })" class="text-xs bg-emerald-50 text-emerald-700 font-semibold px-3 py-1.5 rounded-md hover:bg-emerald-100 border border-emerald-200 transition">
                            + Tambah Obat
                        </button>
                    </div>

                    <template x-for="(row, index) in rows" :key="index">
                        <div class="grid grid-cols-12 gap-3 mb-3 items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <div class="col-span-5">
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Obat</label>
                                <select :name="'obat_id[' + index + ']'" class="w-full text-sm rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach($obats as $obat)
                                        <option value="{{ $obat->id }}">
                                            {{ $obat->nama_obat }} (Stok: {{ $obat->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Jumlah</label>
                                <input type="number" min="1" :name="'jumlah[' + index + ']'" x-model="row.jumlah" class="w-full text-sm rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div class="col-span-4">
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Aturan Pakai</label>
                                <input type="text" placeholder="Contoh: 3x1 Sesudah makan" :name="'aturan_pakai[' + index + ']'" class="w-full text-sm rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div class="col-span-1 text-center pt-5">
                                <button type="button" @click="rows.splice(index, 1)" x-show="rows.length > 1" class="text-red-500 hover:text-red-700 font-bold text-sm">
                                    ✕
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.rekam-medis.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">Simpan Rekam Medis</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>