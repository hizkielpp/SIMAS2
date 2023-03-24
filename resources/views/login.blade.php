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

    <!-- Custom Style -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/login-style.css" />
  </head>

  <body>
    <div class="login">
      <div class="image d-none flex-column justify-content-center align-items-center">
        <img src="img/login-image-3x.png" alt="Gambar Login" />
        <h3 class="blue fw__bold">Sistem Manajemen Surat (SIMAS)</h3>
        <h4 class="blue fw__normal text-center mt-2">Aplikasi Pengelola Surat Masuk dan Keluar Fakultas Kedokteran</h4>
      </div>
      <div class="content d-flex justify-content-center align-items-center">
        <div class="body">
          <div class="d-flex align-items-center brand mb-5">
            <img
              src="img/logo-fk.png"
              alt="Logo Fakultas Kedokteran"
              width="46px"
            />
            <h4 class="text-white fw__light ms-2">
              Fakultas Kedokteran <br />
              Universitas Diponegoro
            </h4>
          </div>
          <div class="title mb-5">
            <h1 class="text-white fw__bold mb-3">Selamat Datang!</h1>
            <h3 class="text-white fw__light">
              Silahkan masuk dengan akun sesuai prodi masing -masing
            </h3>
          </div>
          <form method="POST" action="{{ route('login.custom') }}">
          @csrf
            <div class="mb-3 mt-4">
              <label for="email" class="form-label">Username</label>
              <input
                type="email"
                class="form-control"
                id="email"
                aria-describedby="emailHelp"
                name="email"
                autofocus
              />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input
                type="password"
                class="form-control"
                id="password"
                name="password"
              />
            </div>
            <button type="submit" class="btn btn__blue w-100 fw__bold mt-4">
              Masuk
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Bootstrap -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
