@extends('layouts.admin.app')
@section('title', 'Edit Ulasan Wisata')

@push('styles')
<style>
    .fade-in { animation: fadeIn .4s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .star-select {
        font-size: 32px;
        cursor: pointer;
        color: #dcdcdc;
        transition: .25s;
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
        <h3 class="fw-bold text-blue">✏️ Edit Ulasan Wisata</h3>

        <a href="{{ route('ulasan.index') }}" class="btn btn-secondary">
            ← Kembali
        </a>
    </div>

    {{-- FORM --}}
    <div class="card shadow-sm p-4">

        <form action="{{ route('ulasan.update', $ulasan->ulasan_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- DESTINASI --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Destinasi Wisata</label>
                    <select name="destinasi_id" class="form-select" required>
                        @foreach($destinasi as $d)
                            <option value="{{ $d->destinasi_id }}"
                                {{ $ulasan->destinasi_id == $d->destinasi_id ? 'selected' : '' }}>
                                {{ $d->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- WARGA --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Warga Pengulas</label>
                    <select name="warga_id" class="form-select" required>
                        @foreach($warga as $w)
                            <option value="{{ $w->warga_id }}"
                                {{ $ulasan->warga_id == $w->warga_id ? 'selected' : '' }}>
                                {{ $w->nama }} — {{ $w->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- RATING --}}
                <div class="col-md-12 mt-2">
                    <label class="form-label fw-semibold">Rating</label>

                    <div>
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star-select {{ $ulasan->rating >= $i ? 'active' : '' }}"
                                  data-value="{{ $i }}">★</span>
                        @endfor
                    </div>

                    <input type="hidden" id="ratingInput" name="rating" value="{{ $ulasan->rating }}" required>
                    <small id="ratingError" class="text-danger d-none">Rating wajib diisi.</small>
                </div>

                {{-- KOMENTAR --}}
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Komentar</label>
                    <textarea name="komentar" class="form-control" rows="3" required>{{ $ulasan->komentar }}</textarea>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('ulasan.index') }}" class="btn btn-secondary">
                    Batal
                </a>

                <button class="btn btn-primary px-4">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

    let stars = document.querySelectorAll(".star-select");
    let ratingInput = document.getElementById("ratingInput");
    let ratingError = document.getElementById("ratingError");

    stars.forEach(star => {
        star.addEventListener("click", function () {

            let rating = this.dataset.value;
            ratingInput.value = rating;

            // Reset all stars
            stars.forEach(s => s.classList.remove("active"));

            // Activate selected stars
            for (let i = 0; i < rating; i++) {
                stars[i].classList.add("active");
            }

            ratingError.classList.add("d-none");
        });
    });

    // Prevent saving without rating
    document.querySelector("form").addEventListener("submit", function (e) {
        if (!ratingInput.value || ratingInput.value < 1) {
            ratingError.classList.remove("d-none");
            e.preventDefault();
        }
    });

});
</script>
@endpush
