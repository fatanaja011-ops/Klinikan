<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <!-- Header & Tombol Tambah (Opsional) -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Rekam Medis Pasien</h1>
            {{--
            <a href="{{ route('dokter.rekam-medis.create') }}" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                + Tambah Rekam Medis
            </a>
            --}}
        </div>

        <!-- Daftar Rekam Medis -->
        <div class="space-y-4">
            @forelse($data as $item)
                <article class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="font-semibold text-gray-900">
                                {{ $item->pasien->nama ?? $item->pasien->nama_lengkap ?? '-' }}
                            </h2>
                            <p class="text-sm text-gray-500">
                                @if(isset($item->tgl_rekam_medis))
                                    {{ \Carbon\Carbon::parse($item->tgl_rekam_medis)->format('d/m/Y') }}
                                @elseif(isset($item->tanggal_periksa))
                                    {{ \Carbon\Carbon::parse($item->tanggal_periksa)->format('d/m/Y') }}
                                @else
                                    {{ $item->created_at?->format('d/m/Y') ?? '-' }}
                                @endif
                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700">
                            {{ $item->diagnosa ?? $item->diagnosis ?? 'Belum ada diagnosa' }}
                        </span>
                    </div>

                    @if(!empty($item->keluhan))
                        <p class="mt-2 text-sm text-gray-600">
                            <strong class="text-gray-700">Keluhan:</strong> {{ $item->keluhan }}
                        </p>
                    @endif

                    <p class="mt-2 text-sm text-gray-600">
                        <strong class="text-gray-700">Tindakan:</strong> {{ $item->tindakan ?? '-' }}
                    </p>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-gray-200 bg-white p-8 text-center text-gray-500">
                    Belum ada data rekam medis.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div>
            {{ $data->links() }}
        </div>
    </div>
</x-app-layout>