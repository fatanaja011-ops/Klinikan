<x-app-layout>
    <!-- Import Library Midtrans Snap -->
    <script 
        src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>

    <div class="p-6 space-y-6">
        <div>
            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Kasir & Transaksi</span>
            <h2 class="text-2xl font-bold text-gray-800">Pembayaran Baru</h2>
            <p class="mt-1 text-sm text-gray-500">Pilih pemeriksaan yang belum memiliki pembayaran.</p>
        </div>

        @if($errors->any())
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($pemeriksaans->isEmpty())
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-6 text-sm text-emerald-800">
                Semua pemeriksaan sudah memiliki pembayaran.
            </div>
        @else
            <form id="form-pembayaran" action="{{ route('admin.pembayaran.store') }}" method="POST" class="grid gap-6 lg:grid-cols-[1fr_380px]">
                @csrf
                <input type="hidden" name="metode_pembayaran" id="input_metode_pembayaran" value="Tunai">

                <!-- Sisi Kiri: Pilihan Pemeriksaan & Ringkasan -->
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                    <label for="pemeriksaan_id" class="mb-2 block text-sm font-semibold text-gray-700">Pemeriksaan</label>
                    <select id="pemeriksaan_id" name="pemeriksaan_id" required class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Pilih pemeriksaan</option>
                        @foreach($pemeriksaans as $pemeriksaan)
                            <option value="{{ $pemeriksaan->id }}" @selected(old('pemeriksaan_id') == $pemeriksaan->id)>
                                {{ $pemeriksaan->pasien->nama ?? 'Pasien' }} - {{ $pemeriksaan->diagnosis }}
                            </option>
                        @endforeach
                    </select>

                    <div id="patient-summary" class="mt-5 hidden rounded-lg bg-gray-50 p-4 text-sm text-gray-600">
                        <div class="grid gap-2 sm:grid-cols-2">
                            <div><span class="text-gray-400">Pasien</span><p id="patient-name" class="font-semibold text-gray-800">-</p></div>
                            <div><span class="text-gray-400">Dokter</span><p id="doctor-name" class="font-semibold text-gray-800">-</p></div>
                            <div><span class="text-gray-400">Diagnosis</span><p id="diagnosis" class="font-semibold text-gray-800">-</p></div>
                            <div><span class="text-gray-400">Tanggal</span><p id="exam-date" class="font-semibold text-gray-800">-</p></div>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Rincian & Metode Pembayaran -->
                <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-800">Rincian Tagihan</h3>
                    <div id="bill-items" class="mt-4 space-y-3 text-sm text-gray-600">
                        <p class="text-gray-400">Pilih pemeriksaan untuk melihat rincian.</p>
                    </div>

                    <div class="mt-5 border-t border-gray-100 pt-4">
                        <div class="flex items-center justify-between font-bold text-gray-900">
                            <span>Total Tagihan</span>
                            <span id="bill-total" class="text-emerald-600">Rp 0</span>
                        </div>

                        <!-- Pilihan Metode Pembayaran -->
                        <div class="mt-5">
                            <label class="mb-2 block text-sm font-semibold text-gray-700">Metode Pembayaran</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" id="btn-method-tunai" onclick="setMethod('Tunai')" class="rounded-lg border border-emerald-600 bg-emerald-50 py-2 text-sm font-semibold text-emerald-700">
                                    💵 Tunai
                                </button>
                                <button type="button" id="btn-method-midtrans" onclick="setMethod('Midtrans')" class="rounded-lg border border-gray-200 bg-white py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                                    📱 QRIS / Midtrans
                                </button>
                            </div>
                        </div>

                        <!-- Input Uang Tunai -->
                        <div id="container-cash" class="mt-4">
                            <label for="nominal_bayar" class="mb-2 block text-sm font-semibold text-gray-700">Nominal Dibayar</label>
                            <input id="nominal_bayar" name="nominal_bayar" type="number" min="0" value="{{ old('nominal_bayar') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <p id="change-preview" class="mt-2 text-xs text-gray-500">Kembalian akan dihitung setelah nominal diisi.</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('admin.pembayaran.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-600 hover:bg-gray-200">Batal</a>
                        <button type="button" id="btn-submit" onclick="submitPayment()" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                            Simpan Pembayaran
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <script>
        const payments = @json($paymentData);

        const select = document.getElementById('pemeriksaan_id');
        const nominal = document.getElementById('nominal_bayar');
        const summary = document.getElementById('patient-summary');
        const billItems = document.getElementById('bill-items');
        const billTotal = document.getElementById('bill-total');
        const changePreview = document.getElementById('change-preview');
        const containerCash = document.getElementById('container-cash');
        const inputMethod = document.getElementById('input_metode_pembayaran');
        const btnTunai = document.getElementById('btn-method-tunai');
        const btnMidtrans = document.getElementById('btn-method-midtrans');
        const btnSubmit = document.getElementById('btn-submit');

        let selectedTotal = 0;
        let selectedMethod = 'Tunai';

        const rupiah = (value) => new Intl.NumberFormat('id-ID').format(value);

        function setMethod(method) {
            selectedMethod = method;
            inputMethod.value = method;

            if (method === 'Tunai') {
                btnTunai.className = 'rounded-lg border border-emerald-600 bg-emerald-50 py-2 text-sm font-semibold text-emerald-700';
                btnMidtrans.className = 'rounded-lg border border-gray-200 bg-white py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50';
                containerCash.classList.remove('hidden');
                nominal.required = true;
                btnSubmit.innerText = 'Simpan Pembayaran';
            } else {
                btnMidtrans.className = 'rounded-lg border border-emerald-600 bg-emerald-50 py-2 text-sm font-semibold text-emerald-700';
                btnTunai.className = 'rounded-lg border border-gray-200 bg-white py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50';
                containerCash.classList.add('hidden');
                nominal.required = false;
                btnSubmit.innerText = 'Bayar via Midtrans';
            }
        }

        function renderPayment() {
            const payment = payments[select.value];
            if (!payment) {
                summary.classList.add('hidden');
                billItems.innerHTML = '<p class="text-gray-400">Pilih pemeriksaan untuk melihat rincian.</p>';
                billTotal.textContent = 'Rp 0';
                selectedTotal = 0;
                return;
            }

            summary.classList.remove('hidden');
            document.getElementById('patient-name').textContent = payment.patient;
            document.getElementById('doctor-name').textContent = payment.doctor;
            document.getElementById('diagnosis').textContent = payment.diagnosis;
            document.getElementById('exam-date').textContent = payment.date;
            selectedTotal = Number(payment.total);
            billTotal.textContent = `Rp ${rupiah(selectedTotal)}`;
            billItems.innerHTML = payment.items.map((item) => `
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                    <span>${item.nama_pelayanan} <span class="text-xs text-gray-400">x${item.jumlah}</span></span>
                    <span class="font-medium text-gray-800">Rp ${rupiah(item.subtotal)}</span>
                </div>
            `).join('');
            
            updateChange();
        }

        function updateChange() {
            if (selectedMethod !== 'Tunai') return;
            const paid = Number(nominal.value || 0);
            if (!selectedTotal || !paid) {
                changePreview.textContent = 'Kembalian akan dihitung setelah nominal diisi.';
                changePreview.className = 'mt-2 text-xs text-gray-500';
                return;
            }

            const change = paid - selectedTotal;
            changePreview.textContent = change >= 0 ? `Kembalian: Rp ${rupiah(change)}` : `Kurang: Rp ${rupiah(Math.abs(change))}`;
            changePreview.className = `mt-2 text-xs ${change >= 0 ? 'text-emerald-600 font-semibold' : 'text-red-600 font-semibold'}`;
        }

        function submitPayment() {
            if (!select.value) {
                alert('Pilih pemeriksaan terlebih dahulu!');
                return;
            }

            if (selectedMethod === 'Tunai') {
                const paid = Number(nominal.value || 0);
                if (paid < selectedTotal) {
                    alert('Nominal dibayar kurang dari total tagihan!');
                    return;
                }
                document.getElementById('form-pembayaran').submit();
            } else {
                btnSubmit.disabled = true;
                btnSubmit.innerText = 'Memproses...';

                // Proses Midtrans Snap
                fetch("{{ route('admin.pembayaran.snap_token') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ pemeriksaan_id: select.value })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        throw new Error(data.error || data.message || 'Gagal membuat token pembayaran.');
                    }
                    return data;
                })
                .then(data => {
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = 'Bayar via Midtrans';

                    if (data.snap_token) {
                        if (!window.snap) {
                            throw new Error('Midtrans Snap belum termuat. Periksa koneksi internet atau client key.');
                        }

                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                alert('Pembayaran Berhasil!');
                                window.location.href = "{{ route('admin.pembayaran.index') }}";
                            },
                            onPending: function(result) {
                                alert('Menunggu Pembayaran...');
                                window.location.href = "{{ route('admin.pembayaran.index') }}";
                            },
                            onError: function(result) {
                                alert('Pembayaran Gagal!');
                            },
                            onClose: function() {
                                alert('Pop-up pembayaran ditutup.');
                            }
                        });
                    } else {
                        alert('Gagal mendapatkan token: ' + (data.error || 'Terjadi kesalahan server.'));
                    }
                })
                .catch(err => {
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = 'Bayar via Midtrans';
                    console.error('Midtrans:', err);
                    alert('Pembayaran Midtrans gagal: ' + err.message);
                });
            }
        }

        select.addEventListener('change', renderPayment);
        nominal.addEventListener('input', updateChange);
        renderPayment();
    </script>
</x-app-layout>