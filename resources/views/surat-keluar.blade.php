@extends('template')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Datepicker Jquery -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<style>
    iframe {
        width: 100%;
        /* for responsiveness */
    }
</style>
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
            $("#datepicker").datepicker({
                changeYear: true
            });

            // Setter
            $("#datepicker").datepicker("option", "changeYear", true);

            $("#datepicker").datepicker("option", "dateFormat", "dd-mm-yy");

        });
</script>

{{-- Datepicker Jquery : edit surat --}}
<script>
    $(function() {
            // Initializing
            $("#datepickerEdit").datepicker();

            // Ganti tahun
            $("#datepickerEdit").datepicker({
                changeYear: true
            });

            // Getter
            var changeYear = $("#datepickerEdit").datepicker("option", "changeYear");

            // Setter
            $("#datepickerEdit").datepicker("option", "changeYear", true);
        });
</script>

{{-- Set attribut modal lampiran --}}
{{-- <script>
    function showLampiran(id) {
            $('#iframeLampiran').attr('src', `{{ url('/uploads/${id}') }}`)
        }
</script> --}}

<!-- Bootstrap data tables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />

<!-- Custom CSS -->
<link rel="stylesheet" href="css/surat-masuk-style.css" />

@endsection
@section('content')
<section class="surat__masuk content">
    {{-- @dd($suratKeluar) --}}
    {{-- Navigation start --}}
    <div class="navigation__content mb-4">
        <h5 class="fw__semi black">SURAT KELUAR</h5>
    </div>
    {{-- Navigation end --}}

    {{-- Alert gagal menambahkan surat start --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Aksi gagal!</strong>
        <p class="mt-2">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    {{-- Alert gagal menambahkan surat end --}}

    <div class="card p-4 mt-3 surat__keluar">
        <!-- Modal lampiran surat start -->
        <div class="modal modal__section fade" id="lampiran" tabindex="-1" aria-labelledby="lampiranLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl h-100">
                <div class="modal-content modal-xl h-100">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="lampiranLabel">Lampiran Surat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="example1"></div>
                        {{-- <iframe src="" id="iframeLampiran" frameborder="0" style="width:100%;"
                            class="h-100"></iframe> --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal lampiran surat end -->

        <!-- Modal edit start -->
        <div class="modal modal__section fade" id="editSuratKeluar" data-bs-backdrop="static" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h4 class="modal-title fw-semibold black" id="modalTitle">
                            Form Edit Surat Keluar
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEdit" method="POST" enctype="multipart/form-data" action="{{ route('editSK') }}">
                            @csrf
                            <div class="alert alert-primary gap-2 d-flex align-items-start" role="alert">
                                <i class="fa-solid fa-circle-info" class="icon__info"></i>
                                <div>
                                    <span class="fw-semibold">Keterangan</span>
                                    <h5 class="mt-1 fw-normal" style="line-height: 1.5">
                                        Surat ini dibuat oleh : <span id="created_by"></span> (<span
                                            id="bagian"></span>)
                                        pada <span id="created_at"></span>
                                    </h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <input type="text" name="jenisSurat" value="biasa" hidden>
                                    <input type="text" name="idSurat" hidden>
                                    <div class="mb-3">
                                        <label for="kodeUnitE" class="form-label black fw-normal">Kode Unit
                                            Surat</label>
                                        <select class="form-select" aria-label="Default select example" id="kodeUnitE"
                                            name="kodeUnit">
                                            <option selected value="">
                                                -- Pilih salah satu --
                                            </option>
                                            @foreach ($unit as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}
                                                ({{ $v->kode }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kodeHalE" class="form-label black fw-normal">Kode Hal</label>
                                        <select class="form-select" aria-label="Default select example" id="kodeHalE"
                                            name="kodeHal">
                                            <option value="" selected>
                                                -- Pilih salah satu --
                                            </option>
                                            @foreach ($hal as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}
                                                ({{ $v->kode }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tujuanSuratE" class="form-label black fw-normal">Tujuan
                                            Surat</label>
                                        <input type="text" class="form-control" placeholder="Masukkan nomor surat"
                                            id="tujuanSuratE" name="tujuanSurat" aria-describedby="emailHelp" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="sifatSuratE" class="form-label black fw-normal">Sifat</label>
                                        <select id="sifatSuratE" name="sifatSurat" class="form-select"
                                            aria-label="Default select example">
                                            <option value="" selected>
                                                -- Pilih salah satu --
                                            </option>
                                            @foreach ($sifat as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3" id="unitPengesahanKanan">
                                        <label for="disahkanOlehE" class="form-label black fw-normal">Disahkan
                                            Oleh</label>
                                        <select id="disahkanOlehE2" class="form-select"
                                            aria-label="Default select example">
                                            <option value="" selected>
                                                -- Pilih salah satu --
                                            </option>
                                            @foreach ($unit as $k => $v)
                                            <option value="{{ $v->nama }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="mb-3" id="unitPengesahanKiri">
                                        <label for="disahkanOlehE" class="form-label black fw-normal">Disahkan
                                            Oleh</label>
                                        <select id="disahkanOlehE1" class="form-select"
                                            aria-label="Default select example">
                                            <option value="" selected>
                                                -- Pilih salah satu --
                                            </option>
                                            @foreach ($unit as $k => $v)
                                            <option value="{{ $v->nama }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tanggalPengesahanE" class="form-label black fw-normal">Tanggal
                                            Disahkan</label>
                                        {{-- <duet-date-picker id="tanggalPengesahanE" identifier="date"
                                            name="tanggalPengesahan"></duet-date-picker> --}}
                                        <div class="position-relative input__tanggal__form">
                                            <input type="text" id="datepickerEdit" identifier="date" placeholder="..."
                                                name="tanggalPengesahanE" aria-placeholder="coba" class="form-control"
                                                required>
                                            <i class="fa-solid fa-calendar-days position-absolute"></i>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label black fw-normal" id="labelUpload">Upload Arsip Surat
                                            Baru</label>
                                        {{-- <div class="alert alert-primary gap-1 d-flex align-items-center"
                                            role="alert">
                                            <div class="fs-6">
                                                Nama lampiran sebelumnya : <span class="fw-semibold"
                                                    id="lampiranE"></span>
                                            </div>
                                        </div> --}}
                                        <input type="file" class="form-control" name="lampiran"
                                            aria-describedby="emailHelp" accept=".pdf" value="" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="jumlahLampiranE" class="form-label black fw-normal"
                                            id="labelJumlah">Jumlah Halaman Dokumen
                                            Arsip</label>
                                        <input type="number" class="form-control" id="jumlahLampiranE"
                                            name="jumlahLampiran" min="0" aria-describedby="emailHelp" />
                                    </div>

                                    <div class="mb-3">
                                        <label for="perihalE" class="form-label black fw-normal">Perihal</label>
                                        <textarea class="form-control perihal" id="perihalE" name="perihal" rows="1"
                                            placeholder="Contoh : Permohonan perijinan penelitian"
                                            style="min-height: unset"></textarea>
                                    </div>
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
                            <button type="button" onclick="editData()" class="mybtn blue" onclick="confirmEdit()">
                                Edit
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Modal edit end -->

        <!-- Modal registrasi start -->
        <div class="modal modal__section fade" id="registrasiSuratKeluar" data-bs-backdrop="static" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                            Form Registrasi Surat Keluar
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formRegistrasi" class="needs-validation" novalidate action="{{ route('inputSK') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="alert alert-warning gap-2 d-flex align-items-start" role="alert">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    <div>
                                        <span class="fw-semibold">Perhatian!</span>
                                        <h5 class="mt-1 fw-normal" style="line-height: 1.5">
                                            Setelah registrasi nomor
                                            surat berhasil, mohon untuk mengupload
                                            arsip surat melalui tombol "Upload Arsip".
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="mb-3">
                                        <label for="nomorSurat" class="form-label black fw-normal">
                                            Nomor Surat</label>
                                        <div class="input d-flex align-items-center">
                                            <input type="text" readonly class="form-control readonly" id="nomorSurat"
                                                aria-describedby="emailHelp" name="nomorSurat" required />
                                            <button type="button" class="ms-2 ambilNomor">
                                                Ambil Nomor <i class="fas fa-search ms-1"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback" id="feedbackNomorSurat">
                                            Masukkan nomor surat dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kodeUnit" class="form-label black fw-normal">Kode Unit
                                            Surat</label>
                                        <select id="kodeUnit" required name="kodeUnit" class="form-select"
                                            aria-label="Default select example">
                                            <option value="" selected disabled value="">...</option>
                                            @foreach ($unit as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}
                                                ({{ $v->kode }})
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="feedback-u">
                                            Masukkan kode unit surat dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kodeHal" class="form-label black fw-normal">Kode Hal</label>
                                        <select class="form-select" required id="kodeHal"
                                            aria-label="Default select example" name="kodeHal">
                                            <option value="" selected disabled value="">...</option>
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
                                        <label for="tujuanSurat" class="form-label black fw-normal">Tujuan
                                            Surat</label>
                                        <input type="text" required class="form-control"
                                            placeholder="Contoh : Fakultas Kedokteran" id="tujuanSurat"
                                            name="tujuanSurat" aria-describedby="emailHelp" />
                                        <div class="invalid-feedback">
                                            Masukkan tujuan surat dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sifatSurat" class="form-label black fw-normal">Sifat</label>
                                        <select class="form-select" id="sifatSurat" name="sifatSurat"
                                            aria-label="Default select example" required>
                                            <option value="" selected disabled value="">...</option>
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
                                        <label for="disahkanOleh" class="form-label black fw-normal">Disahkan
                                            Oleh</label>
                                        <select class="form-select" aria-label="Default select example"
                                            id="disahkanOleh" name="disahkanOleh" required>
                                            <option selected disabled value="">...</option>
                                            @foreach ($unit as $k => $v)
                                            <option value="{{ $v->nama }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Masukkan unit dengan benar.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tanggalPengesahan" class="form-label black fw-normal">Tanggal
                                            Disahkan</label>
                                        <div class="position-relative input__tanggal__form">
                                            <input type="text" id="datepicker" identifier="date" placeholder="..."
                                                name="tanggalPengesahan" aria-placeholder="coba" class="form-control"
                                                required>
                                            <i class="fa-solid fa-calendar-days position-absolute"></i>
                                        </div>
                                        <div class="invalid-feedback">
                                            Masukkan tanggal pengesahan surat dengan benar.
                                        </div>
                                    </div>
                                    {{-- <div class="mb-3">
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
                                            Masukkan lampiran dengan benar.
                                        </div>
                                    </div> --}}
                                    {{-- <div class="mb-3">
                                        <label for="jumlahLampiran" class="form-label black fw-normal">Jumlah
                                            Lampiran</label>
                                        <input type="number" class="form-control" placeholder="Contoh : 1"
                                            id="jumlahLampiran" name="jumlahLampiran" min="0"
                                            aria-describedby="emailHelp" />
                                        <div class="invalid-feedback">
                                            Masukkan jumlah lampiran surat dengan benar.
                                        </div>
                                    </div> --}}
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1"
                                            class="form-label black fw-normal">Perihal</label>
                                        <textarea name="perihal" class="form-control" style="min-height: 13.9rem"
                                            id="exampleFormControlTextarea1" rows="8"
                                            placeholder="Contoh : Permohonan perijinan penelitian" required></textarea>
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
                        <button type="submit" id="confirmRegistrasi" onclick="confirmAdd()" form="formRegistrasi"
                            class="mybtn blue">
                            Tambah
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal registrasi end -->

        <!-- Modal upload dokumen start -->
        <div class="modal modal__section fade" id="uploadDokumen" data-bs-backdrop="static" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                            Upload Arsip Surat
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formUploadDokumen" class="needs-validation" novalidate
                            action="{{ route('uploadDokumen') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="suratKeluar" name="dokumen">
                            <div class="alert alert-primary gap-1 d-flex align-items-start" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" style="width: 20px"
                                    viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                <div>
                                    <span class="fw-semibold">Perhatian!</span>
                                    <h5 class="mt-1 fw-normal" style="line-height: 1.5">
                                        Dokumen surat dalam bentuk pdf dan ukuran file tidak lebih dari 1MB.
                                    </h5>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="lampiran" class="form-label black fw-normal">Upload
                                    Arsip</label>
                                <input type="file" class="form-control" id="lampiran" name="lampiran"
                                    aria-describedby="emailHelp" accept=".pdf" required />
                                <div class="invalid-feedback">
                                    Isian upload arsip wajib diisi.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="jumlahLampiran" class="form-label black fw-normal">Jumlah Halaman
                                    Dokumen Arsip</label>
                                <input type="number" class="form-control" placeholder="Contoh : 1" id="jumlahLampiran"
                                    name="jumlahLampiran" min="0" aria-describedby="emailHelp" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mybtn light" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" form="formUploadDokumen" class="mybtn blue">
                            Tambah
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal upload dokumen end -->

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
                @if ($user->role_id != 3)
                <button type="button" data-bs-toggle="modal" data-bs-target="#registrasiSuratKeluar" class="mybtn blue">
                    <i class="fa-solid fa-plus me-2"></i>Registrasi Surat
                </button>
                @endif
            </div>
        </div>
        {{-- Tabel header end --}}

        {{-- Tabel content start --}}
        <div class="table-responsive">
            <table id="mytable" class="table table-borderless">
                <thead>
                    <tr>
                        <th class="no">#</th>
                        <th>Asal Surat / No. Surat</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Perihal</th>
                        <th>Penerima</th>
                        <th>Sifat</th>
                        <th>Aksi</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($suratKeluar as $k => $v)
                    <tr>
                        <td class="no">{{ $loop->iteration }}</td>
                        <td>
                            {{ $v->disahkanOleh }} <br>Nomor :
                            {{ $v->nomorSurat }}/{{ $v->kodeUnit }}/{{ $v->kodeHal }}/{{ convertToRomawi(date('m',
                            strtotime($v->tanggalPengesahan))) }}/{{ date('Y', strtotime($v->tanggalPengesahan)) }}
                        </td>
                        <td>{{ date('d ', strtotime($v->tanggalPengesahan)) }}{{ convertToBulan(date('F',
                            strtotime($v->tanggalPengesahan))) }}{{ date(' Y', strtotime($v->tanggalPengesahan)) }}
                        </td>
                        <td>{{ $v->perihal }}</td>
                        <td>{{ $v->tujuanSurat }}</td>
                        <td>
                            @if ($v->sifatSurat == 1)
                            <div class="sifat biasa d-flex justify-content-center align-items-center">
                                <h5 class="fw__semi">Biasa</h5>
                            </div>
                            @elseif ($v->sifatSurat == 2)
                            <div class="sifat penting d-flex justify-content-center align-items-center">
                                <h5 class="fw__semi">Penting</h5>
                            </div>
                            @elseif ($v->sifatSurat == 3)
                            <div class="sifat segera d-flex justify-content-center align-items-center">
                                <h5 class="fw__semi">Segera</h5>
                            </div>
                            @elseif ($v->sifatSurat == 4)
                            <div class="sifat rahasia d-flex justify-content-center align-items-center">
                                <h5 class="fw__semi">Rahasia</h5>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratKeluar"
                                    class="myicon position-relative blue d-flex align-items-center justify-content-center"
                                    id="btnEdit" onclick="detailSurat('{{ $v->id }}')" data-id="{{ $v->id }}"
                                    style="width: fit-content">
                                    <i class="fa-solid fa-file-lines me-2"></i>Detail
                                </button>
                                @if ($v->lampiran == null)
                                <button type="button"
                                    class="myicon position-relative yellow d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal" data-bs-target="#uploadDokumen"
                                    onclick="uploadDokumen('{{ $v->id }}')" id="btnUpload">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <div class="position-absolute mytooltip">
                                        <div class="text-white px-3 py-2 position-relative">
                                            Upload Arsip
                                        </div>
                                        <div id="arrow"></div>
                                    </div>
                                </button>
                                @endif
                                @if ($v->lampiran !== null)
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
                                @endif
                                {{-- <button type="button"
                                    class="myicon position-relative red d-flex align-items-center justify-content-center"
                                    onclick="confirmHapus('{{ $v->id }}')">
                                    <i class="fa-solid fa-trash"></i>
                                    <div class="position-absolute mytooltip">
                                        <div class="text-white px-3 py-2 position-relative">
                                            Hapus
                                        </div>
                                        <div id="arrow"></div>
                                    </div>
                                </button> --}}
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        {{-- Tabel content end --}}

    </div>
</section>
@endsection
@section('js')
{{-- Sweet alert cdn start --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Sweet alert cdn end --}}

{{-- Fungsi edit start --}}
<script>
    const btnEdit = document.getElementById('footer__edit')
    const btnDetail = document.getElementById('footer__detail')
    let title = document.getElementById('modalTitle')
    
    function editData() {
        const btnBatal = document.getElementById('#btnBatal')
        let input = document.querySelectorAll('[disabled]')
        
        title.innerText = "Form Edit Surat Masuk"
        input.forEach(item=>item.removeAttribute('disabled'))
        btnDetail.style.display = 'none'
        btnEdit.classList.add('d-flex', 'gap-2')
    }
    function batalHandling() {
        let input = document.querySelectorAll('select, input, textarea')
        
        title.innerText = "Detail Surat Masuk"
        btnDetail.style.display = 'block'
        btnEdit.classList.remove('d-flex', 'gap-2')
        input.forEach(item=>item.setAttribute('disabled', true))
    }
</script>
{{-- Fungsi edit end --}}

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

{{-- Fungsi upload dokumen start --}}
<script>
    function uploadDokumen(id) {
            let idSurat = document.getElementById('suratKeluar')
            idSurat.value = id
            console.log(idSurat);
        }
</script>
{{-- Fungsi upload dokumen end --}}

{{-- refresh page --}}
<script>
    function refreshDatatable() {
            $(document).ready(function() {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('refreshDatatable') }}" + "?jenis=suratkeluar",
                    dataType: 'json',
                    success: function(data) {
                        // var data = JSON.parse(data);
                        $('#mytable').DataTable().destroy();
                        data.forEach((value, index) => {
                            htmlNew = ""
                            htmlNew += `<tr>
                <td class="no">${index+1}</td>
                <td>Nomor : <br>${value.nomorSurat}/${value.kodeUnit}/${value.tanggalPengesahan}/${value.tanggalPengesahan}<br />
              <span class="date d-inline-block mt-1"
                >${value.created_at} WIB</span
              >
            </td>
            <td>${value.perihal}</td>
            <td>${value.tujuanSurat}</td>`
                            if (value.sifatSurat == 1) {
                                htmlNew += `<td><div
              class="sifat biasa d-flex justify-content-center align-items-center"
              >
                <h6 class="fw__semi">Biasa</h6>
              </div></td>`
                            } else if (value.sifatSurat == 2) {
                                htmlNew = `<td>              <div
              class="sifat penting d-flex justify-content-center align-items-center"
              >
                <h6 class="fw__semi">Penting</h6>
              </div></td>`
                            } else if (value.sifatSurat == 3) {
                                htmlNew = `<td>              <div
              class="sifat segera d-flex justify-content-center align-items-center"
              >
                <h6 class="fw__semi">Segera</h6>
              </div></td>`
                            } else if (value.sifatSurat == 4) {
                                htmlNew = `<td>              <div
              class="sifat rahasia d-flex justify-content-center align-items-center"
              >
                <h6 class="fw__semi">Rahasia</h6>
              </div></td>`
                            }
                            htmlNew = `            <td>
              <div class="d-flex align-items-center">
                <button
                  type="button"
                  data-bs-toggle="modal"
                  data-bs-target="#editSuratKeluar"
                  class="myicon blue d-flex align-items-center justify-content-center me-2 passId"
                  data-id="${value.id}"
                >
                  <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button
                  type="button"
                  class="myicon red d-flex align-items-center justify-content-center"
                  onclick="confirmHapus('${value.id}')"
                >
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </td></tr>`
                        });
                    }
                });
            });
            // setInterval('location.reload()', 4000);
        }
</script>
<!-- Sweet alert : confirm delete -->
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
                        url: '{{ route('deleteSK') }}',
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
<!-- Sweet alert : confirm add -->
<script>
    function confirmAdd() {
            // valid = true
            // inputs = document.querySelectorAll('.my__validation input, .my__validation select, .my__validation textarea')
            invalidFeedback = document.querySelectorAll('.invalid-feedback')
            nomorSurat = $('input[name="nomorSurat"]').attr('value')
            var url = '{{ route('cekTersedia', ':id') }}';
            url = url.replace(':id', $('input[name="nomorSurat"]').attr('value'));
            url += "?sumber=keluar&jenis=biasa"
            $.ajax({
                type: 'GET',
                url: url,
                async: false,
                statusCode: {
                    400: function() {
                        $('#feedbackNomorSurat').style.display = "block"
                        $('#feedbackNomorSurat').html("Error")
                        valid = false
                    },
                    201: function() {
                        $('#feedbackNomorSurat').style.display = "block"
                        $('#feedbackNomorSurat').html("Nomor surat telah digunakan")
                        valid = false

                    },
                    200: function() {
                        // $('#nomorSurat').style.borderColor = "none"
                        // $('#feedbackNomorSurat').html("")
                        $('#feedbackNomorSurat').style.display = "block"
                        valid = true
                    }
                },
            });
        }
</script>

{{-- Sweet alert : confirm upload dokumen start --}}
<script>
    function confirmUpload() {
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
                    document.getElementById('formUploadDokumen').submit();
                    console.log(document.getElementById('formUploadDokumen'));
                } else {
                    new Audio("audio/cancel-edited.mp3").play();
                }
            });
        }
</script>
{{-- Sweet alert : confirm upload dokumen send --}}

<!-- Sweet alert : confirm edit -->
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
                    $('#formEdit').submit();
                    // new Audio("audio/success-edited.mp3").play();
                    // Swal.fire("Berhasil!", "Data berhasil diperbarui.", "success");
                    $("#editSuratKeluar").modal("hide");
                } else {
                    new Audio("audio/cancel-edited.mp3").play();
                    $("#editSuratKeluar").modal("hide");
                }
            });
        }
</script>

<!-- Data tables -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js">
</script>
<script></script>
<script>
    $(".ambilNomor").click(function() {
            $.ajax({
                type: 'GET',
                url: "{{ route('ambilNomor') }}" + "?jenis=biasa",
                dataType: 'json',
                success: function(data) {
                    $('input[name="nomorSurat"]').attr('value', data)
                }
            });
        });
</script>

{{-- Fungsi edit data start --}}
<script>
    function detailSurat(id, lampiran) {
            let url = "{{ route('getSK', ':id') }}";
            url = url.replace(':id', id);
            // Kondisi sudah ada lampiran
            if (lampiran === 'null') {
                $('#labelUpload').hide()
                $('#labelJumlah').hide()
                $('#formEdit input[name=lampiran]').hide()
                $('#formEdit input[name=jumlahLampiran]').hide()
                $('#formEdit #unitPengesahanKanan').hide()
                $('#formEdit #unitPengesahanKiri').show()
                $('#formEdit #unitPengesahanKiri select').attr('name', "disahkanOleh")
            }
            // Kondisi belum ada lampiran
            else {
                $('#formEdit #unitPengesahanKiri').hide()
                $('#formEdit #unitPengesahanKanan').show()
                $('#formEdit #unitPengesahanKanan select').attr('name', "disahkanOleh")
                $('#formEdit input[name=lampiran]').show()
                $('#formEdit input[name=jumlahLampiran]').show()
                $('#formEdit #labelUpload').show()
                $('#formEdit #labelJumlah').show()

            }
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    $('input[name="jenisSurat"]').val('biasa')
                    $("#tujuanSuratE").val(data.tujuanSurat)
                    tanggal = new Date(data.tanggalPengesahan)
                    y = tanggal.getFullYear()
                    m = parseInt(tanggal.getMonth()) + 1
                    d = tanggal.getDate()
                    // Ganti tahun
                    $("#datepickerEdit").datepicker(
                        'setDate',
                        `0${m}/${d}/${y}`);
                    $('#tanggalPengesahanE').val(`0${m}/${d}/${y}`)
                    $('#tujuanSuratE').attr('value', data.tujuanSurat)
                    $('#perihalE').val(data.perihal)
                    $("#kodeHalE").val(data.kodeHal)
                    $("#kodeUnitE").val(data.kodeUnit)
                    $("#disahkanOlehE1").val(data.disahkanOleh)
                    $("#disahkanOlehE2").val(data.disahkanOleh)
                    $('#sifatSuratE').val(data.sifatSurat)
                    $('#jumlahLampiranE').val(data.jumlahLampiran)
                    $('#lampiranE').html(data.lampiran)
                    $('#created_by').text(data.name)
                    $('#created_at').text(data.created_at)
                    $('#bagian').text(data.bagian)
                }
            });
            $('input[name="idSurat"]').attr('value', id);
        }
        $(document).ready(function() {
            var start = $('#inputTanggalStart').attr('value')
            var end = $('#inputTanggalEnd').attr('value')
            oke = false
            $('#inputTanggalStart').change(function() {
                start = this.value
                if (start && end) {
                    window.location.href = '{{ route('suratKeluar') }}' + '?start=' + start + '&end=' +
                        end;
                }
            })
            $('#inputTanggalEnd').change(function() {
                end = this.value
                if (start && end) {
                    window.location.href = '{{ route('suratKeluar') }}' + '?start=' + start + '&end=' +
                        end;
                }
            })
        });
</script>
{{-- Fungsi edit data end --}}

<!-- Data tables : responsive -->
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
{{-- include tombol ekspor untuk datatable --}}

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

<!-- Initializing data tables -->
<script>
    $(document).ready(function() {
            $("#mytable").DataTable({
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
                dom: '<"d-flex justify-content-end"f>rt<"d-flex justify-content-between mt-3 overflow-hidden"<"d-flex align-items-center"li>p>',
                destroy: true,
                order: false,
                language: {
                    lengthMenu: "Tampilkan _MENU_",
                    zeroRecords: "Surat keluar tidak tersedia. <br>Silahkan registrasi surat terlebih dahulu.",
                    info: "Menampilkan _PAGE_ dari _PAGES_",
                    infoEmpty: "Baris tidak tersedia",
                    infoFiltered: "(filtered from _MAX_ total records)",
                    search: "Cari :",
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
    if ("{{ Session::get('failed') }}") {
                gagal("{{ Session::get('failed') }}")
            }
</script>
</div>
@endif

{{-- Bootstrap form validation start --}}
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
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

{{-- Readonly ambil nomor start --}}
<script>
    $(".readonly").on('keydown paste focus mousedown', function(e) {
            if (e.keyCode != 9) // ignore tab
                e.preventDefault();
        });
</script>
{{-- Readonly ambil nomor end --}}

@endsection
@section('sk', 'active')
@section('title', 'Surat Keluar')