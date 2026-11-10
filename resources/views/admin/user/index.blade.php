<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Data Master</span>
                <h2 class="text-2xl font-bold text-gray-800">Data Pengguna</h2>
            </div>
            <a href="{{ route('admin.user.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium shadow-sm">+ Tambah Pengguna</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-lg">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">NAMA</th>
                            <th class="px-6 py-3">EMAIL</th>
                            <th class="px-6 py-3">ROLE</th>
                            <th class="px-6 py-3">DIBUAT</th>
                            <th class="px-6 py-3 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-emerald-100 text-emerald-700' : ($user->role === 'dokter' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ optional($user->created_at)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.user.edit', $user) }}" class="px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md text-xs font-medium">Edit</a>
                                        @if(!$user->is(auth()->user()))
                                            <form action="{{ route('admin.user.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded-md text-xs font-medium">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data pengguna.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
