@extends('layouts.admin.app')
@section('title', 'Destinasi Wisata')

@push('styles')
    <style>
        .fade-in {
            animation: fadeIn .5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dest-card {
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            transition: .25s;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
        }

        .dest-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
        }

        /* SLIDER FOTO */
        .slide-box {
            position: relative;
            width: 100%;
            height: 170px;
            overflow: hidden;
            border-radius: 14px 14px 0 0;
            background: #ddd;
        }

        .slide-img {
            position: absolute;
            top: 0;
            left: 100%;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: left .7s ease-in-out;
        }

        .slide-img.active {
            left: 0;
        }

        /* PLACEHOLDER TANPA FOTO */
        .no-photo-box {
            width: 100%;
            height: 170px;
            border-radius: 14px 14px 0 0;
            background: #e5e7eb;
            /* abu-abu */
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .no-photo-icon {
            width: 52px;
            height: 52px;
            border-radius: 999px;
            border: 2px dashed #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 22px;
            color: #9ca3af;
            margin-bottom: 4px;
        }

        .no-photo-text {
            font-size: 11px;
            color: #9ca3af;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid fade-in" style="padding-top: 35px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-4">
            <h3 class="fw-bold text-blue">🏝️ Destinasi Wisata</h3>

            <a href="{{ route('destinasi.create') }}" class="btn btn-primary px-4">
                + Tambah Destinasi
            </a>
        </div>

        {{-- FILTER / SEARCH --}}
        <div class="card p-3 mb-4 shadow-sm">

            <form method="GET" class="row g-2 align-items-center">

                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari destinasi, alamat, kontak..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <select name="rt" class="form-select">
                        <option value="all">Semua RT</option>
                        @for ($i = 1; $i <= 20; $i++)
                            <option value="{{ $i }}" {{ request('rt') == $i ? 'selected' : '' }}>
                                RT {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="rw" class="form-select">
                        <option value="all">Semua RW</option>
                        @for ($i = 1; $i <= 20; $i++)
                            <option value="{{ $i }}" {{ request('rw') == $i ? 'selected' : '' }}>
                                RW {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="number" name="tiket_min" class="form-control" placeholder="Tiket Min"
                        value="{{ request('tiket_min') }}">
                </div>

                <div class="col-md-2">
                    <input type="number" name="tiket_max" class="form-control" placeholder="Tiket Max"
                        value="{{ request('tiket_max') }}">
                </div>

                <div class="col-md-2 mt-2">
                    <button class="btn btn-info w-100">Filter</button>
                </div>

            </form>

        </div>

        {{-- GRID CARD --}}
        <div class="row g-4">

            @forelse ($destinasi as $d)

                <div class="col-md-4 col-lg-3">
                    <div class="card dest-card">

                        {{-- FOTO SLIDESHOW / PLACEHOLDER --}}
                        @php $images = $d->media; @endphp

                        @if ($images && $images->count() > 0)
                            <div class="slide-box" id="slide-{{ $d->destinasi_id }}">
                                @foreach ($images as $idx => $img)
                                    <img src="{{ asset('storage/' . $img->file_url) }}"
                                        class="slide-img {{ $idx === 0 ? 'active' : '' }}">
                                @endforeach
                            </div>
                        @else
                            {{-- GREY BOX + TANDA SERU --}}
                            <div class="no-photo-box">
                                <div class="no-photo-icon">!</div>
                                <span class="no-photo-text">Belum ada foto</span>
                            </div>
                        @endif

                        <div class="card-body">

                            <h5 class="fw-bold">{{ $d->nama }}</h5>

                            <p class="mb-1"><strong>Alamat:</strong> {{ $d->alamat }}</p>

                            <p class="mb-1 small text-muted">
                                RT {{ $d->rt ?? '-' }} | RW {{ $d->rw ?? '-' }}
                            </p>

                            <p class="mb-1">
                                <strong>Tiket:</strong> Rp {{ number_format($d->tiket, 0, ',', '.') }}
                            </p>

                            <p class="text-muted mb-2">
                                📞 {{ $d->kontak ?? 'Tidak ada' }}
                            </p>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('destinasi.edit', $d->destinasi_id) }}"
                                    class="btn btn-warning btn-sm px-3">
                                    Edit
                                </a>

                                <form action="{{ route('destinasi.destroy', $d->destinasi_id) }}" method="POST"
                                    onsubmit="return confirm('Hapus destinasi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm px-3">Hapus</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">Belum ada destinasi wisata.</h5>
                </div>
            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $destinasi->links('pagination::bootstrap-4') }}
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".slide-box").forEach(box => {
                let imgs = box.querySelectorAll(".slide-img");
                if (imgs.length <= 1) return; // slideshow hanya jika foto lebih dari 1

                let index = 0;

                setInterval(() => {
                    imgs[index].classList.remove("active");
                    index = (index + 1) % imgs.length;
                    imgs[index].classList.add("active");
                }, 10000); // 10 detik
            });
        });
    </script>
@endpush
