@extends('layouts.admin.app')
@section('title', 'Data Warga')

@push('styles')
    <style>
        .fade-in {
            animation: fadein .4s ease-in-out;
        }

        @keyframes fadein {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .warga-card {
            border-radius: 14px;
            transition: .25s;
            border: none;
            background: #ffffff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
        }

        .warga-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, .15);
        }

        .badge-female {
            background: #ff4f81;
        }

        .badge-male {
            background: #1e88e5;
        }

        .filter-label {
            font-weight: 600;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid fade-in" style="padding-top: 35px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between mb-4">
            <h3 class="fw-bold text-blue">👥 Data Warga</h3>
            <a href="{{ route('warga.create') }}" class="btn btn-primary px-4">
                + Tambah Warga
            </a>
        </div>

        {{-- FILTER / SEARCH --}}
        <div class="card p-3 mb-3 shadow-sm">
            <form method="GET" class="row g-2">

                {{-- Search box --}}
                <div class="col-md-4">
                    <label class="filter-label">🔍 Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, KTP, email..."
                        value="{{ request('search') }}">
                </div>

                {{-- Gender --}}
                <div class="col-md-2">
                    <label class="filter-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="all">Semua</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                {{-- Agama --}}
                <div class="col-md-2">
                    <label class="filter-label">Agama</label>
                    <select name="agama" class="form-select">
                        <option value="all">Semua</option>
                        @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $a)
                            <option value="{{ $a }}" {{ request('agama') == $a ? 'selected' : '' }}>{{ $a }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sorting --}}
                <div class="col-md-2">
                    <label class="filter-label">Urutkan</label>
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                        <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="no_ktp" {{ request('sort_by') == 'no_ktp' ? 'selected' : '' }}>No KTP</option>
                    </select>
                </div>

                {{-- Button --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-info w-100">Terapkan</button>
                </div>

            </form>
        </div>

        {{-- GRID CARD --}}
        <div class="row g-3">

            @forelse ($warga as $w)
                <div class="col-md-4 col-lg-3">
                    <div class="card warga-card p-3">

                        {{-- HEADER --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">{{ $w->nama }}</h5>

                            <span class="badge {{ $w->jenis_kelamin == 'L' ? 'badge-male' : 'badge-female' }}">
                                {{ $w->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </div>

                        <hr>

                        {{-- DETAIL --}}
                        <p class="mb-1"><strong>No KTP:</strong> {{ $w->no_ktp }}</p>
                        <p class="mb-1"><strong>Agama:</strong> {{ $w->agama ?? '-' }}</p>
                        <p class="mb-1"><strong>Pekerjaan:</strong> {{ $w->pekerjaan ?? '-' }}</p>

                        @if ($w->email)
                            <p class="mb-1"><strong>Email:</strong> {{ $w->email }}</p>
                        @endif

                        @if ($w->telp)
                            <p class="mb-1"><strong>Telp:</strong> {{ $w->telp }}</p>
                        @endif

                        {{-- FOOTER --}}
                        <div class="d-flex justify-content-between mt-3">

                            <a href="{{ route('warga.edit', $w->warga_id) }}" class="btn btn-warning btn-sm px-3">
                                ✏ Edit
                            </a>

                            <form action="{{ route('warga.destroy', $w->warga_id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus warga?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm px-3">
                                    🗑 Hapus
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
            @empty

                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076500.png" width="90" class="mb-3">
                    <h5 class="text-muted">Belum ada data warga.</h5>
                </div>
            @endforelse

        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $warga->links('pagination::bootstrap-4') }}
        </div>

    </div>
@endsection
