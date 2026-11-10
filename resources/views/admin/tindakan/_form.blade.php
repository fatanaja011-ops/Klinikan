<div class="space-y-4">
    @if($tindakan)
        <div>
            <label class="block text-sm font-medium text-gray-700">Kode Tindakan</label>
            <input type="text" value="{{ $tindakan->kode_tindakan }}" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100">
        </div>
    @endif
    <div>
        <label for="nama_tindakan" class="block text-sm font-medium text-gray-700">Nama Tindakan</label>
        <input id="nama_tindakan" name="nama_tindakan" type="text" value="{{ old('nama_tindakan', $tindakan?->nama_tindakan) }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label for="tarif" class="block text-sm font-medium text-gray-700">Tarif</label>
        <div class="mt-1 flex rounded-md shadow-sm">
            <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">Rp</span>
            <input id="tarif" name="tarif" type="number" min="0" step="0.01" value="{{ old('tarif', $tindakan?->tarif) }}" required class="block w-full rounded-r-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
        </div>
    </div>
    <div>
        <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('keterangan', $tindakan?->keterangan) }}</textarea>
    </div>
</div>
<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.tindakan.index') }}" class="rounded-md bg-gray-100 px-4 py-2 text-gray-700">Batal</a>
    <button type="submit" class="rounded-md bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700">{{ $submitLabel }}</button>
</div>
