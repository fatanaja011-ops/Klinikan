<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Top Navigation / Breadcrumb & Actions -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex items-center space-x-2 text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">
                    <span>Pelayanan Klinik</span>
                    <span class="text-gray-300">/</span>
                    <span class="text-gray-500">Rekam Medis</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Pemeriksaan Pasien</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola catatan keluhan medis, pemeriksaan fisik, diagnosis, dan tindakan dokter.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('pemeriksaan.create') }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-medium text-sm rounded-xl shadow-sm hover:shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pemeriksaan
                </a>
            </div>
        </div>

        <!-- Alert Notification -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200/60 rounded-xl flex items-start sm:items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <div class="p-1 bg-emerald-100 text-emerald-700 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-emerald-900">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Main Card Container -->
        <div class="bg-white rounded-2xl border border-gray-200/70 shadow-sm overflow-hidden">
            
            <!-- Table Component -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-200/80 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                            <th class="py-4 px-6">Informasi Pasien & Dokter</th>
                            <th class="py-4 px-6">Keluhan Utama</th>
                            <th class="py-4 px-6">Pemeriksaan Fisik</th>
                            <th class="py-4 px-6">Diagnosis Medis</th>
                            <th class="py-4 px-6">Tindakan</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($pemeriksaans as $item)
                            <tr class="hover:bg-slate-50/60 transition-colors duration-150 group">
                                
                                <!-- Pasien & Dokter -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-full bg-emerald-100/70 text-emerald-700 flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                                            {{ substr($item->pasien->nama ?? 'P', 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 group-hover:text-emerald-700 transition-colors">
                                                {{ $item->pasien->nama ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <span>{{ $item->dokter->nama_dokter ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Keluhan -->
                                <td class="py-4 px-6 max-w-xs">
                                    <p class="text-gray-700 truncate" title="{{ $item->keluhan }}">{{ $item->keluhan }}</p>
                                </td>

                                <!-- Pemeriksaan Fisik -->
                                <td class="py-4 px-6 max-w-xs">
                                    @if($item->pemeriksaan_fisik)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200/50 truncate max-w-full" title="{{ $item->pemeriksaan_fisik }}">
                                            {{ $item->pemeriksaan_fisik }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Tidak ada data</span>
                                    @endif
                                </td>

                                <!-- Diagnosis -->
                                <td class="py-4 px-6 max-w-xs">
                                    <div class="font-medium text-gray-900 truncate" title="{{ $item->diagnosis }}">
                                        {{ $item->diagnosis }}
                                    </div>
                                </td>

                                <!-- Tindakan -->
                                <td class="py-4 px-6 max-w-xs">
                                    @if($item->tindakan)
                                        <p class="text-gray-600 truncate" title="{{ $item->tindakan }}">{{ $item->tindakan }}</p>
                                    @else
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('pemeriksaan.edit_form', $item->id) }}" 
                                           class="p-2 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-all focus:outline-none" 
                                           title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('pemeriksaan.hapus', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pemeriksaan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-all focus:outline-none" 
                                                    title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="max-w-xs mx-auto text-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-900">Belum Ada Catatan Pemeriksaan</h3>
                                        <p class="mt-1 text-xs text-gray-500">Silakan tambahkan data pemeriksaan pasien baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer & Pagination -->
            @if($pemeriksaans->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $pemeriksaans->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>