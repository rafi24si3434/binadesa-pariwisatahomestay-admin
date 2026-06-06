@extends('layouts.admin.app')
@section('title', 'Tambah Destinasi')

@push('styles')
<style>
    .fade-in { animation: fadeIn .4s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .img-preview {
        width: 100%;
        height: 220px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px dashed #9bb1ff;
        background: #f3f6ff;
    }
</style>
@endpush


@section('content')
<div class="container-fluid fade-in" style="padding-top: 35px;">

    <h3 class="fw-bold text-blue mb-3">🌄 Tambah Destinasi Wisata</h3>

    <div class="card shadow-sm p-4">
        <form action="{{ route('destinasi.store') }}" method="POST" enctype="multipart/form-data" id="form-create">
            @csrf

            <div class="row g-3">

                {{-- NAMA --}}
                <div class="col-md-6">
                    <label class="fw-semibold">Nama Destinasi</label>
                    <input type="text" name="nama" id="nama" class="form-control"
                           placeholder="Masukkan nama destinasi..." required>
                    <small class="text-danger d-none" id="nama-error">Nama destinasi sudah digunakan!</small>
                </div>

                {{-- FOTO PREVIEW --}}
                <div class="col-md-6 text-center">
                    <label class="fw-semibold">Foto Destinasi</label>

                    <img id="preview-img" class="img-preview mb-2" src="/no-image.png">

                    <input type="file" name="foto" id="foto" class="form-control"
                        accept="image/*" required onchange="previewImage()">
                </div>

                {{-- DESKRIPSI --}}
                <div class="col-12">
                    <label class="fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"
                              placeholder="Deskripsi singkat destinasi..." required></textarea>
                </div>

                {{-- ALAMAT --}}
                <div class="col-md-6">
                    <label class="fw-semibold">Alamat</label>
                    <input type="text" name="alamat" class="form-control"
                           placeholder="Alamat lengkap..." required>
                </div>

                {{-- RT --}}
                <div class="col-md-3">
                    <label class="fw-semibold">RT</label>
                    <select name="rt" class="form-select" required>
                        <option value="">Pilih RT</option>
                        @for ($i=1; $i<=20; $i++)
                            <option value="{{ $i }}">RT {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                {{-- RW --}}
                <div class="col-md-3">
                    <label class="fw-semibold">RW</label>
                    <select name="rw" class="form-select" required>
                        <option value="">Pilih RW</option>
                        @for ($i=1; $i<=20; $i++)
                            <option value="{{ $i }}">RW {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                {{-- JAM BUKA --}}
                <div class="col-md-4">
                    <label class="fw-semibold">Jam Buka</label>
                    <input type="text" name="jam_buka" class="form-control"
                           placeholder="08:00 - 17:00" required>
                </div>

                {{-- TIKET --}}
                <div class="col-md-4">
                    <label class="fw-semibold">Harga Tiket</label>
                    <input type="number" name="tiket" class="form-control"
                           placeholder="0 = Gratis" required>
                </div>

                {{-- KONTAK --}}
                <div class="col-md-4">
                    <label class="fw-semibold">Nomor Kontak</label>
                    <input type="text" name="kontak" class="form-control"
                           placeholder="0812xxxx" required>
                </div>

            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('destinasi.index') }}" class="btn btn-secondary px-4">Batal</a>
                <button class="btn btn-primary px-4" id="btn-submit">Simpan</button>
            </div>

        </form>
    </div>

</div>


{{-- ======================== --}}
{{-- AJAX VALIDATION + PREVIEW --}}
{{-- ======================== --}}
<script>
    // Preview Foto
    function previewImage() {
        const file = document.getElementById('foto').files[0];
        const preview = document.getElementById('preview-img');

        if (file) {
            preview.src = URL.createObjectURL(file);
        }
    }

    // AJAX CHECK DUPLIKAT NAMA
    document.getElementById('nama').addEventListener('keyup', function () {
        const nama = this.value;

        if (nama.length < 3) return;

        fetch("{{ route('destinasi.checkName') }}?nama=" + nama)
            .then(res => res.json())
            .then(data => {
                const error = document.getElementById('nama-error');
                const submitBtn = document.getElementById('btn-submit');

                if (data.exists) {
                    error.classList.remove('d-none');
                    submitBtn.disabled = true;
                } else {
                    error.classList.add('d-none');
                    submitBtn.disabled = false;
                }
            });
    });
</script>

@endsection
