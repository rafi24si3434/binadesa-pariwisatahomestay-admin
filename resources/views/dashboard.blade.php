@extends('layouts.admin.app')
@section('title', 'Dashboard')

@push('styles')
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn .6s ease-out;
        }

        /* Tema Dashboard */
        .brand-red {
            color: #C62828 !important;
        }

        .brand-red-bg {
            background: #C62828 !important;
        }

        .brand-red-soft {
            background: #FDECEC !important;
        }

        /* Card Style */
        .card-soft {
            border-radius: 16px;
            background: #fff;
            border: 1px solid #f0f0f0;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.07);
            transition: .25s ease-in-out;
        }

        .card-soft:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 26px;
            color: #fff;
        }
    </style>
@endpush



@section('content')
    <div class="container-fluid py-4 fade-in">

        {{-- HEADER --}}
        <div class="d-flex align-items-center mb-4">
            <img src="{{ asset('assets/images/logo.png') }}" style="width:200px; margin-right:20px;" />

            <div>
                <h3 class="fw-bold brand-red mb-0">Dashboard Bina Desa</h3>
                <p class="text-muted mb-0">Sistem Informasi Pariwisata & Homestay</p>
            </div>

            {{-- JAM --}}
            <div class="ms-auto text-end">
                <h4 class="fw-bold brand-red mb-0" id="clock" style="font-size:28px;"></h4>
                <span class="text-muted" id="date-today"></span>
            </div>
        </div>



        {{-- ============================= --}}
        {{-- STATISTIK --}}
        {{-- ============================= --}}
        <div class="row g-3 mb-4">

            {{-- TOTAL HOMESTAY --}}
            <div class="col-md-3">
                <div class="card card-soft">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon brand-red-bg me-3">
                            <i class="fa fa-hotel"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold brand-red mb-0">Total Homestay</h6>
                            <span class="text-muted">{{ $totalHomestay }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOTAL DESTINASI --}}
            <div class="col-md-3">
                <div class="card card-soft">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-primary me-3">
                            <i class="fa fa-map-location-dot"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-primary mb-0">Destinasi Wisata</h6>
                            <span class="text-muted">{{ $totalDestinasi }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOTAL BOOKING --}}
            <div class="col-md-3">
                <div class="card card-soft">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-warning me-3">
                            <i class="fa fa-calendar-check"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-warning mb-0">Total Booking</h6>
                            <span class="text-muted">{{ $totalBooking }}</span>
                        </div>
                    </div>
                </div>
            </div>


            {{-- ✔ VARIASI BARU : TOTAL KAMAR --}}
            <div class="col-md-3">
                <div class="card card-soft">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-dark me-3">
                            <i class="fa fa-bed"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Total Kamar</h6>
                            <span class="text-muted">{{ \App\Models\KamarHomestay::count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>





        {{-- ============================= --}}
        {{-- CHART --}}
        {{-- ============================= --}}
        <div class="row g-4 mb-4">

            {{-- CHART VISITS --}}
            <div class="col-lg-6">
                <div class="card card-soft p-3">
                    <h6 class="fw-bold brand-red">Kunjungan Wisata (Mingguan)</h6>
                    <canvas id="chartVisits" height="140"></canvas>
                </div>
            </div>

            {{-- CHART HUNIAN --}}
            <div class="col-lg-6">
                <div class="card card-soft p-3">
                    <h6 class="fw-bold text-primary">Tingkat Hunian Homestay (%)</h6>
                    <canvas id="chartHunian" height="140"></canvas>
                </div>
            </div>

        </div>



        {{-- ============================= --}}
        {{-- LIST: HOMESTAY TERBARU --}}
        {{-- ============================= --}}
        <div class="row g-4">

            {{-- HOMESTAY TERBARU --}}
            <div class="col-lg-6">
                <div class="card card-soft">
                    <div class="card-header fw-bold brand-red">
                        Homestay Terbaru
                    </div>

                    <div class="card-body">
                        @if ($homestayTerbaru->count() == 0)
                            <p class="text-muted fst-italic small">Belum ada homestay.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                {{-- Tambahkan ->take(5) di baris ini --}}
                                @foreach ($homestayTerbaru->take(5) as $hs)
                                    <li class="list-group-item py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between w-100">

                                            {{-- Nama --}}
                                            <span class="small fw-semibold text-dark">
                                                {{ $hs->nama }}
                                            </span>

                                            {{-- Jumlah kamar --}}
                                            <span class="badge bg-primary small" style="font-size: 11px;">
                                                {{ $hs->kamar_count }} kamar
                                            </span>

                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>



            {{-- EVENT --}}
            <div class="col-lg-6">
                <div class="card card-soft">
                    <div class="card-header fw-bold text-primary">
                        Ulasan Wisata Terbaru
                    </div>

                    <div class="card-body">

                        @if ($ulasanTerbaru->count() == 0)
                            <p class="text-muted fst-italic small">Belum ada ulasan dari warga.</p>
                        @else
                            <ul class="list-group list-group-flush">

                                {{-- Tambahkan ->take(2) di baris ini --}}
                                @foreach ($ulasanTerbaru->take(2) as $u)
                                    <li class="list-group-item py-2">

                                        {{-- NAMA & DESTINASI --}}
                                        <strong class="small d-block">
                                            {{ $u->warga->nama ?? 'Warga Tidak Diketahui' }}
                                        </strong>

                                        <span class="text-primary fw-bold small d-block">
                                            {{ $u->destinasi->nama ?? '-' }}
                                        </span>

                                        {{-- RATING (kecil) --}}
                                        <div class="text-warning" style="font-size: 11px;">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $u->rating ? '' : 'text-secondary' }}"></i>
                                            @endfor
                                        </div>

                                        {{-- KOMENTAR (lebih kecil) --}}
                                        <div class="text-muted small" style="font-size: 12px;">
                                            "{{ $u->komentar }}"
                                        </div>

                                        {{-- WAKTU --}}
                                        <small class="text-secondary d-block" style="font-size: 11px;">
                                            {{ \Carbon\Carbon::parse($u->waktu)->diffForHumans() }}
                                        </small>

                                    </li>
                                @endforeach

                            </ul>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card card-soft">
                <div class="card-header fw-bold brand-red">
                    Warga Terbaru
                </div>

                <div class="card-body">

                    @if ($wargaTerbaru->count() == 0)
                        <p class="text-muted fst-italic small">Belum ada data warga.</p>
                    @else
                        <ul class="list-group list-group-flush">

                            @foreach ($wargaTerbaru as $w)
                                <li class="list-group-item py-2 px-3">

                                    <div class="d-flex align-items-center justify-content-between w-100">

                                        {{-- Nama Warga --}}
                                        <span class="small fw-semibold text-dark">
                                            {{ $w->nama }}
                                        </span>

                                        {{-- RT / RW --}}
                                        <span class="badge bg-secondary small" style="font-size: 11px;">
                                            RT {{ $w->rt }} / RW {{ $w->rw }}
                                        </span>

                                    </div>

                                </li>
                            @endforeach

                        </ul>
                    @endif

                </div>
            </div>
        </div>

        {{-- IDENTITAS PENGEMBANG --}}
        {{-- ============================= --}}
        <div class="row g-4 mt-4 fade-in">
            <div class="col-lg-12">
                <div class="card card-soft p-4 d-flex flex-row align-items-center">

                    <img src="{{ asset('assets/images/Muhammad Rafi.jpg') }}" class="rounded-circle shadow"
                        style="width:120px; height:120px; object-fit:cover; border:4px solid #C62828;">

                    <div class="ms-4">
                        <h4 class="fw-bold mb-1 brand-red">Identitas Pengembang</h4>

                        <p class="mb-1"><strong>Nama :</strong> Muhammad Rafi</p>
                        <p class="mb-1"><strong>NIM :</strong> 2457301096</p>
                        <p class="mb-1"><strong>Prodi :</strong> Sistem Informasi</p>

                        <div class="d-flex mt-2" style="gap:14px; font-size:26px;">
                            <a href="https://linkedin.com" target="_blank" class="text-primary">
                                <i class="fa-brands fa-linkedin"></i>
                            </a>
                            <a href="https://github.com" target="_blank" class="text-dark">
                                <i class="fa-brands fa-github"></i>
                            </a>
                            <a href="https://www.instagram.com/rraappii._/" target="_blank" class="text-danger">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row g-4 mt-4 fade-in">
            <div class="col-lg-12">
                <div class="card card-soft p-4 d-flex flex-row align-items-center">

                    <img src="{{ asset('assets/images/ricardo.jpeg') }}" class="rounded-circle shadow"
                        style="width:120px; height:120px; object-fit:cover; border:4px solid #C62828;">

                    <div class="ms-4">
                        <h4 class="fw-bold mb-1 brand-red">Identitas Pengembang</h4>

                        <p class="mb-1"><strong>Nama :</strong> Ricardo Zulkifli Raja Guk-Guk</p>
                        <p class="mb-1"><strong>NIM :</strong> 2457301121</p>
                        <p class="mb-1"><strong>Prodi :</strong> Sistem Informasi</p>

                        <div class="d-flex mt-2" style="gap:14px; font-size:26px;">
                            <a href="https://linkedin.com" target="_blank" class="text-primary">
                                <i class="fa-brands fa-linkedin"></i>
                            </a>
                            <a href="https://github.com" target="_blank" class="text-dark">
                                <i class="fa-brands fa-github"></i>
                            </a>
                            <a href="https://www.instagram.com/rraappii._/" target="_blank" class="text-danger">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection



@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ===========================
        // JAM REALTIME
        // ===========================
        function updateClock() {
            const now = new Date();
            document.getElementById("clock").innerHTML =
                now.toLocaleTimeString('id-ID', {
                    hour12: false
                });

            document.getElementById("date-today").innerHTML =
                now.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
        }
        updateClock();
        setInterval(updateClock, 1000);



        // ===========================
        // CHART VISITS
        // ===========================
        new Chart(document.getElementById("chartVisits"), {
            type: 'line',
            data: {
                labels: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"],
                datasets: [{
                    data: @json($chartKunjungan),
                    borderColor: "#C62828",
                    backgroundColor: "rgba(198,40,40,0.2)",
                    tension: .4,
                    borderWidth: 3
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });


        // ===========================
        // CHART HUNIAN
        // ===========================
        new Chart(document.getElementById("chartHunian"), {
            type: 'bar',
            data: {
                labels: @json($chartHunian['labels']),
                datasets: [{
                    data: @json($chartHunian['values']),
                    backgroundColor: ['#C62828', '#8E0000', '#C62828', '#8E0000']
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush
