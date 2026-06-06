@extends('layouts.admin.app')
@section('title', 'Tambah Warga')

@push('styles')
<style>
    .fade-in { animation: fadeIn .4s ease-in-out; }
    @keyframes fadeIn { from {opacity:0; transform:translateY(10px);} to {opacity:1;} }
    label { font-weight: 600; }
    .text-danger.small { font-size: 13px; }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in" style="padding-top:35px;">

    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold text-blue">➕ Tambah Warga</h3>
        <a href="{{ route('warga.index') }}" class="btn btn-secondary px-4">← Kembali</a>
    </div>

    <div class="card shadow-sm p-4">
        <form method="POST" action="{{ route('warga.store') }}">
            @csrf

            <div class="row g-3">

                {{-- NO KTP --}}
                <div class="col-md-6">
                    <label>No KTP</label>
                    <input type="text" id="no_ktp" name="no_ktp"
                           class="form-control"
                           maxlength="16"
                           placeholder="16 digit angka">
                    <small id="ktpWarning" class="text-danger d-none">❌ KTP sudah terdaftar!</small>
                </div>

                {{-- NAMA --}}
                <div class="col-md-6">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama') }}">
                </div>

                {{-- JENIS KELAMIN --}}
                <div class="col-md-4">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                {{-- AGAMA --}}
                <div class="col-md-4">
                    <label>Agama</label>
                    <select name="agama" class="form-control">
                        <option value="">-- Pilih Agama --</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Khonghucu">Khonghucu</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                {{-- PEKERJAAN --}}
                <div class="col-md-4">
                    <label>Pekerjaan</label>
                    <input type="text" name="pekerjaan" class="form-control">
                </div>

                {{-- TELP --}}
                <div class="col-md-6">
                    <label>No Telepon</label>
                    <input type="text" name="telp" class="form-control">
                </div>

                {{-- EMAIL --}}
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" id="email" name="email" class="form-control">
                    <small id="emailWarning" class="text-danger d-none">❌ Email sudah digunakan!</small>
                </div>

            </div>

            <button id="submitBtn" class="btn btn-primary mt-4 px-4">
                Simpan Data
            </button>

        </form>
    </div>
</div>

{{-- AJAX + AUTO FORMAT --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
/* ============================
   AUTO FORMAT KTP (16 DIGIT)
============================ */
$('#no_ktp').on('input', function () {
    this.value = this.value.replace(/\D/g, '').substring(0, 16);
});

/* ============================
   CEK KTP REALTIME (AJAX)
============================ */
$('#no_ktp').on('keyup', function () {
    let ktp = $(this).val();

    if (ktp.length === 16) {
        $.get("{{ route('warga.checkKTP') }}", { no_ktp: ktp }, function (data) {

            if (data.exists) {
                $('#ktpWarning').removeClass('d-none');
                $('#submitBtn').prop('disabled', true);
            } else {
                $('#ktpWarning').addClass('d-none');
                $('#submitBtn').prop('disabled', false);
            }

        });
    }
});

/* ============================
   CEK EMAIL REALTIME (AJAX)
============================ */
$('#email').on('keyup', function () {
    let email = $(this).val();

    if (email.includes('@')) {
        $.get("{{ route('warga.checkEmail') }}", { email: email }, function (data) {

            if (data.exists) {
                $('#emailWarning').removeClass('d-none');
                $('#submitBtn').prop('disabled', true);
            } else {
                $('#emailWarning').addClass('d-none');
                $('#submitBtn').prop('disabled', false);
            }

        });
    }
});
</script>

@endsection
