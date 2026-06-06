@extends('layouts.admin.app')
@section('title', 'Tambah User')

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

        <h3 class="fw-bold text-blue mb-3">➕ Tambah User</h3>

        <div class="card p-4 shadow-sm" style="border-radius: 14px;">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf

                <label class="fw-semibold">Nama</label>
                <input type="text" name="name" class="form-control mb-3" required>

                <label class="fw-semibold">Email</label>
                <input type="email" name="email" class="form-control mb-3" required>

                <label class="fw-semibold">Password</label>
                <input type="password" name="password" class="form-control mb-3" required>

                <label class="fw-semibold">Role</label>
                <select name="role" class="form-control mb-3">
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                </select>

                <button class="btn btn-success px-4">Simpan</button>
                <a href="{{ route('user.index') }}" class="btn btn-secondary px-4">Kembali</a>
            </form>

            <script>
                document.querySelector('input[name="email"]').addEventListener('input', function() {
                    let email = this.value;

                    fetch("{{ route('user.checkEmail') }}?email=" + email)
                        .then(res => res.json())
                        .then(data => {
                            let msg = document.getElementById("email-status");

                            if (data.exists) {
                                msg.innerHTML = "<span class='text-danger'>Email sudah dipakai!</span>";
                            } else {
                                msg.innerHTML = "<span class='text-success'>Email tersedia ✓</span>";
                            }
                        });
                });
            </script>

        </div>

    </div>
@endsection
