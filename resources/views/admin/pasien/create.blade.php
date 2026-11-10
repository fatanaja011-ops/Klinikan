<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Pasien Baru</h2>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.pasien.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Rekam Medis (RM)</label>
                        <input type="text" value="{{ $no_rm }}" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm cursor-not-allowed font-mono font-semibold">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIK (16 Digit)</label>
                        <input type="text" name="nik" maxlength="16" value="{{ old('nik') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        @error('nik') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap Pasien</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        @error('nama') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        @error('tanggal_lahir') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Telepon / WA</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                        @error('no_telepon') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat Tempat Tinggal</label>
                        <textarea name="alamat" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">{{ old('alamat') }}</textarea>
                        @error('alamat') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.pasien.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">Simpan Pasien</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>