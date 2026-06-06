@extends('layouts.admin.app')
@section('title', 'Booking Homestay')

@push('styles')
    <style>
        .fade-in {
            animation: fade .35s ease-in-out;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .booking-card {
            border-radius: 18px;
            background: #ffffff;
            border: 1px solid #eef0f4;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
            transition: .25s ease;
        }

        .booking-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(0, 0, 0, .12);
            border-color: #d5d8df;
        }

        .bukti-img {
            width: 75px;
            height: 75px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e6e6e6;
            transition: .2s;
        }

        .bukti-img:hover {
            transform: scale(1.05);
        }

        .badge-status {
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
        }

        .btn-action {
            border-radius: 10px !important;
            font-weight: 600;
            padding: 6px 12px !important;
            transition: .2s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .12);
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid fade-in" style="padding-top:35px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-0">📅 Booking Homestay</h3>
                <small class="text-muted">Kelola semua pemesanan dengan cepat dan mudah.</small>
            </div>

            <a href="{{ route('booking.create') }}" class="btn btn-primary px-4 shadow-sm" style="border-radius:12px;">
                + Booking Baru
            </a>
        </div>

        {{-- FILTER --}}
        <div class="card p-3 shadow-sm mb-4" style="border-radius:14px;">
            <form method="GET" class="row g-2 align-items-center">

                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" style="border-radius:12px;"
                        placeholder="🔍 Cari nama tamu, homestay, kamar..." value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select" style="border-radius:12px;">
                        <option value="">Semua Status</option>
                        {{-- PERUBAHAN: Value diubah menjadi huruf awal kapital --}}
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>💰 Lunas</option>
                        <option value="Batal" {{ request('status') == 'Batal' ? 'selected' : '' }}>❌ Batal</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="metode_bayar" class="form-select" style="border-radius:12px;">
                        <option value="">Semua Metode Bayar</option>
                        <option value="cash" {{ request('metode_bayar') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                        <option value="transfer" {{ request('metode_bayar') == 'transfer' ? 'selected' : '' }}>🏦 Transfer
                        </option>
                        <option value="qris" {{ request('metode_bayar') == 'qris' ? 'selected' : '' }}>📱 QRIS</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-info w-100 text-white" style="border-radius:12px;">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- LIST BOOKING --}}
        <div class="row g-4">
            @forelse($booking as $b)
                <div class="col-12">
                    <div class="booking-card card border-0 shadow-sm" style="border-radius: 16px;">
                        {{-- Gunakan flex-column di HP, flex-row di layar menengah (md) ke atas --}}
                        <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center gap-4">

                            {{-- 1. BUKTI BAYAR --}}
                            <div class="flex-shrink-0 text-center">
                                @if ($b->media)
                                    <img src="{{ asset('storage/' . $b->media->file_url) }}" alt="Bukti Bayar"
                                        class="rounded-3 shadow-sm"
                                        style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #f8f9fa;">
                                @else
                                    <div class="rounded-3 d-flex flex-column align-items-center justify-content-center bg-light text-muted"
                                        style="width: 100px; height: 100px; border: 2px dashed #dee2e6;">
                                        <span class="fs-4">📄</span>
                                        <span style="font-size: 11px; font-weight: 500;">Belum Ada</span>
                                    </div>
                                @endif
                            </div>

                            {{-- 2. INFORMASI UTAMA --}}
                            <div class="flex-grow-1">

                                {{-- Nama & Status --}}
                                <div class="d-flex flex-wrap justify-content-between align-items-start mb-2 gap-2">
                                    <div>
                                        <h5 class="mb-0 fw-bold text-dark">{{ $b->warga->nama ?? 'Nama Tidak Diketahui' }}
                                        </h5>
                                        <div class="text-secondary small mt-1">
                                            <i class="bi bi-person-badge"></i> NIK: {{ $b->warga->no_ktp ?? '-' }}
                                        </div>
                                    </div>

                                    {{-- MAPPING STATUS: Menyesuaikan DB dengan Tampilan --}}
                                    @php
                                        $dbStatus = strtolower($b->status);
                                        $statusConfig = [
                                            'pending' => [
                                                'label' => 'Pending',
                                                'class' => 'bg-warning text-dark',
                                                'icon' => '⏳',
                                            ],
                                            'dibayar' => [
                                                'label' => 'Lunas',
                                                'class' => 'bg-success text-white',
                                                'icon' => '✅',
                                            ],
                                            'selesai' => [
                                                'label' => 'Selesai',
                                                'class' => 'bg-primary text-white',
                                                'icon' => '🏁',
                                            ],
                                            'batal' => [
                                                'label' => 'Batal',
                                                'class' => 'bg-danger text-white',
                                                'icon' => '❌',
                                            ],
                                        ];
                                        $currentStatus = $statusConfig[$dbStatus] ?? [
                                            'label' => ucfirst($dbStatus),
                                            'class' => 'bg-secondary text-white',
                                            'icon' => '📌',
                                        ];
                                    @endphp

                                    <span class="badge {{ $currentStatus['class'] }} px-3 py-2 rounded-pill shadow-sm"
                                        style="font-size: 0.85rem;">
                                        {{ $currentStatus['icon'] }} {{ $currentStatus['label'] }}
                                    </span>
                                </div>

                                <hr class="text-muted opacity-25 my-3">

                                {{-- Homestay & Kamar --}}
                                <div class="d-flex flex-wrap gap-4 mb-3">
                                    <div>
                                        <div class="text-muted small fw-semibold text-uppercase"
                                            style="font-size: 10px; letter-spacing: 0.5px;">Properti</div>
                                        <div class="text-dark fw-medium">
                                            🏠 {{ $b->kamar->homestay->nama ?? '-' }}
                                            <span class="text-muted ms-1">({{ $b->kamar->nama_kamar ?? '-' }})</span>
                                        </div>
                                    </div>

                                    {{-- Tanggal --}}
                                    <div>
                                        <div class="text-muted small fw-semibold text-uppercase"
                                            style="font-size: 10px; letter-spacing: 0.5px;">Jadwal Menginap</div>
                                        <div class="text-dark fw-medium">
                                            📅 {{ \Carbon\Carbon::parse($b->checkin)->format('d M Y') }}
                                            <i class="bi bi-arrow-right text-muted mx-1"></i>
                                            {{ \Carbon\Carbon::parse($b->checkout)->format('d M Y') }}

                                            @php
                                                $days = \Carbon\Carbon::parse($b->checkin)->diffInDays(
                                                    \Carbon\Carbon::parse($b->checkout),
                                                );
                                            @endphp
                                            <span class="badge bg-light text-dark border ms-2">{{ $days }}
                                                Malam</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Metode & Total Harga --}}
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                    <div class="small text-secondary">
                                        Metode Pembayaran: <strong
                                            class="text-dark">{{ strtoupper($b->metode_bayar) }}</strong>
                                    </div>
                                    <div class="fs-5 fw-bold text-primary">
                                        Rp {{ number_format($b->total, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            {{-- 3. TOMBOL AKSI --}}
                            <div class="d-flex flex-md-column gap-2 mt-3 mt-md-0 justify-content-end"
                                style="min-width: 140px;">

                                {{-- Form Toggle Status --}}
                                <form action="{{ route('booking.toggleLunas', $b->booking_id) }}" method="POST"
                                    class="w-100">
                                    @csrf
                                    @method('PATCH')
                                    @if ($dbStatus === 'dibayar' || $dbStatus === 'selesai')
                                        <button type="submit"
                                            class="btn btn-outline-secondary btn-sm w-100 fw-medium rounded-3">
                                            ↩ Batal Lunas
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="btn btn-success btn-sm w-100 fw-medium rounded-3 shadow-sm">
                                            💰 Set Lunas
                                        </button>
                                    @endif
                                </form>

                                <a href="{{ route('booking.edit', $b->booking_id) }}"
                                    class="btn btn-warning btn-sm w-100 fw-medium rounded-3 text-dark shadow-sm">
                                    ✏️ Edit Data
                                </a>

                                <form action="{{ route('booking.destroy', $b->booking_id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data booking ini?');"
                                    class="w-100">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-medium rounded-3">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <div class="p-5 bg-white shadow-sm rounded-4" style="border: 1px dashed #ccc;">
                        <div class="fs-1 mb-3">📭</div>
                        <h4 class="text-dark fw-bold">Belum Ada Transaksi</h4>
                        <p class="text-muted">Data booking homestay saat ini masih kosong.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $booking->links('pagination::bootstrap-4') }}
        </div>

    </div>
@endsection
