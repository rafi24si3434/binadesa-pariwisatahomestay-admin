@extends('layouts.admin.app')
@section('title', 'Profil Saya')

@push('styles')
<style>
    .profile-card {
        border-radius: 18px;
        background: #fff;
        padding: 25px;
        box-shadow: 0 5px 18px rgba(0,0,0,0.10);
        transition: .2s;
    }

    .profile-card:hover {
        transform: translateY(-3px);
    }

    .profile-img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #C62828;
    }

    .brand-red {color:#C62828;}
</style>
@endpush

@section('content')
<div class="container py-4">

    <h3 class="fw-bold brand-red mb-4">👤 Profil Saya</h3>

    <div class="profile-card">

        {{-- FOTO PROFIL --}}
        <div class="text-center mb-4">
            <img src="{{ $user->foto_url }}" class="profile-img shadow" alt="Foto Profil">
            <h4 class="mt-3 fw-bold">{{ $user->name }}</h4>
            <p class="text-muted">{{ $user->email }}</p>
        </div>

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- FORM UPDATE PROFIL --}}
        <form action="{{ route('user.updateProfil') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                {{-- NAMA --}}
                <div class="col-md-6 mb-3">
                    <label class="fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- EMAIL --}}
                <div class="col-md-6 mb-3">
                    <label class="fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="form-control @error('email') is-invalid @enderror" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="col-md-6 mb-3">
                    <label class="fw-semibold">Password (opsional)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Biarkan kosong jika tidak ingin mengubah">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- FOTO --}}
                <div class="col-md-6 mb-3">
                    <label class="fw-semibold">Foto Profil</label>
                    <input type="file" name="foto" accept="image/*"
                           class="form-control @error('foto') is-invalid @enderror">
                    @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div>

            <button type="submit" class="btn btn-danger px-4 mt-2">Update Profil</button>
        </form>

    </div>

</div>
@endsection
