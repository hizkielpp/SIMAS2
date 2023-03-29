<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin | @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />

    <!-- Fontawesome Icons -->
    <link rel="stylesheet" href="css/all.min.css" />

    <!-- Template : overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />

    <!-- Template : theme style -->
    <link rel="stylesheet" href="css/adminlte.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/beranda-style.css" />
    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        {{-- Preloader start --}}
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="img/AdminLTELogo.png" alt="AdminLTELogo" height="60"
                width="60" />
        </div>
        {{-- Preloader end --}}

        {{-- Navbar start --}}
        <nav id="navbar" class="main-header navbar navbar-expand navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item d-flex align-items-center user">
                    <div class="image__user d-flex justify-content-center align-items-center">
                        <h3 class="text-white fw__med">A</h3>
                    </div>
                    <div class="dropdown">
                        <button class="border-0 bg-transparent dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Admin <i class="fa-solid fa-angle-down ms-1"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="profil-pengguna.html"><i
                                        class="fa-solid fa-user me-2"></i>Profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="ubah-password.html"><i
                                        class="fa-solid fa-lock me-2"></i>Ubah Password</a>
                            </li>
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
            <a href="{{ route('dashboard') }}" class="brand-link d-flex align-items-center">
                <div class="d-flex justify-content-center align-items-center mr12">
                    <h3 class="text-white fw__med">S</h3>
                </div>
                <div class="name brand-text">
                    <h5 class="black fw__ebold mb-1">SIMAS</h5>
                    <h5 id="brandText" class="black fw__normal">
                        Sistem Manajemen Surat
                    </h5>
                </div>
            </a>
            {{-- Sidebar brand end --}}

            {{-- Sidebar content start --}}
            <div id="sidebarMenu" class="sidebar">
                <nav class="mt-5">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link @yield('db')">
                                <i class="fa-solid fa-house me-1"></i>
                                <p class="fw__light">Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('suratMasuk') }}" class="nav-link @yield('sm')">
                                <i class="fa-solid fa-envelope me-1"></i>
                                <p class="fw__light">Surat Masuk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('suratKeluar') }}" class="nav-link @yield('sk')">
                                <i class="fa-solid fa-envelope me-1"></i>
                                <p class="fw__light">Surat Keluar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('suratAntidatir') }}" class="nav-link @yield('sa')">
                                <i class="fa-solid fa-envelope me-1"></i>
                                <p class="fw__light">Surat Antidatir</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kelolaAkun') }}" class="nav-link @yield('ka')">
                                <i class="fa-solid fa-user-gear me-1"></i>
                                <p class="fw__light">Kelola Akun</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            {{-- Sidebar content end --}}

        </aside>
        {{-- Sidebar container end --}}

        {{-- Main section start --}}
        <div id="mainPage" class="content-wrapper">
            @yield('content')
        </div>
        {{-- Main section end --}}

        {{-- Footer start --}}
        <footer class="main-footer px-4 py-3">
            <strong>Copyright &copy; 2023. Digital Innovation & Media.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0
            </div>
        </footer>
        {{-- Footer end --}}
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Template : overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

    <!-- Template : AdminLTE App -->
    <script src="js/adminlte.js"></script>

    <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>

    <!-- Fontawesome Icons -->
    <script src="js/all.min.js"></script>

    <!-- Custom JS -->
    <script src="js/script.js"></script>
    @yield('js')

    <!-- Initializing date picker : rentang start -->
    <script>
        $(function() {
            $("#datepickerStart").datepicker();
        });
    </script>

    <!-- Initializing date picker : rentang end -->
    <script>
        $(function() {
            $("#datepickerEnd").datepicker();
        });
    </script>
</body>

</html>