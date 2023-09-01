@extends('template')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap data tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />

    <!-- Datepicker Jquery -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    {{-- Fungsi rentang tanggal start --}}
    <script>
        $(document).ready(function() {
            $(function() {
                // Initializing
                $("#inputTanggalStart").datepicker()
                // Ganti tahun
                $("#inputTanggalStart").datepicker("option", "changeYear", true);
                // Ganti format tanggal
                $("#inputTanggalStart").datepicker("option", "dateFormat", "dd-mm-yy")
                if (start) {
                    $("#inputTanggalStart").datepicker("setDate", `${start}`)
                    $("#inputTanggalStart").attr('value', start)
                }
            });
            $(function() {
                // Initializing
                $("#inputTanggalEnd").datepicker()
                // Ganti tahun
                $("#inputTanggalEnd").datepicker("option", "changeYear", true);
                // Ganti format tanggal
                $("#inputTanggalEnd").datepicker("option", "dateFormat", "dd-mm-yy");
                if (end) {
                    $("#inputTanggalEnd").datepicker("setDate", `${end}`)
                    $("#inputTanggalEnd").attr('value', end)
                }
            });
        });
    </script>
    @if (isset($_GET['start']) and isset($_GET['end']))
        <script>
            let start = "{{ $_GET['start'] }}"
            let end = "{{ $_GET['end'] }}"
        </script>
    @else
        <script>
            let start = ""
            let end = ""
        </script>
    @endif
    {{-- Fungsi rentang tanggal start --}}

    {{-- Datepicker Jquery : registrasi surat --}}
    <script>
        $(function() {
            // Initializing
            $("#datepicker").datepicker();

            // Ganti tahun
            $("#datepicker").datepicker("option", "changeYear", true);
            // Ganti format tanggal
            $("#datepicker").datepicker("option", "dateFormat", "dd-mm-yy");
            // Periksa apakah ada nilai lama (old value) dari server
            let oldDate = "{{ old('tanggalPengajuan') }}";
            if (oldDate) {
                // Tetapkan nilai Datepicker dari old value
                $("#datepicker").datepicker("setDate", oldDate);
            }
        });
    </script>

    {{-- Datepicker Jquery : edit surat --}}
    <script>
        $(function() {
            // Initializing
            $("#tanggalPengajuanE").datepicker();

            // Ganti tahun
            $("#tanggalPengajuanE").datepicker("option", "changeYear", true);
            // Ganti format tanggal
            $("#tanggalPengajuanE").datepicker("option", "dateFormat", "dd-mm-yy");
        });
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/surat-masuk-style.css" />
@endsection
@section('content')
    <section class="surat__masuk content">
        {{-- Navigation start --}}
        <div class="navigation__content mb-4">
            <h5 class="fw__semi black">SURAT MASUK</h5>
        </div>
        {{-- Navigation end --}}

        {{-- Alert gagal menambahkan surat start --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong id="headerGagal">Aksi gagal!</strong>
                <p class="mt-2">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Alert gagal menambahkan surat end --}}

        {{-- Alert edit surat sama dengan database start --}}
        @if (session()->has('editFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong id="headerGagal">Aksi gagal!</strong>
                <p class="mt-2">
                    {{ session('editFailed') }}
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Alert edit surat sama dengan database end --}}

        {{-- Tabel wrapper start --}}
        <div class="card p-4 mt-3">
            {{-- Tabel header start --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <h4 class="fw-semibold black">Daftar Surat Masuk</h4>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <p class="">Rentang Tanggal :</p>
                    <div class="input__tanggal position-relative">
                        <input type="text" name="inputTanggal" placeholder="Batas awal" id="inputTanggalStart"
                            class="mybtn" />
                        <i class="fa-solid fa-calendar-days position-absolute"></i>
                    </div>
                    <div class="input__tanggal position-relative">
                        <input type="text" name="inputTanggalEnd" placeholder="Batas akhir" id="inputTanggalEnd"
                            class="mybtn" />
                        <i class="fa-solid fa-calendar-days position-absolute"></i>
                    </div>
                    @if ($user->role_id != 3)
                        <button id="btnReg" type="button" data-bs-toggle="modal" data-bs-target="#registrasiSuratMasuk"
                            class="mybtn blue">
                            <i class="fa-solid fa-plus me-2"></i>Registrasi Surat
                        </button>
                    @endif
                </div>
            </div>
            {{-- Tabel header end --}}

            <!-- Modal lampiran surat start -->
            <div class="modal modal__section fade" data-bs-backdrop="static" id="lampiran" tabindex="-1"
                aria-labelledby="lampiranLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl h-100">
                    <div class="modal-content modal-xl h-100">
                        <div class="modal-header">
                            <h4 class="modal-title fw-semibold black" id="lampiranLabel">Lampiran Surat</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="example1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal lampiran surat end -->

            <!-- Modal registrasi start -->
            <div class="modal modal__section fade" data-bs-backdrop="static" id="registrasiSuratMasuk"
                data-bs-backdrop="static" tabindex="-1" aria-labelledby="ex ampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content p-3">
                        <div class="modal-header">
                            <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                                Form Registrasi Surat Masuk
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formRegistrasi" class="needs-validation" novalidate method="POST"
                                action="{{ route('inputSM') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="asalSurat" class="form-label black fw-normal">Asal Surat</label>
                                            <input type="text" class="form-control" id="asalSurat" name="asalSurat"
                                                placeholder="Contoh : Ketua Departemen Kedokteran"
                                                aria-describedby="emailHelp" value="{{ old('asalSurat') }}" required />
                                            <div class="invalid-feedback">
                                                Masukkan asal surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="noSurat" class="form-label black fw-normal">Nomor Surat</label>
                                            <input type="text" class="form-control"
                                                placeholder="Contoh : 1/UN7.F4/I/2023" id="noSurat" name="nomorSurat"
                                                aria-describedby="emailHelp" required />
                                            <div class="invalid-feedback">
                                                Masukkan nomor surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date" class="form-label black fw-normal">Tanggal
                                                Surat</label>
                                            <div class="position-relative input__tanggal__form">
                                                <input type="text" id="datepicker" identifier="date"
                                                    placeholder="..." name="tanggalPengajuan" aria-placeholder="coba"
                                                    class="form-control" value="{{ old('tanggalPengajuan') }}" required>
                                                <i class="fa-solid fa-calendar-days position-absolute"></i>
                                            </div>
                                            <div class="invalid-feedback">
                                                Masukkan tanggal pengajuan dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="penerima" class="form-label black fw-normal">Penerima</label>
                                            <select class="form-select" required aria-label="Default select example"
                                                name="tujuanSurat">
                                                <option selected disabled value="">...</option>
                                                @foreach ($tujuan as $k => $v)
                                                    @if (old('tujuanSurat') == $v->kode)
                                                        <option value="{{ $v->kode }}" selected>{{ $v->nama }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $v->kode }}">{{ $v->nama }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Masukkan penerima surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="penerima" class="form-label black fw-normal">Sifat</label>
                                            <select class="form-select" aria-label="Default select example" required
                                                name="sifatSurat">
                                                <option selected disabled value="">...</option>
                                                @foreach ($sifat as $k => $v)
                                                    @if (old('sifatSurat') == $v->kode)
                                                        <option value="{{ $v->kode }}" selected>{{ $v->nama }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Masukkan sifat surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kodeHal" class="form-label black fw-normal">Kode Hal</label>
                                            <select class="form-select" required aria-label="Default select example"
                                                id="kodeHal" name="kodeHal">
                                                <option selected disabled value="">...</option>
                                                @foreach ($hal as $k => $v)
                                                    @if (old('kodeHal') == $v->kode)
                                                        <option value="{{ $v->kode }}" selected>{{ $v->nama }}
                                                            ({{ $v->kode }})
                                                        </option>
                                                    @else
                                                        <option value="{{ $v->kode }}">{{ $v->nama }}
                                                            ({{ $v->kode }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Masukkan kode hal surat dengan benar.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="lampiran" class="form-label black fw-normal">Upload
                                                Arsip Surat</label>
                                            <div class="alert alert-warning gap-2 d-flex align-items-start"
                                                role="alert">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                <div>
                                                    Format file .pdf dan ukuran file
                                                    maksimal 2
                                                    MB.
                                                </div>
                                            </div>
                                            <input type="file" class="form-control" id="lampiran" name="lampiran"
                                                aria-describedby="emailHelp" accept=".pdf" required />
                                            <div class="invalid-feedback">
                                                Mohon upload lampiran dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jumlahLampiran" class="form-label black fw-normal">Jumlah
                                                Lampiran</label>
                                            <input type="number" class="form-control" placeholder="Contoh : 1"
                                                id="jumlahLampiran" name="jumlahLampiran"
                                                value="{{ old('jumlahLampiran') }}" min="0" />
                                            <div class="invalid-feedback">
                                                Masukkan jumlah lampiran surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1"
                                                class="form-label black fw-normal">Perihal</label>
                                            <textarea class="form-control perihal" id="exampleFormControlTextarea1" rows="4"
                                                placeholder="Contoh : Permohonan perijinan penelitian" name="perihal" required>{{ old('perihal') }}</textarea>
                                            <div class="invalid-feedback">
                                                Masukkan perihal surat dengan benar.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="mybtn light" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="mybtn blue" type="submit" form="formRegistrasi">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal registrasi end -->

            <!-- Modal detail & edit start -->
            <div class="modal modal__section fade" id="editSuratMasuk" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    {{-- Loader start --}}
                    <div id="myloader" class="w-100 h-100 position-absolute justify-content-center align-items-center"
                        style="z-index: 9999; backdrop-filter: blur(4px); background-color: rgba(256, 256, 256, .8); display: flex; border-radius: .3rem">
                        <div class="lds-dual-ring"></div>
                    </div>
                    {{-- Loader end --}}
                    <div class="modal-content p-3">
                        <div class="modal-header">
                            <h4 class="modal-title fw-semibold black" id="modalTitle">
                                Detail Surat Masuk
                            </h4>
                            <button type="button" onclick="batalHandling()" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEdit" enctype="multipart/form-data" method="POST"
                                action="{{ route('editSM') }}">
                                @csrf
                                <input type="text" id="idSurat" name="idSurat" hidden>
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="asalSurat" class="form-label black fw-normal">Asal Surat</label>
                                            <input type="text" disabled class="form-control" id="asalSuratE"
                                                name="asalSurat" placeholder="Contoh : Ketua Departemen Kedokteran"
                                                aria-describedby="emailHelp" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="nomorSuratE" class="form-label black fw-normal">Nomor
                                                Surat</label>
                                            <input disabled type="text" class="form-control"
                                                placeholder="Masukkan nomor surat" id="nomorSuratE" name="nomorSurat"
                                                aria-describedby="emailHelp" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="date" class="form-label black fw-normal">Tanggal
                                                Surat</label>
                                            <div class="position-relative input__tanggal__form">
                                                <input identifier="date" disabled class="form-control"
                                                    name="tanggalPengajuan" id="tanggalPengajuanE" value="2020-06-16">
                                                <i class="fa-solid fa-calendar-days position-absolute"></i>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="penerima" class="form-label black fw-normal">Penerima</label>
                                            <select class="form-select" disabled aria-label="Default select example"
                                                name="tujuanSurat" id="tujuanSuratE">
                                                <option selected>
                                                    ...
                                                </option>
                                                @foreach ($tujuan as $k => $v)
                                                    <option value="{{ $v->kode }}">{{ $v->nama }}
                                                        ({{ $v->kode }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="penerima" class="form-label black fw-normal">Sifat</label>
                                            <select class="form-select" disabled id="sifatSuratE"
                                                aria-label="Default select example" name="sifatSurat">
                                                <option selected>
                                                    ...
                                                </option>
                                                @foreach ($sifat as $k => $v)
                                                    <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="kodeHal" class="form-label black fw-normal">Kode Hal</label>
                                            <select class="form-select" disabled aria-label="Default select example"
                                                id="kodeHalE" name="kodeHal">
                                                <option selected>...</option>
                                                @foreach ($hal as $k => $v)
                                                    <option value="{{ $v->kode }}">{{ $v->nama }}
                                                        ({{ $v->kode }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lampiran" class="form-label black fw-normal">Upload
                                                Arsip Surat Baru</label>
                                            {{-- <div class="alert alert-primary gap-1 d-flex align-items-center"
                                            role="alert">
                                            <div class="fs-6">
                                                Nama lampiran sebelumnya : <span class="fw-semibold"
                                                    id="lampiranE"></span>
                                            </div>
                                        </div> --}}
                                            <input type="file" disabled class="form-control" id="lampiran"
                                                aria-describedby="emailHelp" name="lampiran" accept=".pdf" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="jumlahLampiran" class="form-label black fw-normal">Jumlah
                                                Lampiran</label>
                                            <input id="jumlahLampiranE" disabled type="number" class="form-control"
                                                id="jumlahLampiranE" name="jumlahLampiran" min="0" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1"
                                                class="form-label black fw-normal">Perihal</label>
                                            <textarea id="perihalE" disabled class="form-control perihal" id="exampleFormControlTextarea1" rows="1"
                                                placeholder="Contoh : Permohonan perijinan penelitian" name="perihal" style="min-height: unset"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert border rounded-2 py-3 gap-2 d-flex align-items-start mt-3"
                                    role="alert">
                                    <i class="fa-solid fa-circle-info" class="icon__info"></i>
                                    <div>
                                        <span class="fw-semibold" style="font-size: 14px">Catatan</span>
                                        <h5 class="mt-1 fw-normal" style="line-height: 1.5; font-size: 14px">
                                            Surat ini ditambahkan oleh <span id="created_by"></span> (<span
                                                id="bagian"></span>)
                                            pada <span id="created_at"></span>
                                        </h5>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if ($user->role_id != 3)
                            <div class="modal-footer">
                                <div id="footer__edit" style="display: none">
                                    <button type="button" onclick="batalHandling()" id="btnBatal" class="mybtn light"
                                        data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="button" class="mybtn blue" onclick="confirmEdit()">
                                        Simpan
                                    </button>
                                </div>
                                <div id="footer__detail">
                                    <button type="button" onclick="editData()" class="mybtn blue"
                                        onclick="confirmEdit()">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Modal detail & edit end -->

            <!-- Modal disposisi surat start -->
            <div class="modal modal__section fade" data-bs-backdrop="static" id="disposisi" tabindex="-1"
                aria-labelledby="ex ampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl h-100">
                    <div class="modal-content p-3 h-100">
                        <div class="modal-header">
                            <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                                Disposisi Surat
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <iframe src="" id="iframeDisposisi" frameborder="0" style="width:100%;"
                                class="h-100"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal disposisi surat end -->

            {{-- Tabel content start --}}
            <div class="table-responsive">
                <table id="mytable" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="no">#</th>
                            <th>Asal Surat / No. Surat</th>
                            <th>Tanggal Surat</th>
                            <th>Penerima</th>
                            <th class="text-center">Perihal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suratMasuk as $k => $v)
                            <tr>
                                <td class="no">{{ $k + 1 }}</td>
                                <td>
                                    {{ $v->asalSurat }} <br> <span class="pt-2 d-inline-block">Nomor :
                                        {{ $v->nomorSurat }}
                                    </span>
                                </td>
                                <td>{{ date('d ', strtotime($v->tanggalPengajuan)) }}{{ convertToBulan(date('F', strtotime($v->tanggalPengajuan))) }}{{ date(' Y', strtotime($v->tanggalPengajuan)) }}
                                </td>
                                <td>{{ $v->namaTujuan }}</td>
                                <td>
                                    {{-- @if ($v->sifatSurat == 1)
                            <div class="sifat biasa d-flex justify-content-center align-items-center mx-auto">
                                <h5>Biasa</h5>
                            </div>
                            @elseif ($v->sifatSurat == 2)
                            <div class="sifat penting d-flex justify-content-center align-items-center mx-auto">
                                <h5>Penting</h5>
                            </div>
                            @elseif ($v->sifatSurat == 3)
                            <div class="sifat segera d-flex justify-content-center align-items-center mx-auto">
                                <h5>Segera</h5>
                            </div>
                            @elseif ($v->sifatSurat == 4)
                            <div class="sifat rahasia d-flex justify-content-center align-items-center mx-auto">
                                <h5>Rahasia</h5>
                            </div>
                            @endif --}}
                                    {{ $v->perihal }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-start gap-2 flex-wrap">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratMasuk"
                                            class="myicon position-relative blue d-flex align-items-center justify-content-center"
                                            id="btnEdit" onclick="detailSurat('{{ $v->id }}')"
                                            data-id="{{ $v->id }}" style="width: fit-content">
                                            <i class="fa-solid fa-file-lines me-2"></i>Detail
                                        </button>
                                        <button type="button"
                                            class="myicon light bg-white position-relative blue d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal" data-bs-target="#lampiran"
                                            onclick="lihatLampiran('{{ $v->lampiran }}')">
                                            <i class="fa-solid fa-paperclip"></i>
                                            <div class="position-absolute mytooltip">
                                                <div class="text-white px-3 py-2 position-relative">
                                                    Lihat Arsip
                                                </div>
                                                <div id="arrow"></div>
                                            </div>
                                        </button>
                                        @if ($user->role_id != 3)
                                            <button type="button"
                                                class="myicon position-relative red d-flex align-items-center justify-content-center"
                                                onclick="confirmHapus('{{ $v->id }}')">
                                                <i class="fa-solid fa-trash"></i>
                                                <div class="position-absolute mytooltip">
                                                    <div class="text-white px-3 py-2 position-relative">
                                                        Hapus
                                                    </div>
                                                    <div id="arrow"></div>
                                                </div>
                                            </button>
                                        @endif
                                        @if ($user->role_id != 3)
                                            <a data-id="{{ $v->id }}" data-bs-toggle="modal"
                                                data-bs-target="#disposisi"
                                                class="test myicon position-relative green d-flex align-items-center justify-content-center"
                                                onclick="showDisposisi('{{ $v->id }}')">
                                                <i class="fa-solid fa-file-export"></i>
                                                <div class="position-absolute mytooltip">
                                                    <div class="text-white px-3 py-2 position-relative">
                                                        Disposisi
                                                    </div>
                                                    <div id="arrow"></div>
                                                </div>
                                            </a>
                                        @endif

                                        <!-- {{ route('disposisi') . '?id=' . $v->id }} -->
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Tabel content end --}}

        </div>
        {{-- Tabel wrapper end --}}

    </section>
@endsection
@section('sm', 'active')
@section('title', 'Surat Masuk')
@section('js')
    {{-- Sweet alert start --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Sweet alert end --}}

    {{-- Fungsi lihat lampiran start --}}
    <script>
        function lihatLampiran(id) {
            var options = {
                fallbackLink: "<p>Silahkan lihat arsip dokumen surat melalui link berikut. <a href='[url]'>Lihat arsip.</a></p>"
            };
            PDFObject.embed(`{{ asset('uploads/${id}') }}`, "#example1", options);
        }
    </script>
    {{-- Fungsi lihat lampiran end --}}

    {{-- Function refresh datatables start --}}
    <script>
        function refreshDatatable() {
            setInterval('location.reload()', 2000);
        }
    </script>
    {{-- Function refresh datatables end --}}

    <!-- Sweet alert : confirm delete start -->
    <script>
        function confirmHapus(id) {
            new Audio("audio/warning-edited.mp3").play();
            Swal.fire({
                title: "Konfirmasi",
                text: "Yakin ingin menghapus file?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2F5596",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    var token = $("meta[name='csrf-token']").attr("content");
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('deleteSM') }}",
                        data: {
                            "idSurat": id,
                            "_token": token,
                        },
                        success: function(data) {
                            Swal.fire("Berhasil!", data, "success");
                            new Audio("audio/success-edited.mp3").play();
                            refreshDatatable();
                        },
                        error: function(error) {
                            Swal.fire("Gagal!", `${error.responseText}`, "error");
                            new Audio("audio/error-edited.mp3").play();
                        },

                    });
                    // Swal.fire("Berhasil!", "File anda berhasil dihapus.", "success");
                    // new Audio("audio/success-edited.mp3").play();
                } else {
                    new Audio("audio/cancel-edited.mp3").play();
                }
            });
        }
    </script>
    <!-- Sweet alert : confirm delete end -->

    <!-- Sweet alert : confirm add start -->
    <script>
        function confirmAdd() {
            new Audio("audio/warning-edited.mp3").play();
            Swal.fire({
                title: "Konfirmasi",
                text: "Pastikan data yang anda masukkan benar!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2F5596",
                cancelButtonColor: "#d33",
                confirmButtonText: "Tambah",
                cancelButtonText: "Batal",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    // new Audio("audio/success-edited.mp3").play();
                    // Swal.fire("Berhasil!", "Data berhasil ditambahkan.", "success");
                    // $("#registrasiSuratMasuk").modal("hide");
                    document.getElementById("formRegistrasi").submit();
                } else {
                    new Audio("audio/cancel-edited.mp3").play();
                    // $("#registrasiSuratMasuk").modal("hide");
                }
            });

        }

        function berhasil(txt) {
            new Audio("audio/success-edited.mp3").play();
            Swal.fire({
                confirmButtonColor: "#2F5596",
                icon: 'success',
                title: `Berhasil`,
                text: `${txt}`,
            })
        }

        function gagal() {
            new Audio("audio/cancel-edited.mp3").play();
            Swal.fire({
                confirmButtonColor: "#2F5596",
                icon: 'error',
                title: 'Gagal!',
                text: 'Data gagal ditambahkan!',
            })
        }
    </script>
    <!-- Sweet alert : confirm add end -->

    @if ($message = Session::get('success'))
        <script>
            berhasil("{{ Session::get('success') }}")
        </script>
        </div>
    @endif

    <!-- Sweet alert : confirm edit start -->
    <script>
        function confirmEdit() {
            new Audio("audio/warning-edited.mp3").play();
            Swal.fire({
                title: "Konfirmasi",
                text: "Pastikan data yang anda masukkan benar!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2F5596",
                cancelButtonColor: "#d33",
                confirmButtonText: "Simpan",
                cancelButtonText: "Batal",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("formEdit").submit();
                    // new Audio("audio/success-edited.mp3").play();
                    // Swal.fire("Berhasil!", "Data berhasil diperbarui.", "success");
                    $("#editSuratMasuk").modal("hide");
                } else {
                    new Audio("audio/cancel-edited.mp3").play();
                    $("#editSuratMasuk").modal("hide");
                }
            });
        }
    </script>
    <!-- Sweet alert : confirm edit end -->

    {{-- Fungsi detail surat start --}}
    <script>
        function detailSurat(id) {
            let loader = document.getElementById('myloader')
            loader.classList.remove('d-none')

            let url = "{{ route('getSM', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    loader.classList.add('d-none')
                    $('#nomorSuratE').attr('value', data.nomorSurat)
                    $("#tujuanSuratE").val(data.tujuanSurat)
                    tanggal = new Date(data.tanggalPengajuan)
                    y = tanggal.getFullYear()
                    m = parseInt(tanggal.getMonth()) + 1
                    d = tanggal.getDate()

                    // Tanggal keterangan created at start
                    const month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                        "September", "Oktober", "November", "Desember"
                    ];
                    const weekday = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
                    tanggalDibuat = new Date(data.created_at)
                    yd = tanggalDibuat.getFullYear()
                    md = month[tanggalDibuat.getMonth()]
                    dd = tanggalDibuat.getDate()
                    dname = weekday[tanggalDibuat.getDay()]
                    // Tanggal keterangan created at end

                    $('#tanggalPengajuanE').val(`${d}-${m}-${y}`)
                    $('#asalSuratE').attr('value', data.asalSurat)
                    $("#kodeHalE").val(data.kodeHal)
                    $('#sifatSuratE').val(data.sifatSurat)
                    $('#perihalE').val(data.perihal)
                    $('#jumlahLampiranE').val(data.jumlahLampiran)
                    $('#lampiranE').html(data.lampiran)
                    $('#created_by').text(data.name)
                    $('#created_at').text(`${dname}, ${dd} ${md} ${yd}.`)
                    $('#bagian').text(data.bagian)


                }
            });
            $('#idSurat').attr('value', id);
        }
    </script>
    {{-- Fungsi detail surat end --}}

    {{-- Fungsi edit start --}}
    <script>
        const btnEdit = document.getElementById('footer__edit')
        const btnDetail = document.getElementById('footer__detail')
        let title = document.getElementById('modalTitle')

        function editData() {
            const btnBatal = document.getElementById('#btnBatal')
            let input = document.querySelectorAll('[disabled]')

            title.innerText = "Form Edit Surat Masuk"
            input.forEach(item => item.removeAttribute('disabled'))
            btnDetail.style.display = 'none'
            btnEdit.classList.add('d-flex', 'gap-2')
        }

        function batalHandling() {
            let input = document.querySelectorAll('#formEdit select, #formEdit input, #formEdit textarea')

            title.innerText = "Detail Surat Masuk"
            btnDetail.style.display = 'block'
            btnEdit.classList.remove('d-flex', 'gap-2')
            input.forEach(item => item.setAttribute('disabled', true))
        }
    </script>
    {{-- Fungsi edit end --}}

    <script>
        $(document).ready(function() {
            var start = $('#inputTanggalStart').attr('value')
            var end = $('#inputTanggalEnd').attr('value')

            oke = false
            $('#inputTanggalStart').change(function() {
                // console.log(end)
                start = this.value
                if (start && end) {
                    window.location.href = "{{ route('suratMasuk') }}" + '?start=' + start + '&end=' + end;
                }
            })
            $('#inputTanggalEnd').change(function() {
                // console.log(start)
                end = this.value
                if (start && end) {
                    window.location.href = "{{ route('suratMasuk') }}" + '?start=' + start + '&end=' + end;
                }
            })
        });
    </script>

    <!-- Data tables start -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js">
    </script>
    <!-- Data tables end -->

    <!-- Data tables : responsive start -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <!-- Data tables : responsive end -->

    <!-- Initializing data tables start -->
    <script>
        function showDisposisi(id) {
            $('#iframeDisposisi').attr('src', `{{ url('disposisi?id=${id}') }}`)
        }

        function showLampiran(id) {
            $('#iframeLampiran').attr('src', `{{ url('/uploads/${id}') }}`)
        }

        $(document).ready(function() {
            $('.disposisi').on('click', function() {

            })
            $("#mytable").DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, 3, 4, 5]
                }],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: "none",
                        target: "",
                    },
                },
                dom: '<"d-flex justify-content-end"f>rt<"d-flex justify-content-between mt-3 flex-wrap gap-2"<"d-flex align-items-center flex-wrap gap-2"li>p>',
                // buttons: [{
                //         extend: 'excelHtml5',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3, 4]
                //         },
                //         className: 'mybtn btn__export'
                //     },
                //     {
                //         extend: 'pdfHtml5',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3, 4]
                //         },
                //         className: 'mybtn btn__export'
                //     }
                // ],
                destroy: true,
                order: false,
                language: {
                    lengthMenu: "Tampilkan _MENU_",
                    zeroRecords: "Surat masuk tidak tersedia. <br>Silahkan registrasi surat terlebih dahulu.",
                    info: "Menampilkan _PAGE_ dari _PAGES_",
                    infoEmpty: "Baris tidak tersedia",
                    infoFiltered: "(filtered from _MAX_ total records)",
                    search: "Cari :",
                    // pagingType: "numbers",
                    // paginate: {
                    //   previous: "Sebelumnya",
                    //   next: "Berikutnya",
                    // },
                },
                oLanguage: {
                    oPaginate: {
                        sNext: '<span class="pagination-fa"><i class="fa-solid fa-angle-right"></i></span>',
                        sPrevious: '<span class="pagination-fa"><i class="fa-solid fa-angle-left"></i></span>',
                    },
                },
                lengthMenu: [
                    [10, 20, -1],
                    [10, 20, "Semua"],
                ],
            });
        });
    </script>
    <!-- Initializing data tables end -->

    {{-- script tambahan untuk menangkap session --}}
    <script>
        function berhasil(txt) {
            new Audio("audio/success-edited.mp3").play();
            // Swal.fire("Berhasil!", `${txt}`, "success");
            Swal.fire({
                confirmButtonColor: "#2F5596",
                icon: 'success',
                title: `Berhasil`,
                text: `${txt}`,
            })
        }

        function gagal(txt) {
            new Audio("audio/cancel-edited.mp3").play();
            Swal.fire({
                confirmButtonColor: "#2F5596",
                icon: 'error',
                title: 'Gagal!',
                text: `Data gagal ditambahkan! ${txt} 
                `,
            })
        }
    </script>

    @if ($message = Session::get('success'))
        <script>
            // berhasil("{{ Session::get('success') }}")
        </script>
    @endif

    {{-- Bootstrap form validation start --}}
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Validasi nomor surat jika sudah digunakan
            function cekNomorSurat() {

            }

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                // console.log('tes')
                // console.log(form)
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {

                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
    {{-- Bootstrap form validation end --}}

@endsection
