@extends('template')
@section('content')
<section class="content">
    {{-- @dd($user) --}}
    {{-- Main section start --}}
    <div class="dashboard">
        {{-- Info pengguna start --}}
        <div class="mb-3">
            <div class="d-flex align-items-start">
                <h3 class="black fw-bold me-2">Selamat datang, {{ $user->roleTabel->nama }}!</h3>
                <img src="img/hand-icon.png" width="24px" alt="Hand Icon" />
            </div>
            <h5 class="black fw-normal mt-2">
                Silahkan kelola surat sesuai kebutuhan anda.
            </h5>
        </div>
        {{-- Info pengguna end --}}

        {{-- Jumlah surat start --}}
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
        {{-- Jumlah surat end --}}
    </div>
    {{-- Main section end --}}
</section>
@endsection
@section('js')
{{-- Sweet alert start --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script src="sweetalert2.all.min.js"></script> --}}
{{-- Sweet alert end --}}

{{-- Fungsi menampilkan login berhasil start --}}
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
</script>
{{-- Fungsi menampilkan login berhasil end --}}

{{-- Cek pertama kali login start --}}
@if ($message = Session::get('success'))
<script>
    berhasil("{{ Session::get('success') }}")
</script>
{{-- Cek pertama kali login end --}}

@endif
@endsection
@section('db', 'active')
@section('title', 'Dashboard')