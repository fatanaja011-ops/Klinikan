<x-app-layout>
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">Tambah Data Obat</h1>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.obat.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Kode Obat</label>
                        <input type="text" name="kode_obat" value="{{ $kode_obat }}" readonly class="block w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-gray-700 focus:outline-none">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Nama Obat</label>
                        <input type="text" name="nama_obat" value="{{ old('nama_obat') }}" required class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Jenis Obat</label>
                        <input type="text" name="jenis_obat" value="{{ old('jenis_obat') }}" placeholder="Tablet, Sirup, Salep" required class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Satuan</label>
                        <input type="text" name="satuan" value="{{ old('satuan') }}" placeholder="Botol, Strip, Pcs" required class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" name="harga" value="{{ old('harga') }}" required class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stok" value="{{ old('stok') }}" required class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Kedaluwarsa</label>
                        <input type="date" name="tanggal_kedaluwarsa" value="{{ old('tanggal_kedaluwarsa') }}" required class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.obat.index') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">Batal</a>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Simpan Obat</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
