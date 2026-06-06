@extends('layouts.admin.app')
@section('title', 'Kamar Homestay')

@push('styles')
<style>
    .fade-in { animation: fade .4s ease-in-out; }
    @keyframes fade { from{opacity:0;transform:translateY(10px);} to{opacity:1;transform:none;} }

    .kamar-card {
        width: 260px;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 6px 18px rgba(0,0,0,.1);
        transition: .3s;
        display: inline-block;
        margin-right: 15px;
        vertical-align: top;
    }
    .kamar-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 28px rgba(0,0,0,.15);
    }

    .slide-box {
        height: 160px;
        width: 100%;
        overflow: hidden;
        position: relative;
        background: #ddd;
    }

    .slide-img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        position: absolute;
        top:0; left:100%;
        transition: left .5s ease-in-out;
    }
    .slide-img.active { left: 0; }

    /* SCROLL */
    .scroll-row {
        white-space: nowrap;
        overflow-x: auto;
        padding-bottom: 10px;
    }
    .scroll-row::-webkit-scrollbar {
        height: 7px;
    }
    .scroll-row::-webkit-scrollbar-thumb {
        background: #c2c2c2;
        border-radius: 5px;
    }

    /* PLACEHOLDER */
    .no-photo-box {
        width: 100%;
        height: 160px;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    .no-photo-icon {
        width: 48px;
        height: 48px;
        border-radius: 999px;
        border: 2px dashed #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 20px;
        color: #9ca3af;
        margin-bottom: 4px;
    }
    .no-photo-text {
        font-size: 11px;
        color: #9ca3af;
    }

    .facility-badge {
        background: #e8f0ff;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        color: #4361ee;
        margin-right: 4px;
        display: inline-block;
    }
</style>
@endpush


@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold text-blue">🛏️ Kamar Homestay</h3>
        <a href="{{ route('kamar.create') }}" class="btn btn-primary px-4">+ Tambah Kamar</a>
    </div>

    {{-- GROUP BY HOMESTAY --}}
    @forelse($homestay as $h)

        <div class="mb-4">

            {{-- TITLE --}}
            <h4 class="fw-bold text-dark mb-1">🏠 {{ $h->nama }}</h4>
            <p class="text-muted mb-2">{{ $h->alamat }} (RT {{ $h->rt }}, RW {{ $h->rw }})</p>

            @php
                $kamarList = $kamarGroup->where('homestay_id', $h->homestay_id);
            @endphp

            @if ($kamarList->count() == 0)
                <p class="text-muted fst-italic">Tidak ada kamar untuk homestay ini.</p>
            @else

                <div class="scroll-row">

                    @foreach ($kamarList as $km)

                        <div class="kamar-card">

                            {{-- FOTO/SLIDER --}}
                            <div class="slide-box" data-id="{{ $km->kamar_id }}">

                                @if ($km->media->count() > 0)

                                    @foreach ($km->media as $img)
                                        <img src="{{ asset('storage/'.$img->file_url) }}"
                                             class="slide-img d-none">
                                    @endforeach

                                @else
                                    {{-- PLACEHOLDER --}}
                                    <div class="no-photo-box">
                                        <div class="no-photo-icon">!</div>
                                        <div class="no-photo-text">Belum ada foto</div>
                                    </div>
                                @endif

                            </div>

                            <div class="p-3">

                                <h6 class="fw-bold text-dark mb-1">{{ $km->nama_kamar }}</h6>

                                <p class="small mb-1">👥 Kapasitas: {{ $km->kapasitas }} orang</p>

                                {{-- Fasilitas --}}
                                @php $fas = $km->fasilitas; @endphp
                                <div class="mb-2" style="height:32px; overflow:hidden;">
                                    @foreach (array_slice($fas, 0, 3) as $f)
                                        <span class="facility-badge">{{ $f }}</span>
                                    @endforeach
                                    @if (count($fas) > 3)
                                        <span class="facility-badge">+{{ count($fas) - 3 }}</span>
                                    @endif
                                </div>

                                {{-- Harga --}}
                                <h6 class="fw-bold text-primary mb-2">
                                    Rp {{ number_format($km->harga,0,',','.') }}
                                </h6>

                                {{-- ACTION --}}
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('kamar.edit', $km->kamar_id) }}"
                                       class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('kamar.destroy', $km->kamar_id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus kamar ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

            <hr>

        </div>

    @empty

        <div class="text-center py-5">
            <h5 class="text-muted">Belum ada Homestay.</h5>
        </div>

    @endforelse


    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $paginate->links('pagination::bootstrap-4') }}
    </div>

</div>
@endsection



@push('scripts')
<script>
let sliders = {};

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".slide-box").forEach(box => {

        let id   = box.dataset.id;
        let imgs = box.querySelectorAll(".slide-img");

        if (imgs.length === 0) return;

        let index = 0;

        imgs[index].classList.remove("d-none");
        imgs[index].classList.add("active");

        sliders[id] = { index, imgs };

        setInterval(() => nextSlide(id), 5000);
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
</script>
@endpush
