@extends('layouts.admin.app')
@section('title', 'Homestay')

@push('styles')
<style>
    .fade-in { animation: fade .4s ease-in-out; }
    @keyframes fade { from{opacity:0;transform:translateY(10px);} to{opacity:1;transform:none;} }

    .home-card {
        border-radius: 18px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 6px 18px rgba(0,0,0,.1);
        transition: .3s;
        position: relative;
    }
    .home-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 28px rgba(0,0,0,.15);
    }

    .slide-box {
        height: 200px;
        width: 100%;
        overflow: hidden;
        position: relative;
        background: #ddd;
    }

    .slide-img {
        height: 100%;
        width: 100%;
        position: absolute;
        top:0; left:100%;
        object-fit: cover;
        transition: left .5s ease-in-out;
    }
    .slide-img.active { left: 0; }

    .nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.4);
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: .2s;
        z-index: 5;
    }
    .nav-btn:hover { background: rgba(0,0,0,0.6); }
    .left-nav { left: 10px; }
    .right-nav { right: 10px; }

    /* PLACEHOLDER TANPA FOTO */
    .no-photo-box {
        width: 100%;
        height: 200px;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .no-photo-icon {
        width: 55px;
        height: 55px;
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

    .facility-badge {
        background: #eef3ff;
        padding: 3px 8px;
        border-radius: 6px;
        margin-right: 4px;
        font-size: 11px;
        color: #4361ee;
        display: inline-block;
    }
</style>
@endpush


@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold text-blue">🏠 Homestay</h3>
        <a href="{{ route('homestay.create') }}" class="btn btn-primary px-4">+ Tambah Homestay</a>
    </div>

    {{-- FILTER --}}
    <div class="card p-3 shadow-sm mb-4">
        <form method="GET" class="row g-2 align-items-center">

            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                    placeholder="Cari nama homestay, alamat..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-3">
                <select name="pemilik" class="form-select">
                    <option value="all">Semua Pemilik</option>
                    @foreach($pemilik as $p)
                        <option value="{{ $p->warga_id }}" {{ request('pemilik') == $p->warga_id ? 'selected':'' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <input type="number" name="harga_min" class="form-control" placeholder="Harga Min"
                    value="{{ request('harga_min') }}">
            </div>

            <div class="col-md-2">
                <input type="number" name="harga_max" class="form-control" placeholder="Harga Max"
                    value="{{ request('harga_max') }}">
            </div>

            <div class="col-md-1">
                <button class="btn btn-info w-100">Cari</button>
            </div>

        </form>
    </div>


    {{-- LIST HOMESTAY --}}
    <div class="row g-4">

        @forelse($homestay as $hs)
        <div class="col-md-6 col-lg-4">
            <div class="home-card">

                {{-- SLIDER FOTO --}}
                <div class="slide-box" data-id="{{ $hs->homestay_id }}">

                    <span class="nav-btn left-nav" onclick="prevSlide({{ $hs->homestay_id }})">‹</span>
                    <span class="nav-btn right-nav" onclick="nextSlide({{ $hs->homestay_id }})">›</span>

                    @if($hs->media->count() > 0)
                        @foreach($hs->media as $img)
                            <img src="{{ asset('storage/'.$img->file_url) }}" class="slide-img d-none">
                        @endforeach
                    @else
                        {{-- PLACEHOLDER --}}
                        <div class="no-photo-box">
                            <div class="no-photo-icon">!</div>
                            <span class="no-photo-text">Belum ada foto</span>
                        </div>
                    @endif
                </div>


                <div class="p-3">

                    {{-- Nama --}}
                    <h5 class="fw-bold text-dark mb-1">{{ $hs->nama }}</h5>

                    {{-- Pemilik --}}
                    <p class="text-muted small mb-2">
                        👤 Pemilik: {{ $hs->pemilik->nama ?? '-' }}
                    </p>

                    {{-- Alamat --}}
                    <p class="mb-1">
                        📌 {{ $hs->alamat }} (RT {{ $hs->rt }}, RW {{ $hs->rw }})
                    </p>

                    {{-- Fasilitas --}}
                    <div class="mb-2">
                        @php $fac = json_decode($hs->fasilitas_json, true) ?? []; @endphp
                        @foreach(array_slice($fac, 0, 4) as $f)
                            <span class="facility-badge">{{ $f }}</span>
                        @endforeach
                        @if(count($fac) > 4)
                            <span class="facility-badge">+{{ count($fac)-4 }} lainnya</span>
                        @endif
                    </div>

                    {{-- Harga --}}
                    <h5 class="fw-bold text-primary mb-1">
                        Rp {{ number_format($hs->harga_per_malam,0,',','.') }} / malam
                    </h5>

                    {{-- Status --}}
                    <span class="badge
                        {{ $hs->status == 'tersedia' ? 'bg-success' :
                           ($hs->status == 'penuh' ? 'bg-danger' : 'bg-warning') }}">
                        {{ ucfirst($hs->status) }}
                    </span>

                    <hr>

                    {{-- ACTION --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('homestay.edit', $hs->homestay_id) }}"
                           class="btn btn-warning btn-sm px-3">Edit</a>

                        <form action="{{ route('homestay.destroy', $hs->homestay_id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus homestay ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm px-3">Hapus</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        @empty
            <div class="col-12 text-center py-5">
                <h5 class="text-muted">Belum ada homestay.</h5>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $homestay->links('pagination::bootstrap-4') }}
    </div>

</div>
@endsection


@push('scripts')
<script>
let sliders = {};

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".slide-box").forEach(box => {

        let imgs = box.querySelectorAll(".slide-img");
        let id = box.dataset.id;

        // Jika tidak ada gambar → skip slider
        if (imgs.length === 0) return;

        let index = 0;

        imgs[index].classList.remove("d-none");
        imgs[index].classList.add("active");

        sliders[id] = { index, imgs };

        setInterval(() => nextSlide(id), 6000);
    });
});

function nextSlide(id) {
    let s = sliders[id];
    if (!s) return;

    s.imgs[s.index].classList.remove("active");

    s.index = (s.index + 1) % s.imgs.length;

    s.imgs.forEach(img => img.classList.add("d-none"));
    s.imgs[s.index].classList.remove("d-none");

    setTimeout(() => s.imgs[s.index].classList.add("active"), 20);
}

function prevSlide(id) {
    let s = sliders[id];
    if (!s) return;

    s.imgs[s.index].classList.remove("active");

    s.index = (s.index - 1 + s.imgs.length) % s.imgs.length;

    s.imgs.forEach(img => img.classList.add("d-none"));
    s.imgs[s.index].classList.remove("d-none");

    setTimeout(() => s.imgs[s.index].classList.add("active"), 20);
}
</script>
@endpush
