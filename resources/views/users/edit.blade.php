@extends('layouts.admin.app')
@section('title', 'Edit User')

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
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in">

    {{-- PAGE TITLE --}}
    <h3 class="fw-bold text-blue mb-3">✏ Edit User</h3>

    {{-- CARD --}}
    <div class="card shadow-sm p-4 mx-auto"
         style="border-radius:14px; max-width:700px;">

        <form action="{{ route('user.update', $user->id) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- =====================
                 FOTO PROFIL
            ====================== --}}
            <div class="text-center mb-4">

                @if ($user->fotoProfil)
                    <img src="{{ asset('storage/' . $user->fotoProfil->file_url) }}"
                         class="rounded-circle shadow mb-2"
                         width="120"
                         height="120"
                         style="object-fit:cover;">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=C62828&color=fff&size=120&rounded=true"
                         class="rounded-circle shadow mb-2">
                @endif

                <label class="fw-semibold d-block mt-2">Foto Profil</label>

                <input type="file"
                       name="foto"
                       class="form-control mt-1"
                       accept="image/*">

                <small class="text-muted">
                    Format JPG/PNG • Maksimal 2MB
                </small>
            </div>

            {{-- =====================
                 NAMA
            ====================== --}}
            <div class="mb-3">
                <label class="fw-semibold">Nama</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name', $user->name) }}"
                       required>
            </div>

            {{-- =====================
                 EMAIL
            ====================== --}}
            <div class="mb-3">
                <label class="fw-semibold">Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email', $user->email) }}"
                       required>

                <div id="email-status" class="mt-1"></div>
            </div>

            {{-- =====================
                 PASSWORD
            ====================== --}}
            <div class="mb-3">
                <label class="fw-semibold">
                    Password
                    <small class="text-muted">(kosongkan jika tidak diganti)</small>
                </label>
                <input type="password"
                       name="password"
                       class="form-control">
            </div>

            {{-- =====================
                 ROLE
            ====================== --}}
            <div class="mb-4">
                <label class="fw-semibold">Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                    <option value="petugas" {{ $user->role === 'petugas' ? 'selected' : '' }}>
                        Petugas
                    </option>
                </select>
            </div>

            {{-- =====================
                 ACTION BUTTON
            ====================== --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success px-4">
                    <i class="ti ti-check"></i> Update
                </button>

                <a href="{{ route('user.index') }}"
                   class="btn btn-secondary px-4">
                    Kembali
                </a>
            </div>

        </form>

    </div>

</div>

{{-- =========================
     AJAX CEK EMAIL
========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const emailInput = document.querySelector('input[name="email"]');
    const statusBox  = document.getElementById('email-status');
    const originalEmail = "{{ $user->email }}";

    emailInput.addEventListener('input', function () {
        const email = this.value;

        if (!email || email === originalEmail) {
            statusBox.innerHTML = '';
            return;
        }

        fetch("{{ route('user.checkEmail') }}?email=" + encodeURIComponent(email))
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    statusBox.innerHTML =
                        "<span class='text-danger'>Email sudah digunakan</span>";
                } else {
                    statusBox.innerHTML =
                        "<span class='text-success'>Email tersedia ✓</span>";
                }
            });
    });

});
</script>
@endsection
