@extends('template')
@section('content')
    <section class="content">
        <div class="dashboard row g-3">

            {{-- Data surat start --}}
            <div class="col-lg-8 col-12">
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
            {{-- Data surat end --}}

            {{-- Informasi terbaru start --}}
            <div class="col-lg-4 col-12">
                <div class="card p-4 informasi h-100 flex-column justify-content-between">
                    <div class="">
                        <h3 class="text-white">Informasi Terbaru</h3>
                        <h5 class="light fw__light mt-2">
                            Terdapat {{ $jumlahSM }} surat masuk, {{ $jumlahSK }} surat keluar, dan
                            {{ $jumlahSA }}
                            antidatir baru.
                        </h5>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="time light">{{ date('D d M Y', strtotime($date)) }}</h5>
                        <a href="{{ route('suratMasuk') }}" class="mybtn white__reverse">Periksa</a>
                    </div>
                </div>
            </div>
            {{-- Informasi terbaru end --}}

        </div>
    </section>


@endsection
@section('js')

    {{-- Sweet alert start --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    {{-- Sweet alert end --}}
    {{-- script tambahan untuk menangkap session --}}
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
