@extends('layouts.admin.app')
@section('title', 'Tambah Kamar')

@push('styles')
<style>
    .fade-in { animation: fade .4s ease-in-out; }
    @keyframes fade { from{opacity:0;transform:translateY(10px);} to{opacity:1;transform:none;} }

    .preview-img {
        width: 110px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 8px;
        margin-bottom: 8px;
        border: 1px solid #ddd;
    }
</style>
@endpush


@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-blue m-0">🛏️ Tambah Kamar Homestay</h3>
        <a href="{{ route('kamar.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>


    {{-- FORM --}}
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('kamar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- KIRI --}}
                    <div class="col-md-7">

                        {{-- Nama Kamar --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Kamar</label>
                            <input type="text" name="nama_kamar" class="form-control"
                                   value="{{ old('nama_kamar') }}" required>
                            @error('nama_kamar') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Homestay --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Homestay</label>
                            <select name="homestay_id" class="form-select" required>
                                <option value="">-- Pilih Homestay --</option>
                                @foreach($homestay as $h)
                                    <option value="{{ $h->homestay_id }}"
                                        {{ old('homestay_id') == $h->homestay_id ? 'selected':'' }}>
                                        {{ $h->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('homestay_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Kapasitas --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kapasitas (orang)</label>
                            <input type="number" name="kapasitas" class="form-control"
                                   value="{{ old('kapasitas') }}" min="1" required>
                            @error('kapasitas') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Fasilitas --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Fasilitas</label>

                            <div class="row">
                                @foreach (['WiFi','AC','TV','Parkir','Kamar Mandi Dalam','Sarapan','Lemari','Air Panas'] as $fas)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   name="fasilitas[]"
                                                   value="{{ $fas }}"
                                                   id="fas-{{ $fas }}">
                                            <label class="form-check-label" for="fas-{{ $fas }}">
                                                {{ $fas }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga / Malam (Rp)</label>
                            <input type="number" name="harga" class="form-control"
                                   value="{{ old('harga') }}" min="0" step="1000" required>
                            @error('harga') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>

                    {{-- KANAN: FOTO --}}
                    <div class="col-md-5">

                        <label class="form-label fw-semibold">Upload Foto Kamar</label>
                        <p class="text-muted small">Anda dapat memilih banyak foto. Maks 2MB per foto.</p>

                        <input type="file" name="foto[]" id="fotoInput" class="form-control"
                               accept="image/*" multiple>

                        @error('foto.*')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        {{-- Preview --}}
                        <div id="preview-area" class="mt-3 d-flex flex-wrap"></div>

                    </div>

                </div>

                <hr>

                {{-- BUTTON --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('kamar.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection


@push('scripts')
<script>
document.getElementById('fotoInput')?.addEventListener('change', function(e) {
    const preview = document.getElementById('preview-area');
    preview.innerHTML = '';

    Array.from(e.target.files).forEach(file => {
        const reader = new FileReader();

        reader.onload = ev => {
            const img = document.createElement('img');
            img.src = ev.target.result;
            img.className = 'preview-img';
            preview.appendChild(img);
        };

        reader.readAsDataURL(file);
    });
});
</script>
@endpush
