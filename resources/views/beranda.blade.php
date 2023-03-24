<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Masuk</title>

    <!-- Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi"
      crossorigin="anonymous"
    />

    <!-- Fontawesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    />

    <!-- Custom Style -->
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="beranda-style-old.css" />
  </head>

  <body>
    <!-- Navbar -->
    <nav
      id="navbar"
      class="d-flex justify-content-between align-items-center p26"
    >
      <button id="hamburger" class="btn">
        <i class="fa-solid fa-bars"></i>
      </button>
      <div class="nav__item d-flex">
        <i class="fa-solid fa-bell me-3"></i>
        <h4>Admin</h4>
      </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar px-4">
      <div class="brand d-flex align-items-center py-4">
        <div class="d-flex justify-content-center align-items-center mr12">
          <h3 class="text-white fw__med">S</h3>
        </div>
        <div id="brandText" class="name">
          <h5 class="black fw__ebold mb-1">SIMAS</h5>
          <h5 class="black fw__normal">Sistem Manajemen Surat</h5>
        </div>
      </div>
      <ul class="ps-0 mt-3 nav__item">
        <li>
          <a href="" class="active"
            ><div class="d-flex align-items-center">
              <i class="fa-solid fa-house"></i>
              <h5 class="text fw__light">Dashboard</h5>
            </div>
          </a>
        </li>
        <li>
          <a href=""
            ><div class="d-flex align-items-center">
              <i class="fa-solid fa-envelope"></i>
              <h5 class="text fw__light">Surat Masuk</h5>
            </div>
          </a>
        </li>
        <li>
          <a href=""
            ><div class="d-flex align-items-center">
              <i class="fa-solid fa-envelope"></i>
              <h5 class="text fw__light">Surat Keluar</h5>
            </div>
          </a>
        </li>
        <li>
          <a href=""
            ><div class="d-flex align-items-center">
              <i class="fa-solid fa-envelope"></i>
              <h5 id="text" class="text fw__light">Surat Antidatir</h5>
            </div>
          </a>
        </li>
      </ul>
    </div>

    <!-- Main page -->
    <div id="main" class="main__page p-3">tes</div>

    <!-- Footer -->
    <footer></footer>

    <!-- Custom js -->
    <script src="script.js"></script>

    <!-- Bootstrap -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
