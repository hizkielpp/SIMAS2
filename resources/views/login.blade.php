<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Masuk</title>
        <link
            rel="icon"
            type="image/x-icon"
            href="{{ asset('img/favicon.png') }}"
        />

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css" />

        <!-- Fontawesome Icons -->
        <link rel="stylesheet" href="css/all.min.css" />

        <!-- Custom Style -->
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/login-style.css" />
    </head>

    <body>
        <div class="login d-inline">
            {{-- Hero start --}}
            <section
                class="image d-none flex-column justify-content-center align-items-center"
            >
                <img src="img/login-image-3x.png" alt="Gambar Login" />
                <h3 class="fw__bold" style="color: #2f5596">
                    Sistem Manajemen Surat (SIMAS)
                </h3>
                <h4
                    class="fw__normal text-center mt-2"
                    style="color: #2f5596; line-height: 1"
                >
                    Fakultas Kedokteran Universitas Diponegoro
                </h4>
            </section>
            {{-- Hero end --}}

            {{-- Content start --}}
            <section
                class="content d-flex justify-content-center align-items-center"
            >
                <div class="body">
                    {{-- Brand start --}}
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
                    {{-- Brand end --}}

                    {{-- Main section start --}}
                    <div class="title mb-3">
                        <h1 class="text-white fw-semibold mb-3">
                            Selamat Datang!
                        </h1>
                        <h3
                            class="text-white fw-normal"
                            style="font-size: 18px; line-height: 1.5"
                        >
                            Silahkan masuk dengan akun sesuai prodi masing
                            -masing
                        </h3>

                        {{-- Alert login failed start --}}
                        @if (session()->has('loginFailed'))
                        <div class="feedback p-3 bg-white mt-3 rounded-2">
                            <h5 class="text-danger">
                                <span class="fw-bold">{{
                                    session("loginFailed")
                                }}</span>
                                Email atau password salah.
                            </h5>
                        </div>
                        @endif @if ($errors->any())
                        <div class="feedback p-3 bg-white mt-3 rounded-2">
                            <h5 class="text-danger">
                                <span class="fw-bold">Login gagal!</span> email
                                atau password salah.
                            </h5>
                        </div>
                        @endif
                        {{-- Alert login failed end --}}
                    </div>
                    <form
                        method="POST"
                        action="{{ route('login.custom') }}"
                        class="myform"
                    >
                        @csrf
                        <div class="mb-3 mt-4">
                            <label for="email" class="form-label"
                                >Username</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="email"
                                placeholder="Masukkan email atau NIP"
                                aria-describedby="emailHelp"
                                name="email"
                                autofocus
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"
                                >Password</label
                            >
                            <div class="position-relative icon">
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    placeholder="Masukkan password"
                                    name="password"
                                    required
                                />
                                <i
                                    class="fa-solid fa-eye position-absolute"
                                    onclick="showPass()"
                                    id="passIcon"
                                ></i>
                            </div>
                        </div>
                        <button
                            type="submit"
                            class="btn btn__blue w-100 black fw-semibold mt-4"
                        >
                            Masuk
                        </button>
                    </form>
                    {{-- Main section end --}}
                </div>
            </section>
            {{-- Content end --}}
        </div>

        <!-- Bootstrap JS -->
        <script src="js/bootstrap.bundle.min.js"></script>

        <!-- Fontawesome Icons -->
        <script src="js/all.min.js"></script>

        <!-- Show/hide password -->
        <script>
            function showPass() {
                var input = document.getElementById("password");
                var icon = document.getElementById("passIcon");
                if (input.type === "password") {
                    input.type = "text";
                    icon.style.color = "#fff";
                } else {
                    input.type = "password";
                    icon.style.color = "#000";
                }
            }
        </script>

        <!-- Sweet alert -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Sweet alert : login gagal -->
        <script>
            function loginCallback() {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Username atau kata sandi salah. Silahkan periksa dan coba lagi!",
                });
            }
        </script>
    </body>
</html>
