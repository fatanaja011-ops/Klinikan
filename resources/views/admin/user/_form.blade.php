<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user?->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user?->email) }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
    </div>
    <div>
        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
        <select id="role" name="role" required class="mt-1 block w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
            @foreach(['admin' => 'Admin', 'dokter' => 'Dokter', 'pasien' => 'Pasien'] as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user?->role ?? 'pasien') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password {{ $user ? '(kosongkan jika tidak diubah)' : '' }}</label>
        <input id="password" name="password" type="password" {{ $user ? '' : 'required' }} class="mt-1 block w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
    </div>
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" {{ $user ? '' : 'required' }} class="mt-1 block w-full rounded-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
    </div>
</div>
<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.user.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md">Batal</a>
    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">{{ $submitLabel }}</button>
</div>
