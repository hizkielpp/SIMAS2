@extends('template') @section('content')
    <section class="content">
        {{-- Cek apakah semua surat telah memiliki arsip start --}}
        @php
            $semuaSudahArsip = true; // Inisialisasi dengan true
            $jumlah = 0;
            foreach ($suratKeluar as $item) {
                if ($item->sifatSurat != 4 && !$item->lampiran && $item->status == 'digunakan' && $item->created_by == $user->nip) {
                    // Jika ada surat yang belum memiliki arsip, ubah status menjadi false
                    $semuaSudahArsip = false;
                    $jumlah++;
                }
            }
        @endphp
        {{-- Cek apakah semua surat telah memiliki arsip end --}}

        {{-- Main section start --}}
        <div class="d-flex gap-3 flex-wrap flex-xl-nowrap dashboard">
            <div class="" style="flex: 1 1 70%">
                {{-- Info pengguna start --}}
                <div class="mb-3">
                    <div class="d-flex align-items-start">
                        <h3 class="black fw-bold me-2">
                            Selamat datang, {{ $user->roleTabel->nama }}!
                        </h3>
                        <img src="{{ asset('img/hand-icon.png') }}" width="24px" alt="Hand Icon" />
                    </div>
                    <h5 class="black fw-normal mt-2">
                        Silahkan kelola surat sesuai kebutuhan anda.
                    </h5>
                </div>
                {{-- Info pengguna end --}}
                {{-- Jumlah surat start --}}
                <div class="row g-3">
                    {{-- Admin dan pimpinan start --}}
                    @if ($user->role_id !== 2)
                        <div class="col-sm-6 col-xl-4 col-12">
                            <div class="card surat justify-content-center py-2 px-4">
                                <div class="d-flex flex-wrap gap-3 align-items-center masuk">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <i class="fa-solid fa-envelope masuk"></i>
                                    </div>
                                    <div class="text">
                                        <h5 class="black__light fw__normal mb-2">
                                            Surat Masuk
                                        </h5>
                                        <h4 id="jumlahSm" class="black fw__ebold"></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Admin dan pimpinan end --}}

                    {{-- Admin dan pimpinan start --}}
                    @if ($user->role_id !== 2)
                        <div class="col-sm-6 col-xl-4 col-12">
                            <div class="card position-relative surat justify-content-center py-2 px-4">
                                <div class="d-flex flex-wrap gap-3 align-items-center keluar">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div class="text">
                                        <h5 class="black__light fw__normal mb-2">
                                            Surat Keluar
                                        </h5>
                                        <h4 id="jumlahSk" class="black fw__ebold">
                                        </h4>
                                    </div>
                                </div>
                                <span class="fw-normal ms-2 position-absolute"
                                    style="font-size: 16px">(+{{ $SKToday }})
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-4 col-12">
                            <div class="card position-relative surat justify-content-center py-2 px-4">
                                <div class="d-flex flex-wrap gap-3 align-items-center antidatir">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div class="text">
                                        <h5 class="black__light fw__normal mb-2">
                                            Surat Antidatir
                                        </h5>
                                        <h4 id="jumlahSa" class="black fw__ebold"></h4>
                                    </div>
                                </div>
                                {{-- <span class="fw-normal ms-2 position-absolute"
                                    style="font-size: 16px">(+{{ $SAToday }})
                                </span> --}}
                            </div>
                        </div>
                        {{-- Admin dan pimpinan end --}}
                    @else
                        {{-- Operator start --}}
                        <div class="col-sm-6 col-12">
                            <div class="card position-relative surat justify-content-center py-2 px-4">
                                <div class="d-flex flex-wrap gap-3 align-items-center keluar">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div class="text">
                                        <h5 class="black__light fw__normal mb-2">
                                            Surat Keluar
                                        </h5>
                                        <h4 id="jumlahSk" class="black fw__ebold"></h4>
                                    </div>
                                </div>
                                <span class="fw-normal ms-2 position-absolute"
                                    style="font-size: 16px">(+{{ $SKToday }})</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="card position-relative surat justify-content-center py-2 px-4">
                                <div class="d-flex flex-wrap gap-3 align-items-center antidatir">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div class="text">
                                        <h5 class="black__light fw__normal mb-2">
                                            Surat Antidatir
                                        </h5>
                                        <h4 id="jumlahSa" class="black fw__ebold"></h4>
                                    </div>
                                </div>
                                {{-- <span class="fw-normal ms-2 position-absolute"
                                    style="font-size: 16px">(+{{ $SAToday }})
                                </span> --}}
                            </div>
                        </div>
                        {{-- Operator end --}}
                    @endif
                </div>
                {{-- Jumlah surat end --}}
            </div>
            {{-- Keterangan start --}}
            <div class="card p-4 mb-md-0 keterangan position-relative"
                style="
                background-color: #2f5596;
                flex: 1 1 30%;
                height: fit-content;
            ">
                <img src="{{ asset('img/download-icon-1.png') }}" width="100%" class="position-absolute image-1"
                    style="max-width: 180px; top: 0; right: 6px" alt="Icon" />
                <img src="{{ asset('img/download-icon-2.png') }}" width="100%" class="position-absolute image-2"
                    style="max-width: 140px; bottom: 0; right: 0" alt="Icon" />
                <h5 class="fw-semibold text-white mb-2">
                    <i class="fa-solid fa-circle-info me-2"></i>Info
                </h5>
                <h5 class="fw-normal light mb-3" style="line-height: 1.5">
                    Pedoman Tata Naskah Dinas di Lingkungan Universitas Diponegoro
                </h5>
                <a href="{{ route('downloadNaskah') }}" class="mybtn white bg-white fw-medium"><i
                        class="fa-solid fa-download me-1"></i>
                    Download
                </a>
            </div>
            {{-- Keterangan start --}}
        </div>
        {{-- Main section end --}}

        {{-- Alert warning start --}}
        @if (!$semuaSudahArsip)
            <div class="alert alert-warning gap-2 d-flex align-items-start mt-4" role="alert"
                style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    <span class="fw-semibold">Perhatian!</span>
                    <h5 class="mt-1 fw-normal" style="line-height: 1.7">
                        Anda belum mengupload sebanyak <b>{{ $jumlah }}</b> arsip pada surat yang telah
                        dibuat. <br>
                        Mohon
                        untuk
                        mengupload arsip surat tersebut untuk dapat kembali mengambil nomor surat keluar. Terima kasih.
                    </h5>
                </div>
            </div>
        @endif
        {{-- Alert warning end --}}

    </section>
    @endsection @section('js')
    {{-- Sweet alert start --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Sweet alert end --}}

    {{-- Format jumlah surat start --}}
    <script>
        const wrapperSm = document.getElementById('jumlahSm')
        const wrapperSk = document.getElementById('jumlahSk')
        const wrapperSa = document.getElementById('jumlahSa')
        const jumlahSuratMasuk = `{{ $jumlahSM }}`;
        const jumlahSuratKeluar = `{{ $jumlahSK }}`;
        const jumlahSuratAntidatir = `{{ $jumlahSA }}`;
        const formatedSuratMasuk = jumlahSuratMasuk.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        const formatedSuratKeluar = jumlahSuratKeluar.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        const formatedSuratAntidatir = jumlahSuratAntidatir.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        wrapperSm.innerText = `${formatedSuratMasuk}`
        wrapperSk.innerText = `${formatedSuratKeluar}`
        wrapperSa.innerText = `${formatedSuratAntidatir}`
    </script>
    {{-- Format jumlah surat start --}}

    {{-- Fungsi menampilkan login berhasil start --}}
    <script>
        function berhasil(txt) {
            new Audio("audio/success-edited.mp3").play();
            // Swal.fire("Berhasil!", `${txt}`, "success");
            Swal.fire({
                confirmButtonColor: "#2F5596",
                icon: "success",
                title: `Login berhasil!`,
                text: `${txt}`,
            });
        }
    </script>
    {{-- Fungsi menampilkan login berhasil end --}}

    {{-- Cek pertama kali login start --}}
    @if ($message = Session::get('success'))
        <script>
            berhasil("{{ Session::get('success') }}");
        </script>
        {{-- Cek pertama kali login end --}}
    @endif @endsection @section('db', 'active') @section('title', 'Dashboard')
