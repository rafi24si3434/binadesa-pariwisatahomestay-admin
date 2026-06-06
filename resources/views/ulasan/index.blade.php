@extends('layouts.admin.app')
@section('title', 'Ulasan Wisata')

@push('styles')
<style>
    .fade-in {
        animation: fadeIn .4s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity:0; transform: translateY(10px);}
        to   { opacity:1; transform: translateY(0);}
    }

    .review-card {
        border-radius: 16px;
        padding: 20px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(0,0,0,.1);
        transition: .25s;
    }
    .review-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,.15);
    }

    .star {
        color: #ffca28;
        font-size: 18px;
    }
    .star.off {
        color: #dcdcdc;
    }

    .review-time {
        font-size: 12px;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top: 35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold text-blue">⭐ Ulasan Wisata</h3>
        <a href="{{ route('ulasan.create') }}" class="btn btn-primary px-4">
            + Tambah Ulasan
        </a>
    </div>

    {{-- FILTERS --}}
    <div class="card p-3 shadow-sm mb-4">

        <form method="GET" class="row g-2 align-items-center">

            {{-- SEARCH --}}
            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari komentar..."
                    value="{{ request('search') }}">
            </div>

            {{-- FILTER DESTINASI --}}
            <div class="col-md-3">
                <select name="destinasi_id" class="form-select">
                    <option value="all">Semua Destinasi</option>
                    @foreach($destinasi as $d)
                    <option value="{{ $d->destinasi_id }}"
                        {{ request('destinasi_id') == $d->destinasi_id ? 'selected' : '' }}>
                        {{ $d->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- FILTER RATING --}}
            <div class="col-md-3">
                <select name="rating" class="form-select">
                    <option value="all">Semua Rating</option>
                    @for($i=5; $i>=1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} ⭐
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-info w-100">Filter</button>
            </div>

        </form>

    </div>

    {{-- LIST ULASAN --}}
    <div class="row g-4">

        @forelse($ulasan as $u)
        <div class="col-lg-4 col-md-6 col-sm-12">

            <div class="review-card">

                {{-- DESTINASI --}}
                <h5 class="fw-bold text-dark mb-1">
                    🏝️ {{ $u->destinasi->nama ?? '-' }}
                </h5>

                {{-- WARGA --}}
                <p class="mb-1 text-muted">
                    👤 {{ $u->warga->nama ?? '-' }}
                </p>

                {{-- RATING --}}
                <p class="mb-1">
                    @for($i=1; $i<=5; $i++)
                        <span class="star {{ $i <= $u->rating ? '' : 'off' }}">★</span>
                    @endfor
                </p>

                {{-- KOMENTAR --}}
                <p class="mb-2 text-secondary">{{ $u->komentar ?: 'Tidak ada komentar.' }}</p>

                {{-- WAKTU --}}
                <span class="review-time">
                    🕒 {{ date('d M Y H:i', strtotime($u->waktu ?? $u->created_at)) }}
                </span>

                <hr>

                {{-- ACTION --}}
                <div class="d-flex justify-content-between mt-2">

                    <a href="{{ route('ulasan.edit', $u->ulasan_id) }}"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form action="{{ route('ulasan.destroy', $u->ulasan_id) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus ulasan ini?')">
                        @csrf @method('DELETE')

                        <button class="btn btn-danger btn-sm">
                            Hapus
                        </button>
                    </form>

                </div>

            </div>

        </div>
        @empty

        <div class="col-12 text-center py-5">
            <h5 class="text-muted">Belum ada ulasan wisata.</h5>
        </div>

        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $ulasan->links('pagination::bootstrap-4') }}
    </div>

</div>
@endsection
