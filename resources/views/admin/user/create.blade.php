<x-app-layout>
    <div class="py-6 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Pengguna</h2>
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-lg"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                @include('admin.user._form', ['user' => null, 'submitLabel' => 'Simpan Pengguna'])
            </form>
        </div>
    </div>
</x-app-layout>
