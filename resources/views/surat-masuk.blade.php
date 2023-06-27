@extends('template')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Bootstrap data tables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />

<!-- Datepicker Jquery -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

@if (isset($_GET['start']) and isset($_GET['end']))
<script>
    start = "{{ $_GET['start'] }}"
            end = "{{ $_GET['end'] }}"
</script>
@endif
{{-- Datepicker Jquery : input tanggal start --}}
<script>
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


            errorMsg = ""
        });
</script>


{{-- Datepicker Jquery : input tanggal end --}}
<script>
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
</script>
{{-- @if (isset($_GET['start']) and isset($_GET['end']))
<script>
    start = "{{ $_GET['start'] }}"
            end = "{{ $_GET['end'] }}"
            $("#inputTanggalStart").datepicker('setDate', `${start}`)
            $("#inputTanggalEnd").datepicker('setDate', `${end}`)


            // var end = $.datepicker.parseDate('dd-mm-yy', end);
            // $("#inputTanggalEnd").datepicker('setDate', new Date(end));
</script>
@endif --}}


{{-- Datepicker Jquery : registrasi surat --}}
<script>
    $(function() {
            // Initializing
            $("#datepicker").datepicker();

            // Ganti tahun
            $("#datepicker").datepicker("option", "changeYear", true);
            // Ganti format tanggal
            $("#datepicker").datepicker("option", "dateFormat", "dd-mm-yy");
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
        <strong>Registrasi gagal!</strong>
        <p class="mt-2">@foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session()->has('failed'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Registrasi gagal!</strong>
        <p class="mt-2">{{session('failed')}}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    {{-- Alert gagal menambahkan surat end --}}

    {{-- Keterangan start --}}
    <div class="card p-4 mb-md-0 keterangan">
        <h5 class="fw-semibold black mb-2">Keterangan</h5>
        <h5 class="fw-normal black__light mb-3">
            Surat masuk wajib dilakukan registrasi. Registrasi surat dilakukan
            dengan sistem dalam melakukan kearsipan surat. Operator dapat
            melakukan registrasi surat masuk dan penerusan ke pimpinan agar
            dapat menindak lanjuti surat tersebut. Anda dapat mendownload
            "Tata Naskah Dinas di Lingkungan Universitas Diponegoro" sebagai
            pedoman dalam membuat nomor surat dengan menekan tombol dibawah
            ini.
        </h5>
        <a href="{{ route('downloadNaskah') }}" class="mybtn blue"><i class="fa-solid fa-download me-1"></i>
            Download
        </a>
    </div>
    {{-- Keterangan start --}}

    {{-- Tabel wrapper start --}}
    <div class="card p-4 mt-3">
        {{-- Tabel header start --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
            <h4 class="fw-semibold black">Daftar Surat Keluar</h4>
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
                <button id="tes" type="button" data-bs-toggle="modal" data-bs-target="#registrasiSuratMasuk"
                    class="mybtn blue">
                    <i class="fa-solid fa-plus me-2"></i>Registrasi Surat
                </button>
            </div>
        </div>
        {{-- Tabel header end --}}

        <!-- Modal lampiran surat start -->
        <div class="modal modal__section fade" id="lampiran" tabindex="-1" aria-labelledby="lampiranLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl h-100">
                <div class="modal-content modal-xl h-100">
                    <div class="modal-header">
                        <h4 class="modal-title fw-semibold black" id="lampiranLabel">Lampiran Surat</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <iframe src="" id="iframeLampiran" frameborder="0" style="width:100%;" class="h-100"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal lampiran surat end -->

        <!-- Modal registrasi start -->
        <div class="modal modal__section fade" id="registrasiSuratMasuk" tabindex="-1"
            aria-labelledby="ex ampleModalLabel" aria-hidden="true">
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
                                        <label for="noSurat" class="form-label black fw-normal">Nomor Surat</label>
                                        <input type="text" class="form-control" placeholder="Contoh : 4/UN7.F4/2015/X"
                                            id="noSurat" name="nomorSurat" aria-describedby="emailHelp" required />
                                        <div class="invalid-feedback">
                                            Masukkan nomor surat dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="penerima" class="form-label black fw-normal">Penerima</label>
                                        <select class="form-select" required aria-label="Default select example"
                                            name="tujuanSurat">
                                            <option selected disabled value="">...</option>
                                            @foreach ($tujuan as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Masukkan penerima surat dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="form-label black fw-normal">Tanggal
                                            Surat</label>
                                        <div class="position-relative input__tanggal__form">
                                            <input type="text" id="datepicker" identifier="date" placeholder="..."
                                                name="tanggalPengajuan" aria-placeholder="coba" class="form-control"
                                                required>
                                            <i class="fa-solid fa-calendar-days position-absolute"></i>
                                        </div>
                                        <div class="invalid-feedback">
                                            Masukkan tanggal pengajuan dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="asalSurat" class="form-label black fw-normal">Asal Surat</label>
                                        <input type="text" class="form-control" id="asalSurat" name="asalSurat"
                                            placeholder="Contoh : Ketua Departemen Kedokteran"
                                            aria-describedby="emailHelp" required />
                                        <div class="invalid-feedback">
                                            Masukkan asal surat dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kodeHal" class="form-label black fw-normal">Kode Hal</label>
                                        <select class="form-select" required aria-label="Default select example"
                                            id="kodeHal" name="kodeHal">
                                            <option selected disabled value="">...</option>
                                            @foreach ($hal as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}
                                                ({{ $v->kode }})
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Masukkan kode hal surat dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="penerima" class="form-label black fw-normal">Sifat</label>
                                        <select class="form-select" aria-label="Default select example" required
                                            name="sifatSurat">
                                            <option selected disabled value="">...</option>
                                            @foreach ($sifat as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Masukkan sifat surat dengan benar.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">

                                    <div class="mb-3">

                                        <label for="lampiran" class="form-label black fw-normal">Upload
                                            Lampiran</label>
                                        <div class="alert alert-primary gap-1 d-flex align-items-center" role="alert">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                                                style="width: 20px" viewBox="0 0 16 16" role="img"
                                                aria-label="Warning:">
                                                <path
                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                            </svg>
                                            <div>
                                                Format file <span class="fw-semibold">.pdf</span> dan ukuran file
                                                maksimal <span class="fw-semibold">1
                                                    MB</span>.
                                            </div>
                                        </div>
                                        <input type="file" class="form-control" id="lampiran" name="lampiran"
                                            aria-describedby="emailHelp" accept=".pdf" required />
                                        <div class="invalid-feedback">
                                            Mohon upload lampiran dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1"
                                            class="form-label black fw-normal">Perihal</label>
                                        <textarea class="form-control perihal" id="exampleFormControlTextarea1" rows="4"
                                            placeholder="Contoh : Permohonan perijinan penelitian" name="perihal"
                                            required></textarea>
                                        <div class="invalid-feedback">
                                            Masukkan perihal surat dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jumlahLampiran" class="form-label black fw-normal">Jumlah
                                            Lampiran</label>
                                        <input type="number" class="form-control" placeholder="Contoh : 1"
                                            id="jumlahLampiran" name="jumlahLampiran" min="0" required />
                                        <div class="invalid-feedback">
                                            Masukkan jumlah lampiran surat dengan benar.
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


        <!-- Modal edit start -->
        <div class="modal modal__section fade" id="editSuratMasuk" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                            Form Edit Surat Masuk
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEdit" enctype="multipart/form-data" method="POST" action="{{ route('editSM') }}">
                            @csrf
                            <input type="text" id="idSurat" name="idSurat" hidden>
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="mb-3">
                                        <label for="nomorSuratE" class="form-label black fw-normal">Nomor
                                            Surat</label>
                                        <input type="text" class="form-control" placeholder="Masukkan nomor surat"
                                            id="nomorSuratE" name="nomorSurat" aria-describedby="emailHelp" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="penerima" class="form-label black fw-normal">Penerima</label>
                                        <select class="form-select" aria-label="Default select example"
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
                                        <label for="date" class="form-label black fw-normal">Tanggal
                                            Surat</label>
                                        <div class="position-relative input__tanggal__form">
                                            <input identifier="date" class="form-control" name="tanggalPengajuan"
                                                id="tanggalPengajuanE" value="2020-06-16">
                                            <i class="fa-solid fa-calendar-days position-absolute"></i>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="asalSurat" class="form-label black fw-normal">Asal Surat</label>
                                        <input type="text" class="form-control" id="asalSuratE" name="asalSurat"
                                            placeholder="Contoh : Ketua Departemen Kedokteran"
                                            aria-describedby="emailHelp" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="kodeHal" class="form-label black fw-normal">Kode Hal</label>
                                        <select class="form-select" aria-label="Default select example" id="kodeHalE"
                                            name="kodeHal">
                                            <option selected>...</option>
                                            @foreach ($hal as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}
                                                ({{ $v->kode }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="mb-3">
                                        <label for="penerima" class="form-label black fw-normal">Sifat</label>
                                        <select class="form-select" id="sifatSuratE" aria-label="Default select example"
                                            name="sifatSurat">
                                            <option selected>
                                                ...
                                            </option>
                                            @foreach ($sifat as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lampiran" class="form-label black fw-normal">Upload
                                            Lampiran</label>
                                        <input type="file" class="form-control" id="lampiran"
                                            aria-describedby="emailHelp" name="lampiran" accept=".pdf" />
                                        <div class="mt-2"><span>Nama file : </span><span id="lampiranE"></span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1"
                                            class="form-label black fw-normal">Perihal</label>
                                        <textarea id="perihalE" class="form-control perihal"
                                            id="exampleFormControlTextarea1" rows="1"
                                            placeholder="Contoh : Permohonan perijinan penelitian"
                                            name="perihal"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jumlahLampiran" class="form-label black fw-normal">Jumlah
                                            Lampiran</label>
                                        <input id="jumlahLampiranE" type="number" class="form-control"
                                            placeholder="Masukkan nomor surat" id="jumlahLampiranE"
                                            name="jumlahLampiran" min="0" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mybtn light" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="button" class="mybtn blue" onclick="confirmEdit()">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal edit end -->

        <!-- Modal disposisi surat start -->
        <div class="modal modal__section fade" id="disposisi" tabindex="-1" aria-labelledby="ex ampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl h-100">
                <div class="modal-content p-3 h-100">
                    <div class="modal-header">
                        <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                            Disposisi Surat
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <iframe src="" id="iframeDisposisi" frameborder="0" style="width:100%;" class="h-100"></iframe>
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
                        <th class="no">No</th>
                        <th>Asal Surat / No. Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Perihal</th>
                        <th>Penerima</th>
                        <th>Sifat</th>
                        <th>Aksi</th>
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
                            {{-- <span class="date d-inline-block mt-1">{{ date('d M Y h:i:s',
                                strtotime($v->created_at)) }}
                                WIB</span> --}}
                        </td>
                        <td>{{ date('d ', strtotime($v->tanggalPengajuan)) }}{{ convertToBulan(date('F',
                            strtotime($v->tanggalPengajuan))) }}{{ date(' Y', strtotime($v->tanggalPengajuan)) }}
                        </td>
                        <td>{{ $v->perihal }}</td>
                        <td>{{ $v->tujuanSurat }}</td>
                        <td>
                            @if ($v->sifatSurat == 1)
                            <div class="sifat biasa d-flex justify-content-center align-items-center">
                                <h5>Biasa</h5>
                            </div>
                            @elseif ($v->sifatSurat == 2)
                            <div class="sifat penting d-flex justify-content-center align-items-center">
                                <h5>Penting</h5>
                            </div>
                            @elseif ($v->sifatSurat == 3)
                            <div class="sifat segera d-flex justify-content-center align-items-center">
                                <h5>Segera</h5>
                            </div>
                            @elseif ($v->sifatSurat == 4)
                            <div class="sifat rahasia d-flex justify-content-center align-items-center">
                                <h5>Rahasia</h5>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <button type="button"
                                    class="myicon light bg-white position-relative blue d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal" data-bs-target="#lampiran"
                                    onclick="showLampiran('{{ $v->lampiran }}')">
                                    <i class="fa-solid fa-paperclip"></i>
                                    <div class="position-absolute mytooltip">
                                        <div class="text-white px-3 py-2 position-relative">
                                            Lampiran
                                        </div>
                                        <div id="arrow"></div>
                                    </div>
                                </button>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratMasuk"
                                    class="myicon position-relative blue d-flex align-items-center justify-content-center passId"
                                    data-id="{{ $v->id }}">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    <div class="position-absolute mytooltip">
                                        <div class="text-white px-3 py-2 position-relative">
                                            Edit
                                        </div>
                                        <div id="arrow"></div>
                                    </div>
                                </button>
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
                                <a data-id="{{ $v->id }}" data-bs-toggle="modal" data-bs-target="#disposisi"
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
<script src="sweetalert2.all.min.js"></script>
{{-- Sweet alert end --}}

<script>
    var x = document.querySelector
</script>
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

<script>
    $(document).ready(function() {
            var start = $('#inputTanggalStart').attr('value')
            var end = $('#inputTanggalEnd').attr('value')

            oke = false
            $('#inputTanggalStart').change(function() {
                // console.log(end)
                start = this.value
                console.log(start)
                console.log(end)
                if (start && end) {
                    window.location.href = "{{ route('suratMasuk') }}" + '?start=' + start + '&end=' + end;
                }
            })
            $('#inputTanggalEnd').change(function() {
                // console.log(start)
                end = this.value
                console.log(start)
                console.log(end)
                if (start && end) {
                    window.location.href = "{{ route('suratMasuk') }}" + '?start=' + start + '&end=' + end;
                }
            })

            // Edit surat start
            $(".passId").click(function() {
                let url = "{{ route('getSM', ':id') }}";
                url = url.replace(':id', $(this).data('id'));
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        $('#nomorSuratE').attr('value', data.nomorSurat)
                        $("#tujuanSuratE").val(data.tujuanSurat)
                        $('#tanggalPengajuanE').val(new Date(data.tanggalPengajuan)
                            .toLocaleDateString('en-GB'))
                        $('#asalSuratE').attr('value', data.asalSurat)
                        $("#kodeHalE").val(data.kodeHal)
                        $('#sifatSuratE').val(data.sifatSurat)
                        $('#perihalE').val(data.perihal)
                        $('#jumlahLampiranE').val(data.jumlahLampiran)
                        $('#lampiranE').html(data.lampiran)
                    }
                });
                $('#idSurat').attr('value', $(this).data('id'));
                // alert($(this).data('id'));
            });
            // Edit surat end
        });
</script>

{{-- @if (isset($_GET['start']) and isset($_GET['end']))
<script>
    start = "{{ $_GET['start'] }}"
            // $('#inputTanggalStart').attr('value', new Date(start).toLocaleDateString('en-GB'))
            $('#inputTanggalStart').datepicker('setDate', start)
            end = "{{ $_GET['end'] }}"
            console.log(end)
            $('#inputTanggalEnd').datepicker('setDate', end)

            // $('#inputTanggalEnd').attr('value', new Date(end).toLocaleDateString('en-GB'))
</script>
@endif --}}

<!-- Data tables start -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js">
</script>
<!-- Data tables end -->

<!-- Data tables : responsive start -->
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<!-- Data tables : responsive end -->

{{-- Data tables : button export start --}}
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
</script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
</script>
<script type="text/javascript" charset="utf8"
    src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js">
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js">
</script>
{{-- Data tables : button export end --}}

<!-- Initializing data tables start -->
<script>
    function showDisposisi(id) {
            $('#iframeDisposisi').attr('src', `{{ url('disposisi?id=${id}') }}`)
        }

        function showLampiran(id) {
            console.log(id)
            $('#iframeLampiran').attr('src', `{{ url('/uploads/${id}') }}`)
        }

        $(document).ready(function() {
            $('.disposisi').on('click', function() {

            })
            $("#mytable").DataTable({
                // paging: false,
                // ordering: false,
                // searching: false,
                // responsive: true,
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, 3, 4, 5, 6]
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

{{-- Add keterangan button export start --}}
{{-- <script>
    $(document).ready(function() {
            var btn = document.querySelector(".dt-buttons")
            var descText = document.createElement('h5')
            descText.textContent = "Export :";
            descText.className = "desc__export"
            btn.insertBefore(descText, btn[0]);

            $('.test').on('click', function() {

            })

        })
</script> --}}
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
</div>
@endif
@if ($message = Session::get('failed'))
<script>
    // gagal("{{ Session::get('failed') }}")
</script>
</div>
@endif
@if ($errors->any())
@foreach ($errors->all() as $error)
{{-- <div>$error</div> --}}
<script>
    // gagal()
                // console.log($error)
                // errorMsg += {{ $error }}
</script>
@endforeach
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