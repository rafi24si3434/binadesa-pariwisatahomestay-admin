@extends('layouts.admin.app')
@section('title', 'Edit Booking Homestay')

@push('styles')
    <style>
        .fade-in {
            animation: fade .4s ease-in-out;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .preview-img {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
            margin-right: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid fade-in" style="padding-top:35px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h3 class="fw-bold text-blue">✏️ Edit Booking Homestay</h3>
                <p class="text-muted small mb-0">Perbarui status & metode pembayaran booking.</p>
            </div>

            <a href="{{ route('booking.index') }}" class="btn btn-secondary shadow-sm" style="border-radius:8px;">← Kembali</a>
        </div>

        <div class="card shadow-sm p-4" style="border-radius:12px;">
            <form action="{{ route('booking.update', $booking->booking_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- INFO BOOKING --}}
                    <div class="col-md-7">
                        <h5 class="fw-bold">Informasi Pemesanan</h5>
                        <hr>

                        <p class="mb-2"><strong>🔑 Booking ID:</strong> #{{ $booking->booking_id }}</p>
                        <p class="mb-2"><strong>👤 Pemesan:</strong> {{ $booking->warga->nama ?? '-' }}</p>
                        <p class="mb-2"><strong>🏠 Homestay:</strong> {{ $booking->kamar->homestay->nama ?? '-' }}</p>
                        <p class="mb-2"><strong>🛏️ Kamar:</strong> {{ $booking->kamar->nama_kamar ?? '-' }}</p>
                        <p class="mb-2"><strong>📅 Check-in:</strong>
                            {{ \Carbon\Carbon::parse($booking->checkin)->format('d M Y') }}</p>
                        <p class="mb-2"><strong>📅 Check-out:</strong>
                            {{ \Carbon\Carbon::parse($booking->checkout)->format('d M Y') }}</p>
                        <p class="mb-2"><strong>💰 Total:</strong> Rp {{ number_format($booking->total, 0, ',', '.') }}
                        </p>

                        {{-- STATUS --}}
                        <div class="mt-4">
                            <label class="form-label fw-semibold">Status Booking</label>
                            <select name="status" class="form-select" required style="border-radius:8px;">
                                {{-- PERUBAHAN: Value dan pengecekan menggunakan huruf awal kapital --}}
                                <option value="Pending" {{ ucfirst($booking->status) == 'Pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="Lunas" {{ ucfirst($booking->status) == 'Lunas' ? 'selected' : '' }}>Lunas
                                </option>
                                <option value="Batal" {{ ucfirst($booking->status) == 'Batal' ? 'selected' : '' }}>Batal
                                </option>
                            </select>
                            @error('status')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- METODE BAYAR --}}
                        <div class="mt-3">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <select name="metode_bayar" class="form-select" required style="border-radius:8px;">
                                <option value="transfer"
                                    {{ strtolower($booking->metode_bayar) == 'transfer' ? 'selected' : '' }}>Transfer
                                </option>
                                <option value="cash"
                                    {{ strtolower($booking->metode_bayar) == 'cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                            @error('metode_bayar')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- BUKTI BAYAR --}}
                    <div class="col-md-5">
                        <h5 class="fw-bold">Bukti Pembayaran</h5>
                        <hr>

                        {{-- Tampilkan bukti lama --}}
                        @if ($booking->media)
                            <div class="mb-3 text-center p-3 bg-light rounded" style="border: 1px dashed #ccc;">
                                <p class="text-muted small mb-2">Bukti pembayaran saat ini:</p>
                                <img src="{{ asset('storage/' . $booking->media->file_url) }}"
                                    class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: contain;"
                                    alt="bukti pembayaran">
                            </div>
                        @else
                            <div class="mb-3 p-4 text-center bg-light rounded text-muted" style="border: 1px dashed #ccc;">
                                <i class="fa fa-image fs-1 mb-2"></i>
                                <p class="mb-0">Belum ada bukti pembayaran.</p>
                            </div>
                        @endif

                        {{-- Upload bukti baru --}}
                        <div class="mt-3">
                            <label class="form-label fw-semibold">Upload Bukti Pembayaran Baru (Opsional)</label>
                            <input type="file" name="bukti_bayar" class="form-control" accept="image/*"
                                style="border-radius:8px;">
                            <small class="text-muted d-block mt-1" style="font-size: 11px;">*Maksimal ukuran file
                                2MB.</small>
                            @error('bukti_bayar')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                </div>

                <hr class="mt-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('booking.index') }}" class="btn btn-light" style="border-radius:8px;">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius:8px;">Simpan
                        Perubahan</button>
                </div>

            </form>
        </div>

    </div>
@endsection
