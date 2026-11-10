<x-app-layout>
    <div class="mx-auto max-w-3xl space-y-6">
        <div><p class="text-sm font-semibold uppercase tracking-wider text-emerald-600">Portal Pasien</p><h1 class="mt-1 text-2xl font-bold text-gray-900">Pendaftaran Mandiri</h1><p class="mt-1 text-sm text-gray-500">Pilih dokter, jadwal, dan tanggal kunjungan.</p></div>
        @if($errors->any())<div class="rounded-lg bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form action="{{ route('pasien.pendaftaran.store') }}" method="POST" class="space-y-5 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            @csrf
            <div><label for="dokter_id" class="block text-sm font-semibold text-gray-700">Dokter</label><select id="dokter_id" name="dokter_id" required class="mt-1 w-full rounded-lg border-gray-300"><option value="">Pilih dokter</option>@foreach($dokters as $dokter)<option value="{{ $dokter->id }}" @selected(old('dokter_id') == $dokter->id)>{{ $dokter->nama_dokter }} - {{ $dokter->spesialisasi }}</option>@endforeach</select></div>
            <div><label for="jadwal_id" class="block text-sm font-semibold text-gray-700">Jadwal</label><select id="jadwal_id" name="jadwal_id" required disabled class="mt-1 w-full rounded-lg border-gray-300"><option value="">Pilih dokter terlebih dahulu</option>@foreach($jadwals as $jadwal)<option value="{{ $jadwal->id }}" data-dokter-id="{{ $jadwal->dokter_id }}" @selected(old('jadwal_id') == $jadwal->id)>{{ $jadwal->hari }}, {{ $jadwal->jam_mulai }}-{{ $jadwal->jam_selesai }} ({{ $jadwal->ruang }})</option>@endforeach</select><p id="jadwal-empty" class="mt-1 hidden text-xs text-amber-600">Belum ada jadwal untuk dokter ini.</p></div>
            <div><label class="block text-sm font-semibold text-gray-700">Tanggal Kunjungan</label><input type="date" name="tgl_kunjungan" min="{{ date('Y-m-d') }}" value="{{ old('tgl_kunjungan') }}" required class="mt-1 w-full rounded-lg border-gray-300"></div>
            <div><label class="block text-sm font-semibold text-gray-700">Keluhan Awal</label><textarea name="keluhan_awal" rows="4" required class="mt-1 w-full rounded-lg border-gray-300">{{ old('keluhan_awal') }}</textarea></div>
            <div class="flex justify-end gap-3"><a href="{{ route('pasien.dashboard') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-700">Batal</a><button class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Daftar Sekarang</button></div>
        </form>
    </div>
    <script>
        const dokterSelect = document.getElementById('dokter_id');
        const jadwalSelect = document.getElementById('jadwal_id');
        const jadwalEmpty = document.getElementById('jadwal-empty');
        const oldJadwal = @json(old('jadwal_id'));

        function filterJadwal() {
            const dokterId = dokterSelect.value;
            let available = 0;

            Array.from(jadwalSelect.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    option.textContent = dokterId ? 'Pilih jadwal' : 'Pilih dokter terlebih dahulu';
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

        dokterSelect.addEventListener('change', filterJadwal);
        filterJadwal();
        if (oldJadwal) jadwalSelect.value = oldJadwal;
    </script>
</x-app-layout>
