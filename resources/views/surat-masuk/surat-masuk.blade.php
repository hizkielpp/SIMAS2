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
    <link rel="stylesheet" href="{{ asset('css/surat-masuk-style.css') }}" />
@endsection
@section('content')
    <section class="surat__masuk content">
        {{-- Navigation start --}}
        <div class="navigation__content mb-4">
            <div class="d-flex mt-3 justify-content-between align-items-center">
                <h5 class="fw__semi black">SURAT MASUK</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb p-0 bg-transparent mb-0">
                        <li class="breadcrumb-item active" aria-current="page">Semua Surat Masuk</li>
                    </ol>
                </nav>
            </div>
        </div>
        {{-- Navigation end --}}

        {{-- Alert berhasil start --}}
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        {{-- Alert berhasil end --}}

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
                                action="{{ route('surat-masuk.inputSM') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12">
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
                                            <label for="asalSurat" class="form-label black fw-normal">Asal Surat</label>
                                            <input type="text" class="form-control" id="asalSurat" name="asalSurat"
                                                placeholder="Contoh : Ketua Departemen Kedokteran"
                                                aria-describedby="emailHelp" value="{{ old('asalSurat') }}" required />
                                            <div class="invalid-feedback">
                                                Masukkan asal surat dengan benar.
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
                                            <label for="ditujukan_kepada" class="form-label black fw-normal">Ditujukan
                                                Kepada</label>
                                            <select class="form-select" required aria-label="Default select example"
                                                name="ditujukan_kepada">
                                                <option selected disabled value="">...</option>
                                                @foreach ($ditujukanKepada as $k => $v)
                                                    @if (old('ditujukan_kepada') == $v->nip)
                                                        <option value="{{ $v->nip }}" selected>
                                                            {{ $v->nama_jabatan }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $v->nip }}">{{ $v->nama_jabatan }}
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
                            <button type="button" onclick="fillData()" class="mybtn light me-auto">
                                Testing
                            </button>
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

            {{-- Tabel content start --}}
            <div class="table-responsive">
                <table id="mytable" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="no">#</th>
                            <th>Asal Surat / No. Surat</th>
                            <th>Ditujukan Kepada</th>
                            <th>Tanggal Surat</th>
                            <th>Perihal</th>
                            <th>Status Disposisi Surat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suratMasuk as $k => $v)
                            <!-- Modal teruskan ke start -->
                            {{-- <div class="modal modal__section fade" data-bs-backdrop="static" id="teruskanKe"
                                data-bs-backdrop="static" tabindex="-1" aria-labelledby="ex ampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <form class="needs-validation" novalidate method="POST"
                                        action="{{ route('surat-masuk.teruskan', $v->id) }}">
                                        @csrf
                                        <div class="modal-content p-3">
                                            <div class="modal-header">
                                                <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                                                    Teruskan Surat Ke
                                                </h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="nip_penerima" class="form-label black fw-normal">Pilih
                                                        Tujuan</label>
                                                    <select class="form-select" required
                                                        aria-label="Default select example" name="nip_penerima">
                                                        <option selected disabled value="">...</option>
                                                        @foreach ($ditujukanKepada as $item)
                                                            <option value="{{ $item->nip }}">
                                                                {{ $item->nama_jabatan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Masukkan penerima surat dengan benar.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="mybtn light" data-bs-dismiss="modal">
                                                    Batal
                                                </button>
                                                <button type="submit" class="mybtn blue" type="submit">
                                                    Tambah
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> --}}
                            <!-- Modal teruskan ke end -->

                            <tr>
                                <td class="no">{{ $loop->iteration }}</td>
                                <td>
                                    {{ $v->asalSurat }} <br> <span class="pt-2 d-inline-block">Nomor :
                                        {{ $v->nomorSurat }}
                                    </span>
                                </td>
                                <td>{{ $v->nama_jabatan }} <br> <span class="pt-2 d-inline-block">
                                        {{ $v->name }}</td>
                                </span>
                                <td>{{ date('d ', strtotime($v->tanggalPengajuan)) }}{{ convertToBulan(date('F', strtotime($v->tanggalPengajuan))) }}{{ date(' Y', strtotime($v->tanggalPengajuan)) }}
                                </td>
                                <td>
                                    {{ $v->perihal }}
                                </td>
                                <td>{{ $v->status_disposisi }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="mybtn white dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu p-0 py-2">
                                            {{-- @if ($v->status_disposisi === 'Belum Diproses')
                                                <li>
                                                    <buttton type="button" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#teruskanKe">Teruskan</buttton>
                                                </li>
                                            @endif --}}
                                            <li><a class="dropdown-item"
                                                    href="{{ route('surat-masuk.show', $v->id) }}">Detail</a></li>
                                        </ul>
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
    <!-- Data tables start -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js">
    </script>

    {{-- Sweet alert start --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initializing dom start
        $(document).ready(function() {
            // Filter tanggal start
            var start = $('#inputTanggalStart').attr('value')
            var end = $('#inputTanggalEnd').attr('value')

            $('#inputTanggalStart').change(function() {
                // console.log(end)
                start = this.value
                if (start && end) {
                    window.location.href = "{{ route('surat-masuk.index') }}" + '?start=' + start +
                        '&end=' + end;
                }
            })
            $('#inputTanggalEnd').change(function() {
                // console.log(start)
                end = this.value
                if (start && end) {
                    window.location.href = "{{ route('surat-masuk.index') }}" + '?start=' + start +
                        '&end=' + end;
                }
            })
            // Filter tanggal end

            // <!-- Initializing data tables start -->
            $("#mytable").DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, 3, 4, 5]
                }],
                dom: '<"d-flex justify-content-end"f>rt<"d-flex justify-content-between mt-3 flex-wrap gap-2"<"d-flex align-items-center flex-wrap gap-2"li>p>',
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
            // <!-- Initializing data tables end -->

            // {{-- Bootstrap form validation start --}}
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
            // {{-- Bootstrap form validation end --}}

            // <!-- Sweet alert : confirm delete start -->
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
            // <!-- Sweet alert : confirm delete end -->

        })
        // Button testing start
        function fillData() {
            $("input[name='nomorSurat']").val('1')
            $("input[name='asalSurat']").val('a')
            $("#datepicker").datepicker("setDate", "01-03-2024");
            $("select[name='ditujukan_kepada']").val('1')
            $("select[name='sifatSurat']").val('1')
            $("select[name='kodeHal']").val('AK')
            $("input[name='jumlahLampiran']").val(1)
            $("textarea[name='perihal']").val('a')
        }
        // Button testing end
        // Initializing dom end
    </script>
@endsection
