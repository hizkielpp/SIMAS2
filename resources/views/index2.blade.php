@extends('template')
@section('content')
    <section class="content">
        <div class="dashboard row">

            {{-- Data surat start --}}
            <div class="col-lg-8 col-12">
                <div class="d-flex align-items-start">
                    <h2 class="black fw__bold me-2">Selamat Datang, Admin!</h2>
                    <img src="img/hand-icon.png" width="24px" alt="Hand Icon" />
                </div>
                <h4 class="black fw__light mt-2">
                    Silahkan kelola surat sesuai kebutuhan anda.
                </h4>
                <div class="row mt-4">
                    <div class="col-sm-6 col-xl-4 col-12">
                        <div class="card surat justify-content-center px-3 py-4 mb-3 mb-xl-0">
                            <div class="d-flex align-items-center masuk">
                                <div class="icon d-flex justify-content-center align-items-center">
                                    <i class="fa-solid fa-envelope masuk"></i>
                                </div>
                                <div class="text">
                                    <h4 class="black fw__normal mb-1">Surat Masuk</h4>
                                    <h4 class="black fw__ebold">{{ $jumlahSM }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4 col-12">
                        <div class="card surat justify-content-center px-3 py-4 mb-3 mb-xl-0">
                            <div class="d-flex align-items-center keluar">
                                <div class="icon d-flex justify-content-center align-items-center">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div class="text">
                                    <h4 class="black fw__normal mb-1">Surat Keluar</h4>
                                    <h4 class="black fw__ebold">{{ $jumlahSK }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-4 col-12">
                        <div class="card surat justify-content-center px-3 py-4 mb-3 mb-xl-0">
                            <div class="d-flex align-items-center antidatir">
                                <div class="icon d-flex justify-content-center align-items-center">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div class="text">
                                    <h4 class="black fw__normal mb-1">Surat Antidatir</h4>
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
                <div class="card p-4 informasi h-100 mb-0 justify-content-between">
                    <h3 class="text-white">Informasi Terbaru</h3>
                    <h4 class="light fw__light mt-3">
                        Terdapat {{ $jumlahSM }} surat masuk, {{ $jumlahSK }} surat keluar, dan {{ $jumlahSA }}
                        antidatir baru.
                    </h4>
                    <h5 class="time light">{{ date('D d M Y', strtotime($date)) }}</h5>
                </div>
            </div>
            {{-- Informasi terbaru end --}}

        </div>
    </section>
@endsection
@section('db', 'active')
@section('title', 'Dashboard')
