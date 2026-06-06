@extends('layouts.admin.app')
@section('title', 'Edit Destinasi Wisata')

@push('styles')
<style>
    .fade-in { animation: fadeIn .5s ease-in-out; }
    @keyframes fadeIn {
        from { opacity:0; transform: translateY(10px); }
        to { opacity:1; transform: translateY(0); }
    }

    .thumb-box {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e0e0e0;
        background: #f9fafb;
        transition: .2s;
    }
    .thumb-box img {
        width: 100%;
        height: 130px;
        object-fit: cover;
    }
    .thumb-box:hover {
        box-shadow: 0 8px 18px rgba(0,0,0,.15);
        transform: translateY(-3px);
    }
    .thumb-delete {
        position: absolute;
        top: 6px;
        right: 6px;
        border: none;
        border-radius: 999px;
        padding: 4px 8px;
        font-size: 12px;
    }

    .preview-img {
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top: 35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-blue mb-1">✏️ Edit Destinasi Wisata</h3>
            <p class="text-muted mb-0">Perbarui informasi destinasi dan galeri fotonya.</p>
        </div>

        <a href="{{ route('destinasi.index') }}" class="btn btn-secondary">
            &larr; Kembali
        </a>
    </div>

    {{-- FORM --}}
    <div class="card shadow-sm p-4">
        <form action="{{ route('destinasi.update', $dest->destinasi_id) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- NAMA --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Destinasi</label>
                    <input type="text" name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $dest->nama) }}"
                           required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TIKET --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Harga Tiket (Rp)</label>
                    <input type="number" name="tiket"
                           class="form-control @error('tiket') is-invalid @enderror"
                           value="{{ old('tiket', $dest->tiket) }}"
                           required>
                    @error('tiket')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KONTAK --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kontak / WA</label>
                    <input type="text" name="kontak"
                           class="form-control"
                           value="{{ old('kontak', $dest->kontak) }}">
                </div>

                {{-- ALAMAT --}}
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2"
                              class="form-control @error('alamat') is-invalid @enderror"
                              required>{{ old('alamat', $dest->alamat) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- RT --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">RT</label>
                    <select name="rt" class="form-select">
                        <option value="">- Pilih RT -</option>
                        @for ($i=1; $i<=20; $i++)
                            <option value="{{ $i }}"
                                {{ old('rt', $dest->rt) == $i ? 'selected' : '' }}>
                                RT {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- RW --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">RW</label>
                    <select name="rw" class="form-select">
                        <option value="">- Pilih RW -</option>
                        @for ($i=1; $i<=20; $i++)
                            <option value="{{ $i }}"
                                {{ old('rw', $dest->rw) == $i ? 'selected' : '' }}>
                                RW {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- JAM BUKA --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jam Buka (Opsional)</label>
                    <input type="text" name="jam_buka"
                           class="form-control"
                           placeholder="Contoh: 08.00 - 17.00 WIB"
                           value="{{ old('jam_buka', $dest->jam_buka) }}">
                </div>

                {{-- DESKRIPSI --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $dest->deskripsi) }}</textarea>
                </div>

                {{-- GALERI FOTO EXISTING --}}
                <div class="col-12 mt-3">
                    <label class="form-label fw-semibold">Galeri Foto Saat Ini</label>

                    <div class="row g-3">
                        @forelse($dest->media as $img)
                            <div class="col-md-3 col-sm-4 col-6">
                                <div class="thumb-box">
                                    <img src="{{ asset('storage/'.$img->file_url) }}" alt="foto">

                                    <button type="button"
                                            class="btn btn-sm btn-danger thumb-delete"
                                            data-url="{{ url('/destinasi/media/'.$img->media_id) }}">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted ms-1">Belum ada foto untuk destinasi ini.</p>
                        @endforelse
                    </div>
                </div>

                {{-- UPLOAD FOTO BARU --}}
                <div class="col-12 mt-3">
                    <label class="form-label fw-semibold">
                        Tambah Foto Baru (bisa pilih lebih dari satu)
                    </label>
                    <input type="file" name="foto[]" class="form-control" multiple accept="image/*">

                    @error('foto.*')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror

                    {{-- Preview foto baru --}}
                    <div id="preview-area" class="row g-2 mt-2"></div>
                </div>

            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('destinasi.index') }}" class="btn btn-secondary">
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
document.addEventListener('DOMContentLoaded', function () {

    // Preview foto baru
    const inputFoto = document.querySelector('input[name="foto[]"]');
    const previewArea = document.getElementById('preview-area');

    if (inputFoto) {
        inputFoto.addEventListener('change', function () {
            previewArea.innerHTML = '';

            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-sm-4 col-6';

                    col.innerHTML = `
                        <img src="${e.target.result}" class="preview-img w-100 mb-2" alt="preview">
                    `;

                    previewArea.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    // Hapus foto existing via AJAX
    document.querySelectorAll('.thumb-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('Hapus foto ini?')) return;

            const url = this.getAttribute('data-url');

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // hapus dari DOM
                    this.closest('.col-md-3, .col-sm-4, .col-6').remove();
                } else {
                    alert('Gagal menghapus foto.');
                }
            })
            .catch(() => alert('Terjadi kesalahan saat menghapus foto.'));
        });
    });

});
</script>
@endpush
