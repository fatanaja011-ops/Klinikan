<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Dokter Baru</h2>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.dokter.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kode Dokter</label>
                        <input type="text" value="{{ $kode_dokter }}" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Dokter</label>
                        <input type="text" name="nama_dokter" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Login</label>
                        <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                        <input type="text" name="spesialisasi" placeholder="misal: Umum, Gigi, Anak" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <input type="text" name="no_telepon" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.dokter.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Simpan Dokter</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>