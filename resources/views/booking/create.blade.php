@extends('layouts.admin.app')
@section('title', 'Booking Homestay Baru')

@push('styles')
    <style>
        .fade-in {
            animation: fade .4s ease-in-out;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .calendar-box {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 12px;
            background: #f9fafb;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid fade-in" style="padding-top:35px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-blue mb-0">📝 Booking Homestay Baru</h3>
                <small class="text-muted">Pilih homestay, kamar, kemudian tanggal check-in & check-out.</small>
            </div>
            <a href="{{ route('booking.index') }}" class="btn btn-secondary">
                ← Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- KIRI --}}
                        <div class="col-md-7">

                            {{-- Homestay --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih Homestay</label>
                                <select name="homestay_id" id="homestaySelect" class="form-select" required>
                                    <option value="">-- Pilih Homestay --</option>
                                    @foreach ($homestay as $h)
                                        <option value="{{ $h->homestay_id }}">
                                            {{ $h->nama }} (RT {{ $h->rt }}, RW {{ $h->rw }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kamar (AJAX) --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih Kamar</label>
                                <select name="kamar_id" id="kamarSelect" class="form-select" required>
                                    <option value="">-- Pilih Homestay dulu --</option>
                                </select>
                                <small class="text-muted" id="kamarInfo"></small>
                            </div>

                            {{-- Warga (Pemesan) --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pemesan (Warga)</label>
                                <select name="warga_id" class="form-select" required>
                                    <option value="">-- Pilih Warga --</option>
                                    @foreach ($warga as $w)
                                        <option value="{{ $w->warga_id }}">
                                            {{ $w->nama }} - {{ $w->no_ktp }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Metode Bayar --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Metode Pembayaran</label>
                                <select name="metode_bayar" class="form-select" required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="cash">Cash</option>
                                </select>
                            </div>

                            {{-- Bukti Bayar --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Bukti Pembayaran (Opsional)</label>
                                <input type="file" name="bukti_bayar" class="form-control" accept="image/*">
                                @error('bukti_bayar')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>

                        {{-- KANAN: CALENDAR & TOTAL --}}
                        <div class="col-md-5">

                            <div class="calendar-box mb-3">
                                <h6 class="fw-semibold mb-2">Pilih Tanggal</h6>

                                <div class="mb-2">
                                    <label class="form-label">Check-in</label>
                                    <input type="date" name="checkin" id="checkin" class="form-control" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Check-out</label>
                                    <input type="date" name="checkout" id="checkout" class="form-control" required>
                                </div>

                                <small class="text-muted" id="calendarInfo">
                                    Pilih kamar dulu untuk memuat tanggal yang sudah terbooking.
                                </small>
                            </div>

                            {{-- Total Harga --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Total Harga (otomatis)</label>
                                <input type="text" id="totalDisplay" class="form-control" readonly placeholder="0">
                                <input type="hidden" name="total_hidden" id="totalHidden">
                                <small class="text-muted" id="hargaInfo">
                                    Pilih kamar dan tanggal untuk menghitung total.
                                </small>
                            </div>

                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('booking.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan Booking
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        let kamarData = {}; // {kamar_id: {harga, kapasitas, nama_kamar}}
        let disabledDates = []; // tanggal yang tidak boleh dipilih
        let hargaKamarTerpilih = 0; // harga per malam

        const homestaySelect = document.getElementById('homestaySelect');
        const kamarSelect = document.getElementById('kamarSelect');
        const kamarInfo = document.getElementById('kamarInfo');
        const checkinInput = document.getElementById('checkin');
        const checkoutInput = document.getElementById('checkout');
        const totalDisplay = document.getElementById('totalDisplay');
        const totalHidden = document.getElementById('totalHidden');
        const hargaInfo = document.getElementById('hargaInfo');
        const calendarInfo = document.getElementById('calendarInfo');

        // Helper format rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(angka || 0);
        }

        // Hitung total harga
        function hitungTotal() {
            if (!hargaKamarTerpilih || !checkinInput.value || !checkoutInput.value) {
                totalDisplay.value = '';
                totalHidden.value = '';
                return;
            }

            const start = new Date(checkinInput.value);
            const end = new Date(checkoutInput.value);

            if (end <= start) {
                alert('Checkout harus setelah check-in');
                checkoutInput.value = '';
                totalDisplay.value = '';
                totalHidden.value = '';
                return;
            }

            const diffTime = end - start;
            const days = diffTime / (1000 * 60 * 60 * 24);

            const total = days * hargaKamarTerpilih;
            totalDisplay.value = formatRupiah(total);
            totalHidden.value = total;
        }

        // Cek tanggal apakah disabled
        function isDisabledDate(dateStr) {
            return disabledDates.includes(dateStr);
        }

        function validateDateInput(input) {
            const val = input.value;
            if (!val) return;

            if (isDisabledDate(val)) {
                alert('Tanggal ini sudah dibooking, silakan pilih tanggal lain.');
                input.value = '';
                hitungTotal();
            } else {
                hitungTotal();
            }
        }

        // Event change tanggal
        checkinInput.addEventListener('change', () => validateDateInput(checkinInput));
        checkoutInput.addEventListener('change', () => validateDateInput(checkoutInput));

        // Ketika homestay dipilih → ambil kamar via AJAX
        homestaySelect.addEventListener('change', function() {
            const id = this.value;
            kamarSelect.innerHTML = '<option value="">Memuat kamar...</option>';
            kamarInfo.textContent = '';
            hargaInfo.textContent = 'Pilih kamar dan tanggal untuk menghitung total.';
            hargaKamarTerpilih = 0;
            totalDisplay.value = '';
            totalHidden.value = '';

            if (!id) {
                kamarSelect.innerHTML = '<option value="">-- Pilih Homestay dulu --</option>';
                return;
            }

            fetch("{{ url('/booking/get-kamar') }}/" + id)
                .then(res => res.json())
                .then(data => {
                    kamarData = {};
                    kamarSelect.innerHTML = '<option value="">-- Pilih Kamar --</option>';

                    data.forEach(km => {
                        kamarData[km.kamar_id] = {
                            harga: km.harga,
                            kapasitas: km.kapasitas,
                            nama_kamar: km.nama_kamar
                        };
                        kamarSelect.innerHTML += `
                        <option value="${km.kamar_id}">
                            ${km.nama_kamar} (Rp ${km.harga.toLocaleString('id-ID')} / malam)
                        </option>
                    `;
                    });

                    if (data.length === 0) {
                        kamarSelect.innerHTML = '<option value="">Belum ada kamar untuk homestay ini</option>';
                    }
                })
                .catch(() => {
                    kamarSelect.innerHTML = '<option value="">Gagal memuat kamar</option>';
                });
        });

        // Ketika kamar dipilih → load calendar booked dates
        kamarSelect.addEventListener('change', function() {
            const kamarId = this.value;
            kamarInfo.textContent = '';
            disabledDates = [];
            hargaKamarTerpilih = 0;
            totalDisplay.value = '';
            totalHidden.value = '';

            if (!kamarId || !kamarData[kamarId]) {
                calendarInfo.textContent = 'Pilih kamar dulu untuk memuat tanggal yang sudah terbooking.';
                return;
            }

            const dataKamar = kamarData[kamarId];
            hargaKamarTerpilih = dataKamar.harga;
            kamarInfo.textContent =
                `Kapasitas: ${dataKamar.kapasitas} orang, harga per malam: ${formatRupiah(dataKamar.harga)}.`;

            fetch("{{ url('/booking/calendar') }}/" + kamarId)
                .then(res => res.json())
                .then(data => {
                    disabledDates = data.disable_dates || [];
                    const minDate = data.min_date || null;

                    if (minDate) {
                        checkinInput.setAttribute('min', minDate);
                        checkoutInput.setAttribute('min', minDate);
                    }

                    calendarInfo.textContent = 'Tanggal yang sudah dibooking tidak bisa dipilih.';
                })
                .catch(() => {
                    calendarInfo.textContent = 'Gagal memuat informasi kalender.';
                });
        });
    </script>
@endpush
