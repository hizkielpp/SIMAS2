<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $user->roleTabel->nama }} | @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />

    <!-- Fontawesome Icons -->
    <link rel="stylesheet" href="css/all.min.css" />

    <!-- Template : theme style -->
    <link rel="stylesheet" href="css/adminlte.min.css" />

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/beranda-style.css" />
    @yield('css')
</head>


<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        {{-- Preloader start --}}
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="img/logo-undip.png" alt="Loading Animation" height="100" width="100" />
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
                <li class="nav-item d-flex align-items-center user">
                    <div class="image__user bg-black d-flex justify-content-center align-items-center">
                        <h5 class="text-white">A</h5>
                    </div>
                    <div class="dropdown">
                        <button class="border-0 bg-transparent dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ $user->name }} <i class="fa-solid fa-angle-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu">
                            {{-- <li>
                                <a class="dropdown-item" href="profil-pengguna.html"><i
                                        class="fa-solid fa-user me-2"></i>Profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="ubah-password.html"><i
                                        class="fa-solid fa-lock me-2"></i>Ubah Password</a>
                            </li> --}}
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
            <a href="{{ route('dashboard') }}" class="brand-link d-flex align-items-center border-0">
                <div class="d-flex justify-content-center align-items-center abjad me-2">
                    <h3 class="black fw__med">S</h3>
                </div>
                <div class="name brand-text text-white">
                    <h6 class="fw-semibold mb-1">SIMAS</h6>
                    <h6 id="brandText" class="fw__normal">
                        Sistem Manajemen Surat
                    </h6>
                </div>
            </a>
            {{-- Sidebar brand end --}}

            {{-- Sidebar content start --}}
            <div id="sidebarMenu" class="sidebar">
                <nav class="mt-5">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item nav-link mb-2 text-secondary pb-0" style="font-size: 14px">
                            MENU
                        </li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('dashboard') }}" class="nav-link @yield('db')">
                                <i class="fa-solid fa-house me-2"></i>
                                <p class="fw-normal">Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="{{ route('suratMasuk') }}" class="nav-link @yield('sm')">
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
                            ADMINISTRATOR
                        </li>
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
        <div id="mainPage" class="content-wrapper border-0">
            @yield('content')
        </div>
        {{-- Main section end --}}

        {{-- Footer start --}}
        <footer class="main-footer px-4 py-3">
            Copyright &copy; 2023. <b> Digital Innovation & Media.</b>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>
        {{-- Footer end --}}
    </div>

    <!-- Template : AdminLTE App -->
    <script src="js/adminlte.js"></script>

    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>

    <!-- Fontawesome Icons -->
    <script src="js/all.min.js"></script>

    <!-- Custom JS -->
    <script src="js/script.js"></script>
    @yield('js')
</body>

</html>