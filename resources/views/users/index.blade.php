@extends('layouts.admin.app')
@section('title', 'Data User')

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

        .page-container {
            padding-top: 35px;
        }

        .password-box {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-eye {
            cursor: pointer;
            font-size: 18px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid fade-in page-container">

        <div class="d-flex justify-content-between mb-3">
            <h3 class="fw-bold text-blue">👤 Manajemen User</h3>
            <a href="{{ route('user.create') }}" class="btn btn-primary px-4">+ Tambah User</a>
        </div>

        {{-- FILTER & SEARCH --}}
        <form method="GET" class="row g-2 mb-3">

            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-3">
                <select name="role" class="form-control">
                    <option value="all">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-info w-100">Filter</button>
            </div>

        </form>

        <div class="card shadow-sm p-3">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $u)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>

                            {{-- FOTO USER --}}
                            <td>
                                @if ($u->fotoProfil)
                                    <img src="{{ asset('storage/' . $u->fotoProfil->file_url) }}" class="rounded-circle"
                                        width="45" height="45" style="object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default-user.png') }}" class="rounded-circle" width="45"
                                        height="45">
                                @endif
                            </td>

                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>

                            {{-- PASSWORD --}}
                            <td>
                                <div class="password-box">
                                    <span class="password-mask" id="mask-{{ $u->id }}">••••••••••</span>
                                    <span class="password-real d-none"
                                        id="real-{{ $u->id }}">{{ $u->password }}</span>
                                    <span class="toggle-eye text-primary"
                                        onclick="togglePassword({{ $u->id }})">👁️</span>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-primary">{{ ucfirst($u->role) }}</span>
                            </td>

                            <td>
                                <a href="{{ route('user.edit', $u->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <form method="POST" action="{{ route('user.destroy', $u->id) }}" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus user?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>


            </table>

            {{-- PAGINATION SIMPLE & RAPI --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </div>

    {{-- SHOW / HIDE PASSWORD --}}
    <script>
        function togglePassword(id) {
            const mask = document.getElementById('mask-' + id);
            const real = document.getElementById('real-' + id);

            if (real.classList.contains('d-none')) {
                real.classList.remove('d-none');
                mask.classList.add('d-none');
            } else {
                real.classList.add('d-none');
                mask.classList.remove('d-none');
            }
        }
    </script>

@endsection
