@extends('layouts.admin.app')
@section('title', 'Tambah Ulasan Wisata')

@push('styles')
<style>
    .fade-in { animation: fadeIn .5s ease-in-out; }
    @keyframes fadeIn {
        from { opacity:0; transform:translateY(10px); }
        to   { opacity:1; transform:none; }
    }

    .star-select {
        font-size: 32px;
        cursor: pointer;
        color: #dcdcdc;
        transition: .2s;
    }

    .star-select.active {
        color: #ffca28;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top: 35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-blue">⭐ Tambah Ulasan Wisata</h3>

        <a href="{{ route('ulasan.index') }}" class="btn btn-secondary">
            ← Kembali
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="card shadow-sm p-4">

        <form action="{{ route('ulasan.store') }}" method="POST">
            @csrf

            <div class="row g-3">

                {{-- DESTINASI --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Destinasi Wisata</label>
                    <select name="destinasi_id" class="form-select" required>
                        <option value="">-- Pilih Destinasi --</option>
                        @foreach($destinasi as $d)
                            <option value="{{ $d->destinasi_id }}">
                                {{ $d->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- WARGA --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Warga (Pengulas)</label>
                    <select name="warga_id" class="form-select" required>
                        <option value="">-- Pilih Warga --</option>
                        @foreach($warga as $w)
                            <option value="{{ $w->warga_id }}">
                                {{ $w->nama }} — {{ $w->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- RATING --}}
                <div class="col-md-12 mt-3">
                    <label class="form-label fw-semibold">Rating</label>
                    <div>
                        <span class="star-select" data-value="1">★</span>
                        <span class="star-select" data-value="2">★</span>
                        <span class="star-select" data-value="3">★</span>
                        <span class="star-select" data-value="4">★</span>
                        <span class="star-select" data-value="5">★</span>
                    </div>
                    <input type="hidden" id="ratingInput" name="rating" required>
                    <small class="text-danger d-none" id="ratingError">Rating wajib dipilih.</small>
                </div>

                {{-- KOMENTAR --}}
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Komentar</label>
                    <textarea name="komentar" class="form-control" rows="3"
                              placeholder="Tuliskan pengalaman mengunjungi destinasi..."
                              required></textarea>
                </div>

            </div>

            <div class="d-flex justify-content-end mt-4 gap-2">
                <a href="{{ route('ulasan.index') }}" class="btn btn-secondary">
                    Batal
                </a>

                <button class="btn btn-primary px-4">
                    Simpan Ulasan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    let stars = document.querySelectorAll(".star-select");
    let ratingInput = document.getElementById("ratingInput");
    let ratingError = document.getElementById("ratingError");

    stars.forEach(star => {
        star.addEventListener("click", function () {

            let rating = this.dataset.value;
            ratingInput.value = rating;

            // reset
            stars.forEach(s => s.classList.remove("active"));

            // aktifkan sesuai rating
            for (let i = 0; i < rating; i++) {
                stars[i].classList.add("active");
            }

            ratingError.classList.add("d-none");
        });
    });

    // Prevent submit without rating
    document.querySelector("form").addEventListener("submit", function (e) {
        if (!ratingInput.value) {
            ratingError.classList.remove("d-none");
            e.preventDefault();
        }
    });

});
</script>
@endpush
