<aside class="left-sidebar shadow-sm">

    <div class="sidebar-wrapper">

        {{-- LOGO --}}
        <div class="brand-logo text-center py-4 border-bottom">
            <a href="{{ route('dashboard') }}" class="logo-img d-block">
                <img src="{{ asset('assets/images/logos/logo.png') }}" alt="Logo" style="width:150px;">
            </a>
        </div>

        {{-- USER PROFILE --}}
        @if (session()->has('user_id'))
            @php
                $user = \App\Models\User::with('fotoProfil')->find(session('user_id'));
                $foto = $user?->fotoProfil?->file_url
                    ? asset('storage/' . $user->fotoProfil->file_url)
                    : 'https://ui-avatars.com/api/?name=' .
                        urlencode($user->name) .
                        '&background=C62828&color=fff&size=100&rounded=true';
            @endphp

            <div class="sidebar-profile text-center py-4 px-3">

                <div class="profile-avatar mb-2">
                    <img src="{{ $foto }}" class="rounded-circle" width="85" height="85">
                </div>

                <h6 class="fw-semibold mb-1">{{ $user->name }}</h6>

                <span class="badge role-badge">
                    {{ ucfirst($user->role) }}
                </span>

            </div>
        @endif

        {{-- NAVIGATION --}}
        <nav class="sidebar-nav px-2" data-simplebar>
            <ul id="sidebarnav">

                <li class="nav-section">
                    <span>MENU UTAMA</span>
                </li>

                {{-- DASHBOARD --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <iconify-icon icon="solar:atom-line-duotone" width="22"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- USERS --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('user.*') ? 'active' : '' }}"
                        href="{{ route('user.index') }}">
                        <iconify-icon icon="solar:users-group-rounded-line-duotone" width="22"></iconify-icon>
                        <span>Manajemen User</span>
                    </a>
                </li>

                {{-- WARGA --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('warga.*') ? 'active' : '' }}"
                        href="{{ route('warga.index') }}">
                        <iconify-icon icon="solar:users-group-two-rounded-line-duotone" width="22"></iconify-icon>
                        <span>Data Warga</span>
                    </a>
                </li>

                {{-- DESTINASI --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('destinasi.*') ? 'active' : '' }}"
                        href="{{ route('destinasi.index') }}">
                        <iconify-icon icon="solar:map-point-bold-duotone" width="22"></iconify-icon>
                        <span>Destinasi Wisata</span>
                    </a>
                </li>

                {{-- HOMESTAY --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('homestay.*') ? 'active' : '' }}"
                        href="{{ route('homestay.index') }}">
                        <iconify-icon icon="material-symbols:home-work-rounded" width="22"></iconify-icon>
                        <span>Homestay</span>
                    </a>
                </li>

                {{-- KAMAR --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('kamar.*') ? 'active' : '' }}"
                        href="{{ route('kamar.index') }}">
                        <iconify-icon icon="material-symbols:meeting-room-rounded" width="22"></iconify-icon>
                        <span>Kamar Homestay</span>
                    </a>
                </li>

                {{-- BOOKING --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('booking.*') ? 'active' : '' }}"
                        href="{{ route('booking.index') }}">
                        <iconify-icon icon="solar:calendar-bold-duotone" width="22"></iconify-icon>
                        <span>Booking</span>
                    </a>
                </li>

                {{-- ULASAN --}}
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('ulasan.*') ? 'active' : '' }}"
                        href="{{ route('ulasan.index') }}">
                        <iconify-icon icon="solar:star-bold-duotone" width="22"></iconify-icon>
                        <span>Ulasan Wisata</span>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</aside>

<style>
    .left-sidebar {
        background: #ffffff;
        border-right: 1px solid #eee;
    }

    .sidebar-profile {
        border-bottom: 1px solid #eee;
    }

    .profile-avatar img {
        border: 3px solid #C62828;
        object-fit: cover;
    }

    .role-badge {
        background: #C62828;
        color: #fff;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
    }

    .nav-section {
        font-size: 11px;
        font-weight: 700;
        color: #999;
        padding: 15px 15px 8px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 11px 15px;
        color: #444;
        font-weight: 500;
        border-radius: 10px;
        transition: all .2s ease;
    }

    .sidebar-link:hover {
        background: rgba(198, 40, 40, 0.08);
        color: #C62828;
    }

    .sidebar-link.active {
        background: #C62828;
        color: #fff;
    }

    .sidebar-link.active iconify-icon {
        color: #fff;
    }
</style>
