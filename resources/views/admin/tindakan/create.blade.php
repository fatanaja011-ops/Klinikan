<x-app-layout>
    <div class="py-6 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Tindakan</h2>
        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.tindakan.store') }}" method="POST">
                @csrf
                @include('admin.tindakan._form', ['tindakan' => null, 'submitLabel' => 'Simpan Tindakan'])
            </form>
        </div>
    </div>
</x-app-layout>
