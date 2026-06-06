@extends('layouts.admin.app')
@section('title', 'Edit Kamar')

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

    .old-img-box {
        position: relative;
        display: inline-block;
        margin: 4px;
    }
    .old-img-box img {
        width: 110px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #ddd;
    }
    .btn-del {
        position: absolute;
        top: -6px;
        right: -6px;
        padding: 2px 7px;
        border-radius: 50%;
        font-size: 11px;
    }
</style>
@endpush


@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-blue">✏️ Edit Kamar</h3>
        <a href="{{ route('kamar.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    {{-- FORM --}}
    <div class="card shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('kamar.update', $kamar->kamar_id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- KIRI --}}
                    <div class="col-md-7">

                        {{-- Nama Kamar --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Kamar</label>
                            <input type="text" name="nama_kamar" class="form-control"
                                   value="{{ old('nama_kamar', $kamar->nama_kamar) }}" required>
                            @error('nama_kamar') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Homestay --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Homestay</label>
                            <select name="homestay_id" class="form-select" required>
                                <option value="">-- Pilih Homestay --</option>
                                @foreach($homestay as $h)
                                    <option value="{{ $h->homestay_id }}"
                                        {{ old('homestay_id', $kamar->homestay_id) == $h->homestay_id ? 'selected':'' }}>
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
                                   value="{{ old('kapasitas', $kamar->kapasitas) }}" required>
                            @error('kapasitas') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Fasilitas --}}
                        @php
                            $fasSelected = json_decode($kamar->fasilitas_json ?? '[]', true);
                        @endphp

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Fasilitas</label>

                            <div class="row">
                                @foreach(['WiFi','AC','TV','Parkir','Kamar Mandi Dalam','Sarapan','Lemari','Air Panas'] as $fas)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   name="fasilitas[]"
                                                   value="{{ $fas }}"
                                                   id="fas-{{ $fas }}"
                                                   {{ in_array($fas, $fasSelected) ? 'checked' : '' }}>
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
                            <label class="form-label fw-semibold">Harga per Malam (Rp)</label>
                            <input type="number" name="harga" class="form-control"
                                   value="{{ old('harga', $kamar->harga) }}" min="0" step="1000" required>
                            @error('harga') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>

                    {{-- KANAN (FOTO) --}}
                    <div class="col-md-5">

                        <h5 class="fw-semibold mb-2">Foto Kamar</h5>
                        <p class="text-muted small">Anda boleh upload banyak foto. Max 2MB per foto.</p>

                        {{-- FOTO LAMA --}}
                        <label class="form-label fw-semibold">Foto Lama</label>
                        <div class="mb-3" id="oldImages">
                            @forelse($kamar->media as $m)
                                <div class="old-img-box" data-id="{{ $m->media_id }}">
                                    <img src="{{ asset('storage/'.$m->file_url) }}" alt="foto">

                                    <button type="button" class="btn btn-danger btn-sm btn-del"
                                            onclick="deleteMedia({{ $m->media_id }})">
                                        ✕
                                    </button>
                                </div>
                            @empty
                                <p class="text-muted">Belum ada foto.</p>
                            @endforelse
                        </div>

                        {{-- FOTO BARU --}}
                        <label class="form-label fw-semibold">Tambah Foto Baru</label>
                        <input type="file" name="foto[]" class="form-control" id="fotoInput" accept="image/*" multiple>

                        @error('foto.*') <small class="text-danger">{{ $message }}</small> @enderror

                        <div id="preview-area" class="mt-2 d-flex flex-wrap"></div>

                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('kamar.index') }}" class="btn btn-secondary">Batal</a>
                    <button class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection


@push('scripts')
<script>
    // Preview foto baru
    document.getElementById('fotoInput')?.addEventListener('change', function(e) {
        const preview = document.getElementById('preview-area');
        preview.innerHTML = "";

        Array.from(e.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = ev => {
                const img = document.createElement("img");
                img.src = ev.target.result;
                img.className = "preview-img";
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });

    // AJAX delete foto
    function deleteMedia(id) {
        if (!confirm("Hapus foto ini?")) return;

        fetch(`/kamar/media/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`.old-img-box[data-id="${id}"]`).remove();
            } else {
                alert("Gagal menghapus foto.");
            }
        })
        .catch(err => alert("Terjadi kesalahan."));
    }
</script>
@endpush
