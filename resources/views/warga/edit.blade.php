@extends('layouts.admin.app')
@section('title', 'Edit Warga')

@push('styles')
<style>
    .fade-in {
        animation: fadeIn .4s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    label { font-weight: 600; }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold text-blue">✏️ Edit Data Warga</h3>

        <a href="{{ route('warga.index') }}" class="btn btn-secondary px-4">
            ← Kembali
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="card shadow-sm p-4">

        <form method="POST" action="{{ route('warga.update', $warga->warga_id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">

                {{-- No KTP --}}
                <div class="col-md-6">
                    <label>No KTP</label>
                    <input type="text" name="no_ktp"
                           class="form-control @error('no_ktp') is-invalid @enderror"
                           value="{{ old('no_ktp', $warga->no_ktp) }}">
                    @error('no_ktp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nama --}}
                <div class="col-md-6">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $warga->nama) }}">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div class="col-md-4">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="L" {{ old('jenis_kelamin', $warga->jenis_kelamin)=='L' ? 'selected':'' }}>
                            Laki-laki
                        </option>
                        <option value="P" {{ old('jenis_kelamin', $warga->jenis_kelamin)=='P' ? 'selected':'' }}>
                            Perempuan
                        </option>
                    </select>
                </div>

                {{-- Agama --}}
                <div class="col-md-4">
                    <label>Agama</label>
                    <input type="text" name="agama"
                           class="form-control"
                           value="{{ old('agama', $warga->agama) }}">
                </div>

                {{-- Pekerjaan --}}
                <div class="col-md-4">
                    <label>Pekerjaan</label>
                    <input type="text" name="pekerjaan"
                           class="form-control"
                           value="{{ old('pekerjaan', $warga->pekerjaan) }}">
                </div>

                {{-- No Telp --}}
                <div class="col-md-6">
                    <label>No Telepon</label>
                    <input type="text" name="telp"
                           class="form-control"
                           value="{{ old('telp', $warga->telp) }}">
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email"
                           class="form-control"
                           value="{{ old('email', $warga->email) }}">
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="mt-4">
                <button class="btn btn-primary px-4">Perbarui Data</button>
            </div>

        </form>

    </div>

</div>
@endsection
