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
    <!-- overlayScrollbars -->
    <link
      rel="stylesheet"
      href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css"
    />
    <!-- Theme style -->
    <link rel="stylesheet" href="css/adminlte.min.css" />
    <!-- Custom css -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/beranda-style.css" />
    @yield('css')
  </head>
  <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
      <!-- Preloader -->
      <div
        class="preloader flex-column justify-content-center align-items-center"
      >
        <img
          class="animation__wobble"
          src="img/AdminLTELogo.png"
          alt="AdminLTELogo"
          height="60"
          width="60"
        />
      </div>

      <!-- Navbar -->
      <nav id="navbar" class="main-header navbar navbar-expand navbar-dark">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"
              ><i class="fas fa-bars" ></i
            ></a>
          </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <!-- <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <i class="fas fa-expand-arrows-alt"></i>
            </a>
          </li> -->
          <li class="nav-item">
            <div class="icon d-flex justify-content-center align-items-center">
              <i class="fa-solid fa-bell black"></i>
            </div>
          </li>
          <li class="nav-item d-flex align-items-center user">
            <div
              class="image__user d-flex justify-content-center align-items-center"
            >
              <h3 class="text-white fw__med">A</h3>
            </div>
            <div class="dropdown">
              <button
                class="border-0 bg-transparent dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Admin <i class="fa-solid fa-angle-down ms-1"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('logout') }}">Keluar</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside id="sidebar" class="main-sidebar sidebar-dark-primary">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="brand-link d-flex align-items-center">
          <div class="d-flex justify-content-center align-items-center mr12">
            <h3 class="text-white fw__med">S</h3>
          </div>
          <div class="name brand-text">
            <h5 class="black fw__ebold mb-1" >SIMAS</h5>
            <h5 id="brandText" class="black fw__normal">
              Sistem Manajemen Surat
            </h5>
          </div>
        </a>

        <!-- Sidebar -->
        <div id="sidebarMenu" class="sidebar">
          <!-- Sidebar Menu -->
          <nav class="mt-5">
            <ul
              class="nav nav-pills nav-sidebar flex-column"
              data-widget="treeview"
              role="menu"
              data-accordion="false"
            >
              <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
              <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link @yield('db')" >
                  <i class="fa-solid fa-house me-1" ></i>
                  <p class="fw__light" >Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('suratMasuk') }}" class="nav-link @yield('sm')" >
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
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div id="mainPage" class="content-wrapper">
        @yield('content')
      </div>

      <!-- Main Footer -->
      <!-- <footer class="main-footer">
        <strong
          >Copyright &copy; 2014-2021
          <a href="https://adminlte.io">AdminLTE.io</a>.</strong
        >
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 3.2.0
        </div>
      </footer> -->
    </div>
    <!-- ./wrapper -->
        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <script>
          $(function () {
            $("#datepickerStart").datepicker();
          });
        </script>
    
        <!-- Initializing date picker : rentang end -->
        <script>
          $(function () {
            $("#datepickerEnd").datepicker();
          });
        </script>

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
{{-- 
    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/adminlte.js"></script>

    <!-- Bootstrap CDN -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
    <!-- PAGE PLUGINS -->

    <!-- AdminLTE for demo purposes -->
    <script src="js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="js/pages/dashboard2.js"></script>

    <script src="js/script.js"></script> --}}
    @yield('js')

  </body>
</html>
