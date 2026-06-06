@extends('layouts.admin.app')
@section('title', 'Tambah Homestay')

@push('styles')
<style>
    .fade-in { animation: fade .4s ease-in-out; }
    @keyframes fade {
        from {opacity:0; transform: translateY(10px);}
        to {opacity:1; transform:none;}
    }

    .img-preview {
        width: 140px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        margin-right: 8px;
        margin-bottom: 8px;
        border: 1px solid #ddd;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold text-blue">🏘️ Tambah Homestay</h3>
        <a href="{{ route('homestay.index') }}" class="btn btn-secondary px-4">Kembali</a>
    </div>

    <form action="{{ route('homestay.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf

        {{-- NAMA --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Nama Homestay</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
            <small id="nama-alert" class="text-danger d-none">Nama sudah digunakan!</small>
        </div>

        {{-- PEMILIK --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Pemilik</label>
            <select name="pemilik_warga_id" class="form-select" required>
                <option value="">-- Pilih Pemilik --</option>
                @foreach($warga as $w)
                    <option value="{{ $w->warga_id }}">{{ $w->nama }} ({{ $w->no_ktp }})</option>
                @endforeach
            </select>
        </div>

        {{-- ALAMAT --}}
        <div class="row mb-3">
            <div class="col-md-8">
                <label class="form-label fw-bold">Alamat Lengkap</label>
                <input type="text" name="alamat" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-bold">RT</label>
                <select name="rt" class="form-select" required>
                    @for($i=1;$i<=20;$i++)
                        <option value="{{ $i }}">RT {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-bold">RW</label>
                <select name="rw" class="form-select" required>
                    @for($i=1;$i<=20;$i++)
                        <option value="{{ $i }}">RW {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        {{-- FASILITAS --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Fasilitas</label>
            <div class="row">
                @php
                    $fac = ['Wifi','Parkir','Sarapan','AC','TV','Kamar Mandi Dalam','Dapur','Kolam Renang','Panorama Alam'];
                @endphp

                @foreach($fac as $f)
                <div class="col-md-3">
                    <label>
                        <input type="checkbox" name="fasilitas[]" value="{{ $f }}"> {{ $f }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- HARGA --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Harga per Malam (Rp)</label>
                <input type="number" name="harga_per_malam" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="tersedia">Tersedia</option>
                    <option value="penuh">Penuh</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
        </div>

        {{-- FOTO UPLOAD --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Foto Homestay (Multiple)</label>
            <input type="file" name="foto[]" class="form-control" id="foto" multiple accept="image/*">

            <div id="preview-area" class="mt-2 d-flex flex-wrap"></div>
        </div>

        <button class="btn btn-success px-4">Simpan</button>

    </form>
</div>
@endsection


@push('scripts')
<script>
// =====================
// PREVIEW MULTI FOTO
// =====================
document.getElementById('foto').addEventListener('change', function(e) {
    const preview = document.getElementById('preview-area');
    preview.innerHTML = "";

    [...e.target.files].forEach(file => {
        let reader = new FileReader();
        reader.onload = event => {
            let img = document.createElement('img');
            img.src = event.target.result;
            img.classList.add('img-preview');
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});


// =====================
// AJAX CHECK NAMA
// =====================
document.getElementById('nama').addEventListener('keyup', function () {
    let nama = this.value;

    if (nama.length < 3) return;

    fetch("{{ route('homestay.checkName') }}?nama=" + nama)
        .then(res => res.json())
        .then(data => {
            let alert = document.getElementById('nama-alert');
            if (data.exists) {
                alert.classList.remove('d-none');
            } else {
                alert.classList.add('d-none');
            }
        });
});
</script>
@endpush
