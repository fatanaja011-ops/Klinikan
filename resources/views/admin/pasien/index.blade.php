<x-app-layout>
    <!-- Alpine.js State untuk Modal Hapus -->
    <div class="py-6" x-data="{ showDeleteModal: false, deleteUrl: '', pasienName: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Data Master</span>
                    <h2 class="text-2xl font-bold text-gray-800">Manajemen Data Pasien</h2>
                </div>
                <a href="{{ route('admin.pasien.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition">
                    + Tambah Pasien
                </a>
            </div>

            <!-- Alert Success -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tabel Data Pasien -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Daftar Pasien Terdaftar</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3">NO. RM</th>
                                <th class="px-6 py-3">NIK</th>
                                <th class="px-6 py-3">NAMA PASIEN</th>
                                <th class="px-6 py-3">L/P</th>
                                <th class="px-6 py-3">TGL LAHIR</th>
                                <th class="px-6 py-3">NO. TELEPON</th>
                                <th class="px-6 py-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($pasiens as $pasien)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-emerald-600 font-mono">{{ $pasien->no_rm }}</td>
                                    <td class="px-6 py-4">{{ $pasien->nik }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $pasien->nama }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded {{ $pasien->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">{{ $pasien->no_telepon }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('admin.pasien.edit', $pasien->id) }}" class="px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md text-xs font-medium">
                                                Edit
                                            </a>
                                            
                                            <button type="button" 
                                                @click="showDeleteModal = true; deleteUrl = '{{ route('admin.pasien.destroy', $pasien->id) }}'; pasienName = '{{ $pasien->nama }}'"
                                                class="px-3 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded-md text-xs font-medium">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-400">Belum ada data pasien.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4">
                    {{ $pasiens->links() }}
                </div>
            </div>
        </div>

        <!-- MODAL KONFIRMASI HAPUS MODERN -->
        <div x-show="showDeleteModal" 
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center p-4">
            
            <!-- Backdrop Transparan dengan Blur Effect -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
                 x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showDeleteModal = false"></div>

            <!-- Card Modal -->
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-6 z-10 transform transition-all"
                 x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="flex items-start space-x-4">
                    <!-- Icon Alert -->
                    <div class="flex-shrink-0 w-11 h-11 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h3 class="text-base font-bold text-gray-900">Konfirmasi Hapus Pasien</h3>
                        <p class="mt-1 text-sm text-gray-500 leading-relaxed">
                            Apakah Anda yakin ingin menghapus data pasien <span class="font-semibold text-gray-800" x-text="pasienName"></span>? Data riwayat rekam medis pasien ini mungkin ikut terpengaruh.
                        </p>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <button type="button" 
                            @click="showDeleteModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>