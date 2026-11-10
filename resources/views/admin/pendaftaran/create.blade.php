<x-app-layout>
    <div class="mx-auto max-w-4xl space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-gray-900">Form Pendaftaran Pasien</h1>
            </div>
        </div>

        <!-- Form Card Container -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.pendaftaran.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <!-- Pilih Pasien -->
                    <div class="md:col-span-2">
                        <label for="pasien_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Pilih Pasien <span class="text-red-500">*</span>
                        </label>
                        <select name="pasien_id" id="pasien_id" required class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($pasiens as $pasien)
                                <option value="{{ $pasien->id }}" data-nama="{{ $pasien->nama ?? $pasien->nama_pasien ?? 'Pasien #'.$pasien->id }}" {{ old('pasien_id') == $pasien->id ? 'selected' : '' }}>
                                    {{ $pasien->nama ?? $pasien->nama_pasien ?? 'Pasien #'.$pasien->id }} - Tanggal Kunjungan: {{ old('tgl_kunjungan', date('Y-m-d')) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilih Dokter -->
                    <div>
                        <label for="dokter_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Pilih Dokter <span class="text-red-500">*</span>
                        </label>
                        <select name="dokter_id" id="dokter_id" required class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id }}" {{ old('dokter_id') == $dokter->id ? 'selected' : '' }}>
                                    {{ $dokter->nama_dokter ?? $dokter->user->name ?? 'Dokter #'.$dokter->id }}
                                    - {{ $dokter->spesialisasi ?? 'Spesialisasi belum diatur' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilih Jadwal Praktik -->
                    <div>
                        <label for="jadwal_dokter_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Jadwal Praktik <span class="text-red-500">*</span>
                        </label>
                        <select name="jadwal_dokter_id" id="jadwal_dokter_id" required disabled class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                            <option value="">Pilih dokter terlebih dahulu</option>
                            @foreach($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}" data-dokter-id="{{ $jadwal->dokter_id }}" {{ old('jadwal_dokter_id') == $jadwal->id ? 'selected' : '' }}>
                                    {{ $jadwal->hari }} ({{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}) - {{ $jadwal->ruang ?? 'Poliklinik' }}
                                </option>
                            @endforeach
                        </select>
                        <p id="jadwal-empty" class="mt-1 hidden text-xs text-amber-600">Belum ada jadwal untuk dokter ini.</p>
                    </div>

                    <!-- Tanggal Kunjungan -->
                    <div class="md:col-span-2">
                        <label for="tgl_kunjungan" class="mb-2 block text-sm font-medium text-gray-700">
                            Tanggal Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tgl_kunjungan" id="tgl_kunjungan" value="{{ old('tgl_kunjungan', date('Y-m-d')) }}" required class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    </div>

                    <!-- Keluhan Awal -->
                    <div class="md:col-span-2">
                        <label for="keluhan_awal" class="mb-2 block text-sm font-medium text-gray-700">
                            Keluhan Awal <span class="text-red-500">*</span>
                        </label>
                        <textarea name="keluhan_awal" id="keluhan_awal" rows="3" placeholder="Tuliskan keluhan utama pasien saat ini..." required class="block w-full rounded-xl border border-gray-200 px-3 py-2.5 text-gray-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">{{ old('keluhan_awal') }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.pendaftaran.index') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        Daftarkan Pasien
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const pasienSelect = document.getElementById('pasien_id');
        const tanggalKunjungan = document.getElementById('tgl_kunjungan');
        const dokterSelect = document.getElementById('dokter_id');
        const jadwalSelect = document.getElementById('jadwal_dokter_id');
        const jadwalEmpty = document.getElementById('jadwal-empty');
        const oldJadwal = @json(old('jadwal_dokter_id'));

        function filterJadwal() {
            const dokterId = dokterSelect.value;
            let available = 0;

            Array.from(jadwalSelect.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    option.textContent = dokterId ? '-- Pilih Jadwal Praktik --' : 'Pilih dokter terlebih dahulu';
                    return;
                }

                const matches = option.dataset.dokterId === dokterId;
                option.hidden = !matches;
                option.disabled = !matches;
                if (matches) available++;
            });

            jadwalSelect.disabled = !dokterId || available === 0;
            jadwalEmpty.classList.toggle('hidden', !dokterId || available > 0);
            if (!jadwalSelect.value || jadwalSelect.options[jadwalSelect.selectedIndex]?.disabled) {
                jadwalSelect.value = '';
            }
        }

        function perbaruiLabelPasien() {
            const tanggal = tanggalKunjungan.value || '-';

            Array.from(pasienSelect.options).forEach((option) => {
                if (!option.dataset.nama) {
                    return;
                }

                option.textContent = `${option.dataset.nama} - Tanggal Kunjungan: ${tanggal}`;
            });
        }

        tanggalKunjungan.addEventListener('change', perbaruiLabelPasien);
        dokterSelect.addEventListener('change', filterJadwal);
        perbaruiLabelPasien();
        filterJadwal();
        if (oldJadwal) jadwalSelect.value = oldJadwal;
    </script>
</x-app-layout>