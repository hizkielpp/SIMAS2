<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $user->roleTabel->nama }} | @yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}" />
    <!-- Template : theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />

    <!-- Fontawesome Icons -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />


    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- Versi terbaru dari xlsx.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.0/xlsx.full.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/beranda-style.css') }}" />

    {{-- PDF Object styling start --}}
    <style>
        .pdfobject-container {
            height: 100%;
        }
    </style>
    {{-- PDF Object styling end --}}
    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    @php
        // Inisialisasi semua telah dibaca dengan true
        $semuaTelahDibaca = true;

        // Periksa setiap disposisi
        foreach ($allDisposisi as $key) {
            // Jika disposisi belum dibaca, atur $semuaTelahDibaca menjadi false dan hentikan iterasi
            if ($key->isOpened == false) {
                $semuaTelahDibaca = false;
                break;
            }
        }
    @endphp
    <div class="wrapper">
        {{-- Preloader start --}}
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{ asset('img/logo-undip.png') }}" alt="Loading Animation"
                height="100" width="100" />
        </div>
        {{-- Preloader end --}}

        {{-- Navbar start --}}
        <nav id="navbar" class="main-header navbar navbar-expand bg-white border-0">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-black" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <div class="icon-wrapper notifications border border-1 me-3">
                    <button type="button">
                        <i class="fa-solid fa-bell"></i>
                    </button>
                    @if (!$semuaTelahDibaca)
                        <div class="notification-mark notification-mark--pulsing"></div>
                    @endif
                </div>
                <div class="dropdown__wrapper hide dropdown__wrapper--fade-in none">
                    <div class="notifications-top">
                        <h2 class="fs-6">Notifikasi Disposisi</h2>
                    </div>
                    <div class="notification-items">
                        <div class="notification-item notification-item--recent">
                            @if (count($allDisposisi) === 0)
                                <p>Tidak ada pemberitahuan disposisi.</p>
                            @else
                                @foreach ($allDisposisi as $disposisi)
                                    <i class="fa-solid fa-circle-user fs-4"></i>
                                    <div class="notification-item__body">
                                        <div class="fs-6">
                                            <strong>{{ $disposisi->jabatan_pengirim }}</strong> mengirim disposisi
                                            kepada
                                            {{ $disposisi->jabatan_penerima }}
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="time">
                                                {{ $disposisi->selisih_waktu }}
                                            </span>
                                            @if (!$disposisi->isOpened)
                                                <a class="text-decoration-underline telah-dibaca-button"
                                                    data-id="{{ $disposisi->id }}"
                                                    style="font-size: 0.8rem; cursor: pointer;">Telah dibaca </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="border"></div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>
                <li class="nav-item d-flex align-items-center user">
                    <i class="fa-solid fa-user"></i>
                    <div class="dropdown">
                        <button class="border-0 bg-transparent dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ $user->name }}
                            <i class="fa-solid fa-angle-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"><i
                                        class="fa-solid fa-arrow-right-from-bracket me-2"></i>Keluar</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
        {{-- Navbar end --}}

        {{-- Sidebar container start --}}
        <aside id="sidebar" class="main-sidebar sidebar-dark-primary">
            {{-- Sidebar brand start --}}
            <a href="{{ route('dashboard.dashboard') }}" class="brand-link d-flex align-items-center border-0">
                <div class="d-flex justify-content-center align-items-center abjad me-2">
                    <h3 class="black fw__med">S</h3>
                </div>
                <div class="name brand-text text-white">
                    <h6 class="fw-semibold mb-1">SIMAS</h6>
                    <h6 id="brandText" class="fw__normal">Sistem Manajemen Surat</h6>
                </div>
            </a>
            {{-- Sidebar brand end --}}

            {{-- Sidebar content start --}}
            <div id="sidebarMenu" class="sidebar">
                <nav class="mt-5">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item nav-link mb-2 text-secondary pb-0" style="font-size: 14px">MENU</li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('dashboard.dashboard') }}" class="nav-link @yield('db')">
                                <i class="fa-solid fa-house me-2"></i>
                                <p class="fw-normal">Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('surat-masuk.index') }}" class="nav-link @yield('sm')">
                                <i class="fa-solid fa-envelope me-2"></i>
                                <p class="fw-normal">Surat Masuk</p>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('suratKeluar') }}" class="nav-link @yield('sk')">
                                <i class="fa-solid fa-envelope me-2"></i>
                                <p class="fw-normal">Surat Keluar</p>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('suratAntidatir') }}" class="nav-link @yield('sa')">
                                <i class="fa-solid fa-envelope me-2"></i>
                                <p class="fw-normal">Surat Antidatir</p>
                            </a>
                        </li>
                        @if ($user->role_id == 1)
                            <li class="nav-item nav-link mb-2 text-secondary pt-4 pb-0" style="font-size: 14px">
                                ADMINISTRATOR</li>
                            <li class="nav-item mb-2">
                                <a href="{{ route('kelolaAkun') }}" class="nav-link @yield('ka')">
                                    <i class="fa-solid fa-user-gear me-2"></i>
                                    <p class="fw-normal">Kelola Akun</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            {{-- Sidebar content end --}}
        </aside>
        {{-- Sidebar container end --}}

        {{-- Main section start --}}
        <div id="mainPage" class="content-wrapper border-0">@yield('content')</div>
        {{-- Main section end --}}

        {{-- Footer start --}}
        <footer class="main-footer px-4 py-3">
            Copyright &copy; 2023. <b> Digital Innovation & Media.</b>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0</div>
        </footer>
        {{-- Footer end --}}

        {{-- Toast start --}}
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Berhasil!</strong>
                    <small>Baru saja</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Menandai disposisi telah dibaca.
                </div>
            </div>
        </div>
        {{-- Toast end --}}
    </div>

    <!-- Template : AdminLTE App -->
    <script src="{{ asset('js/adminlte.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Fontawesome Icons -->
    <script src="{{ asset('js/all.min.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/script.js') }}"></script>
    @yield('js')
</body>

{{-- PDF Object --}}
<script src="{{ asset('js/pdfobject.min.js') }}"></script>

<script>
    // Initializing toast bootstrap start
    const toastLiveExample = document.getElementById('liveToast')
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)

    // Handler telah dibaca start
    $(document).on('click', '.telah-dibaca-button', function() {
        let id = $(this).data('id');
        let url = "{{ route('surat-masuk.disposisiOpened', ':id') }}".replace(':id', id);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(result) {
                $('.telah-dibaca-button[data-id="' + id + '"]').hide();
                toastBootstrap.show();
            },
            error: function(xhr) {
                alert(xhr);
            }
        });
    });
    // Handler telah dibaca end
</script>

</html>
