@extends('layouts.admin.app')
@section('title', 'Edit Homestay')

@push('styles')
    <style>
        .fade-in {
            animation: fadeIn .5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .preview-img {
            width: 110px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin: 6px;
            border: 1px solid #eee;
        }

        .old-media-box {
            position: relative;
            display: inline-block;
            margin: 5px;
        }

        .btn-del-media {
            position: absolute;
            top: -6px;
            right: -6px;
            border-radius: 50%;
            font-size: 11px;
            padding: 3px 7px;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid fade-in" style="padding-top:35px;">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-blue mb-0">✏️ Edit Homestay</h3>
                <small class="text-muted">Perbarui informasi homestay.</small>
            </div>

            <a href="{{ route('homestay.index') }}" class="btn btn-secondary">← Kembali</a>
        </div>


        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('homestay.update', $hs->homestay_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="row g-3">

                        {{-- KIRI --}}
                        <div class="col-md-7">

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Homestay</label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ old('nama', $hs->nama) }}" required>
                            </div>

                            {{-- Pemilik --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pemilik (Warga)</label>
                                <select name="pemilik_warga_id" class="form-select" required>
                                    <option value="">-- Pilih Pemilik --</option>
                                    @foreach ($warga as $w)
                                        <option value="{{ $w->warga_id }}"
                                            {{ old('pemilik_warga_id', $hs->pemilik_warga_id) == $w->warga_id ? 'selected' : '' }}>
                                            {{ $w->nama }} - {{ $w->no_ktp }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Alamat --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $hs->alamat) }}</textarea>
                            </div>

                            {{-- RT / RW --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">RT</label>
                                    <select name="rt" class="form-select">
                                        @for ($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}" {{ old('rt', $hs->rt) == $i ? 'selected' : '' }}>
                                                RT {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">RW</label>
                                    <select name="rw" class="form-select">
                                        @for ($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}"
                                                {{ old('rw', $hs->rw) == $i ? 'selected' : '' }}>RW {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            {{-- Fasilitas --}}
                            @php
                                $fasilitasOld = json_decode($hs->fasilitas_json ?? '[]', true) ?? [];
                            @endphp

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Fasilitas</label>
                                <div class="row">
                                    @foreach (['WiFi', 'AC', 'Sarapan', 'Parkir', 'TV', 'Kamar Mandi Dalam'] as $f)
                                        <div class="col-md-4">
                                            <label class="form-check">
                                                <input type="checkbox" name="fasilitas[]" value="{{ $f }}"
                                                    class="form-check-input"
                                                    {{ in_array($f, old('fasilitas', $fasilitasOld)) ? 'checked' : '' }}>
                                                <span class="form-check-label">{{ $f }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Harga + Status --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga/malam</label>
                                    <input type="number" name="harga_per_malam" class="form-control"
                                        value="{{ old('harga_per_malam', $hs->harga_per_malam) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="tersedia" {{ old('status', $hs->status) == 'tersedia' ? 'selected' : '' }}>
                                            Tersedia</option>
                                        <option value="penuh" {{ old('status', $hs->status) == 'penuh' ? 'selected' : '' }}>Penuh
                                        </option>
                                        <option value="tutup" {{ old('status', $hs->status) == 'tutup' ? 'selected' : '' }}>Tutup
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        {{-- KANAN --}}
                        <div class="col-md-5">

                            <h5 class="fw-semibold">Galeri Foto</h5>

                            {{-- Foto Lama --}}
                            <div id="old-images" class="mb-3">
                                @forelse($hs->media as $m)
                                    <div class="old-media-box" data-id="{{ $m->media_id }}">
                                        <img src="{{ asset('storage/' . $m->file_url) }}" class="preview-img">
                                        <button type="button" class="btn btn-danger btn-sm btn-del-media"
                                            onclick="deleteMedia({{ $m->media_id }})">✕</button>
                                    </div>
                                @empty
                                    <p class="text-muted small">Belum ada foto.</p>
                                @endforelse
                            </div>

                            {{-- Upload Baru --}}
                            <label class="form-label fw-semibold">Tambah Foto Baru</label>
                            <input type="file" name="foto[]" id="fotoInput" multiple class="form-control"
                                accept="image/*">

                            <div id="preview-area" class="d-flex flex-wrap mt-2"></div>

                        </div>

                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('homestay.index') }}" class="btn btn-secondary">Batal</a>
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
            preview.innerHTML = '';

            [...e.target.files].forEach(file => {
                const reader = new FileReader();
                reader.onload = ev => {
                    let img = document.createElement('img');
                    img.src = ev.target.result;
                    img.className = "preview-img";
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });

        // Delete media via AJAX
        function deleteMedia(mediaId) {
            if (!confirm('Hapus foto ini?')) return;

            fetch("{{ url('/homestay/media') }}/" + mediaId, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('[data-id="' + mediaId + '"]').remove();
                    } else {
                        alert("Gagal menghapus foto.");
                    }
                });
        }
    </script>
@endpush
