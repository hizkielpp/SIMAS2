@extends('template')
@section('content')
    <section class="content">
        {{-- Main section start --}}
        <div class="dashboard">
            <div class="d-flex align-items-start">
                <h3 class="black fw-bold me-2">Selamat, Admin!</h3>
                <img src="img/hand-icon.png" width="24px" alt="Hand Icon" />
            </div>
            <h5 class="black fw-normal mt-2 mb-3">
                Silahkan kelola surat sesuai kebutuhan anda.
            </h5>
            <div class="row g-3">
                <div class="col-sm-6 col-xl-4 col-12">
                    <div class="card surat justify-content-center py-2 px-4">
                        <div class="d-flex flex-wrap gap-3 align-items-center masuk">
                            <div class="icon d-flex justify-content-center align-items-center">
                                <i class="fa-solid fa-envelope masuk"></i>
                            </div>
                            <div class="text">
                                <h5 class="black__light fw__normal mb-2">Surat Masuk</h5>
                                <h4 class="black fw__ebold">{{ $jumlahSM }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4 col-12">
                    <div class="card surat justify-content-center py-2 px-4">
                        <div class="d-flex flex-wrap gap-3 align-items-center keluar">
                            <div class="icon d-flex justify-content-center align-items-center">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="text">
                                <h5 class="black__light fw__normal mb-2">Surat Keluar</h5>
                                <h4 class="black fw__ebold">{{ $jumlahSK }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4 col-12">
                    <div class="card surat justify-content-center py-2 px-4">
                        <div class="d-flex flex-wrap gap-3 align-items-center antidatir">
                            <div class="icon d-flex justify-content-center align-items-center">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="text">
                                <h5 class="black__light fw__normal mb-2">Surat Antidatir</h5>
                                <h4 class="black fw__ebold">{{ $jumlahSA }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Main section end --}}
    </section>
@endsection
@section('js')

    {{-- Sweet alert start --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    {{-- Sweet alert end --}}

    {{-- Script tambahan untuk menangkap session start --}}
    <script>
        function berhasil(txt) {
            new Audio("audio/success-edited.mp3").play();
            // Swal.fire("Berhasil!", `${txt}`, "success");
            Swal.fire({
                confirmButtonColor: "#2F5596",
                icon: 'success',
                title: `Login berhasil!`,
                text: `${txt}`,
            })
        }

        function gagal(txt) {
            new Audio("audio/cancel-edited.mp3").play();
            Swal.fire({
                confirmButtonColor: "#2F5596",
                icon: 'error',
                title: 'Gagal!',
                text: `Data gagal ditambahkan! pesan error : ${txt}`,
            })
        }
    </script>
    {{-- Script tambahan untuk menangkap session end --}}

    @if ($message = Session::get('success'))
        <script>
            berhasil("{{ Session::get('success') }}")
        </script>
        </div>
    @endif
    @if ($message = Session::get('failed'))
        <script>
            gagal("{{ Session::get('failed') }}")
        </script>
        </div>
    @endif
    @if ($errors->any())
        <script>
            gagal()
        </script>
        {{ implode('', $errors->all('<div>:message</div>')) }}
    @endif
@endsection
@section('db', 'active')
@section('title', 'Dashboard')
