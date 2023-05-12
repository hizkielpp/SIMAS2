@extends('template')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap data tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />

    <!-- Datepicker Jquery -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    {{-- Datepicker Jquery : input tanggal start --}}
    <script>
        $(function() {
            // Initializing
            $("#inputTanggalStart").datepicker();

            // Ganti tahun
            $("#inputTanggalStart").datepicker({
                changeYear: true
            });

            // Getter
            var changeYear = $("#inputTanggalStart").datepicker("option", "changeYear");

            // Setter
            $("#inputTanggalStart").datepicker("option", "changeYear", true);
        });
    </script>

    {{-- Datepicker Jquery : input tanggal end --}}
    <script>
        $(function() {
            // Initializing
            $("#inputTanggalEnd").datepicker();

            // Ganti tahun
            $("#inputTanggalEnd").datepicker({
                changeYear: true
            });

            // Getter
            var changeYear = $("#inputTanggalEnd").datepicker("option", "changeYear");

            // Setter
            $("#inputTanggalEnd").datepicker("option", "changeYear", true);
        });
    </script>

    {{-- Datepicker Jquery : registrasi surat --}}
    <script>
        $(function() {
            // Initializing
            $("#datepicker").datepicker();

            // Ganti tahun
            $("#datepicker").datepicker({
                changeYear: true
            });

            // Getter
            var changeYear = $("#datepicker").datepicker("option", "changeYear");

            // Setter
            $("#datepicker").datepicker("option", "changeYear", true);
        });
    </script>

    {{-- Datepicker Jquery : edit surat --}}
    <script>
        $(function() {
            // Initializing
            $("#tanggalPengajuanE").datepicker();

            // Ganti tahun
            $("#tanggalPengajuanE").datepicker({
                changeYear: true
            });

            // Getter
            var changeYear = $("#tanggalPengajuanE").datepicker("option", "changeYear");

            // Setter
            $("#tanggalPengajuanE").datepicker("option", "changeYear", true);
        });
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/surat-masuk-style.css" />
@endsection
@section('content')
    <section class="surat__masuk content">
        {{-- Keterangan start --}}
        <div class="card p-4 mb-md-0 keterangan">
            <h4 class="fw__bold black mb-3">Keterangan</h4>
            <h5 class="fw__normal black__light mb-3">
                Surat masuk wajib dilakukan registrasi. Registrasi surat dilakukan
                dengan sistem dalam melakukan kearsipan surat. Operator dapat
                melakukan registrasi surat masuk dan penerusan ke pimpinan agar
                dapat menindak lanjuti surat tersebut. Anda dapat mendownload
                "Tata Naskah Dinas di Lingkungan Universitas Diponegoro" sebagai
                pedoman dalam membuat nomor surat dengan menekan tombol dibawah
                ini.
            </h5>
            <a href="{{ route('downloadNaskah') }}" class="mybtn blue fw__light"><i class="fa-solid fa-download me-1"></i>
                Download
            </a>
        </div>
        {{-- Keterangan start --}}

        {{-- Tabel wrapper start --}}
        <div class="card p-4 mt-3">

            {{-- Tabel header start --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <h4 class="fw__bold black">Daftar Surat Keluar</h4>
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

            <!-- Modal registrasi start -->
            <div class="modal modal__section fade" id="registrasiSuratMasuk" tabindex="-1"
                aria-labelledby="ex ampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content p-3">
                        <div class="modal-header">
                            <h4 class="modal-title fw__bold black" id="exampleModalLabel">
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
                                            <label for="noSurat" class="form-label black fw-semibold">Nomor Surat</label>
                                            <input type="text" class="form-control"
                                                placeholder="Contoh : 4/UN7.F4/2015/X" id="noSurat" name="nomorSurat"
                                                aria-describedby="emailHelp" required />
                                            <div class="invalid-feedback">
                                                Mohon masukkan nomor surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="penerima" class="form-label black fw-semibold">Penerima</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="tujuanSurat" required>
                                                <option selected disabled value="">...</option>
                                                @foreach ($tujuan as $k => $v)
                                                    <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Mohon masukkan penerima surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date" class="form-label black fw-semibold">Tanggal
                                                Pengajuan</label>
                                            <div class="position-relative input__tanggal__form">
                                                <input type="text" id="datepicker" identifier="date" placeholder="..."
                                                    name="tanggalPengajuan" aria-placeholder="coba" class="form-control"
                                                    required>
                                                <i class="fa-solid fa-calendar-days position-absolute"></i>
                                            </div>
                                            <div class="invalid-feedback">
                                                Mohon masukkan tanggal pengajuan dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="asalSurat" class="form-label black fw-semibold">Asal Surat</label>
                                            <input type="text" class="form-control" id="asalSurat" name="asalSurat"
                                                placeholder="Contoh : Ketua Departemen Kedokteran"
                                                aria-describedby="emailHelp" required />
                                            <div class="invalid-feedback">
                                                Mohon masukkan asal surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kodeHal" class="form-label black fw-semibold">Kode Hal</label>
                                            <select class="form-select" aria-label="Default select example"
                                                id="kodeHal" name="kodeHal" required>
                                                <option selected disabled value="">...</option>
                                                @foreach ($hal as $k => $v)
                                                    <option value="{{ $v->kode }}">{{ $v->nama }}
                                                        ({{ $v->kode }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Mohon masukkan kode hal surat dengan benar.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="penerima" class="form-label black fw-semibold">Sifat</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="sifatSurat" required>
                                                <option selected disabled value="">...</option>
                                                @foreach ($sifat as $k => $v)
                                                    <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Mohon masukkan sifat surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lampiran" class="form-label black fw-semibold">Upload
                                                Lampiran</label>
                                            <input type="file" class="form-control" id="lampiran" name="lampiran"
                                                aria-describedby="emailHelp" required />
                                            <div class="invalid-feedback">
                                                Mohon upload lampiran dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1"
                                                class="form-label black fw-semibold">Perihal</label>
                                            <textarea class="form-control perihal" id="exampleFormControlTextarea1" rows="4"
                                                placeholder="Contoh : Permohonan perijinan penelitian" name="perihal" required></textarea>
                                            <div class="invalid-feedback">
                                                Mohon masukkan perihal surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jumlahLampiran" class="form-label black fw-semibold">Jumlah
                                                Lampiran</label>
                                            <input type="number" class="form-control" placeholder="Contoh : 1"
                                                id="jumlahLampiran" name="jumlahLampiran" required />
                                            <div class="invalid-feedback">
                                                Mohon masukkan jumlah lampiran surat dengan benar.
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
            <div class="modal modal__section fade" id="editSuratMasuk" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content p-3">
                        <div class="modal-header">
                            <h4 class="modal-title fw__bold black" id="exampleModalLabel">
                                Form Edit Surat Masuk
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
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
                                            <label for="nomorSuratE" class="form-label black fw-semibold">Nomor
                                                Surat</label>
                                            <input type="text" class="form-control" placeholder="Masukkan nomor surat"
                                                id="nomorSuratE" name="nomorSurat" aria-describedby="emailHelp" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="penerima" class="form-label black fw-semibold">Penerima</label>
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
                                            <label for="date" class="form-label black fw-semibold">Tanggal
                                                Pengajuan</label>
                                            <div class="position-relative input__tanggal__form">
                                                <input identifier="date" class="form-control" name="tanggalPengajuan"
                                                    id="tanggalPengajuanE" value="2020-06-16">
                                                <i class="fa-solid fa-calendar-days position-absolute"></i>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="asalSurat" class="form-label black fw-semibold">Asal Surat</label>
                                            <input type="text" class="form-control" id="asalSuratE" name="asalSurat"
                                                placeholder="Contoh : Ketua Departemen Kedokteran"
                                                aria-describedby="emailHelp" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="kodeHal" class="form-label black fw-semibold">Kode Hal</label>
                                            <select class="form-select" aria-label="Default select example"
                                                id="kodeHalE" name="kodeHal">
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
                                            <label for="penerima" class="form-label black fw-semibold">Sifat</label>
                                            <select class="form-select" id="sifatSuratE"
                                                aria-label="Default select example" name="sifatSurat">
                                                <option selected>
                                                    ...
                                                </option>
                                                @foreach ($sifat as $k => $v)
                                                    <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lampiran" class="form-label black fw-semibold">Upload
                                                Lampiran</label>
                                            <input type="file" class="form-control" id="lampiran"
                                                aria-describedby="emailHelp" name="lampiran" />
                                            <span>Nama file lampiran : </span><span id="lampiranE"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1"
                                                class="form-label black fw-semibold">Perihal</label>
                                            <textarea id="perihalE" class="form-control perihal" id="exampleFormControlTextarea1" rows="1"
                                                placeholder="Contoh : Permohonan perijinan penelitian" name="perihal"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jumlahLampiran" class="form-label black fw-semibold">Jumlah
                                                Lampiran</label>
                                            <input id="jumlahLampiranE" type="number" class="form-control"
                                                placeholder="Masukkan nomor surat" id="jumlahLampiranE"
                                                name="jumlahLampiran" />
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
                                Simpah
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
                            <h4 class="modal-title fw__bold black" id="exampleModalLabel">
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
                            <th class="no">No</th>
                            <th>Asal Surat / No. Surat</th>
                            <th>Perihal</th>
                            <th>Penerima</th>
                            <th>Sifat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 0; ?>
                        @foreach ($suratMasuk as $k => $v)
                            <?php $number++; ?>
                            <tr>
                                <td class="no">{{ $number }}</td>
                                <td>
                                    {{ $v->asalSurat }} <br>Nomor :
                                    {{ $v->nomorSurat }}
                                    <br />
                                    <span
                                        class="date d-inline-block mt-1">{{ date('d M Y h:i:s', strtotime($v->created_at)) }}
                                        WIB</span>
                                </td>
                                <td class="fw__normal">{{ $v->perihal }}</td>
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
                                    <div class="d-flex align-items-center">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratMasuk"
                                            class="myicon blue d-flex align-items-center justify-content-center me-2 passId"
                                            data-id="{{ $v->id }}">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button type="button"
                                            class="myicon red d-flex align-items-center justify-content-center me-2"
                                            onclick="confirmHapus('{{ $v->id }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <a data-id="{{ $v->id }}" data-bs-toggle="modal"
                                            data-bs-target="#disposisi"
                                            class="test myicon green d-flex align-items-center justify-content-center"
                                            onclick="showDisposisi('{{ $v->id }}')">
                                            <i class="fa-solid disposisi fa-file-export"></i>
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
    @if ($errors->any())
        <script>
            gagal()
        </script>
        {{ implode('', $errors->all('<div>:message</div>')) }}
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
                // console.log(start)
                // console.log(end)
                start = this.value
                if (start && end) {
                    window.location.href = "{{ route('suratMasuk') }}" + '?start=' + start + '&end=' + end;
                }
            })
            $('#inputTanggalEnd').change(function() {
                // console.log(start)
                // console.log(end)
                end = this.value
                if (start && end) {
                    window.location.href = "{{ route('suratMasuk') }}" + '?start=' + start + '&end=' + end;
                }
            })
            $(".passId").click(function() {
                let url = "{{ route('getSM', ':id') }}";
                url = url.replace(':id', $(this).data('id'));
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        $('#nomorSuratE').attr('value', data.nomorSurat)
                        $("#tujuanSuratE").val(data.tujuanSurat)
                        $('#tanggalPengajuanE').val(data.tanggalPengajuan)
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
        });
    </script>

    @if (isset($_GET['start']) and isset($_GET['end']))
        <script>
            start = "{{ $_GET['start'] }}"
            end = "{{ $_GET['end'] }}"
            $('#inputTanggalStart').attr('value', start)
            $('#inputTanggalEnd').attr('value', end)
        </script>
    @endif

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
        $(document).ready(function() {
            $('.disposisi').on('click', function() {

            })
            $("#mytable").DataTable({
                // paging: false,
                // ordering: false,
                // searching: false,
                // responsive: true,
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: "none",
                        target: "",
                    },
                },
                dom: '<"d-flex justify-content-between align-item-center gap-3 flex-wrap"Bf>rt<"d-flex justify-content-between mt-3 overflow-hidden"<"d-flex align-items-center"li>p>',
                buttons: [{
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'mybtn btn__export'
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'mybtn btn__export'
                    }
                ],
                destroy: true,
                order: [
                    [0, "desc"]
                ],
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
                    [5, 10, 20, -1],
                    [5, 10, 20, "All"],
                ],
            });
        });
    </script>
    <!-- Initializing data tables end -->

    {{-- Add keterangan button export start --}}
    <script>
        $(document).ready(function() {
            var btn = document.querySelector(".dt-buttons")
            var descText = document.createElement('h5')
            descText.textContent = "Export :";
            descText.className = "desc__export"
            btn.insertBefore(descText, btn[0]);

            $('.test').on('click', function() {

            })

        })
    </script>
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

@endsection
