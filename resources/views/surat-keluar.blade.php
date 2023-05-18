@extends('template')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <script>
        function showLampiran(id) {
            $('#iframeLampiran').attr('src', `{{ url('/uploads/${id}') }}`)
        }

        function showDate(val) {
            console.log(val)
        }
    </script>

    <!-- Bootstrap data tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/surat-masuk-style.css" />
@endsection
@section('content')
    <section class="surat__masuk content">
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
        <div class="card p-4 mt-3">

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
                            <iframe src="" id="iframeLampiran" frameborder="0" style="width:100%;"
                                class="h-100"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal lampiran surat end -->

            <!-- Modal edit start -->
            <div class="modal modal__section fade" id="editSuratKeluar" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content p-3">
                        <div class="modal-header">
                            <h4 class="modal-title fw__bold black" id="exampleModalLabel">
                                Form Edit Surat Keluar
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEdit" method="POST" enctype="multipart/form-data"
                                action="{{ route('editSK') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <input type="text"name="jenisSurat" value="biasa" hidden>
                                        <input type="text" name="idSurat" hidden>
                                        <div class="mb-3">
                                            <label for="kodeUnitE" class="form-label black fw-semibold">Kode Unit
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
                                            <label for="kodeHalE" class="form-label black fw-semibold">Kode Hal</label>
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
                                            <label for="tujuanSuratE" class="form-label black fw-semibold">Tujuan
                                                Surat</label>
                                            <input type="text" class="form-control" placeholder="Masukkan nomor surat"
                                                id="tujuanSuratE" name="tujuanSurat" aria-describedby="emailHelp" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="sifatSuratE" class="form-label black fw-semibold">Sifat</label>
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
                                        <div class="mb-3">
                                            <label for="jumlahLampiranE" class="form-label black fw-semibold">Jumlah
                                                Lampiran</label>
                                            <input type="number" class="form-control"
                                                placeholder="Masukkan jumlah lampiran" id="jumlahLampiranE"
                                                name="jumlahLampiran" aria-describedby="emailHelp" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="disahkanOlehE" class="form-label black fw-semibold">Disahkan
                                                Oleh</label>
                                            <select id="disahkanOlehE" name="disahkanOleh" class="form-select"
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
                                            <label for="tanggalPengesahanE" class="form-label black fw-semibold">Tanggal
                                                Disahkan</label>
                                            {{-- <duet-date-picker id="tanggalPengesahanE" identifier="date"
                                                name="tanggalPengesahan"></duet-date-picker> --}}
                                            <div class="position-relative input__tanggal__form">
                                                <input type="text" id="datepickerEdit" identifier="date"
                                                    placeholder="..." name="tanggalPengesahanE" aria-placeholder="coba"
                                                    onchange="showDate(this.value)" class="form-control" required>
                                                <i class="fa-solid fa-calendar-days position-absolute"></i>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label black fw-semibold">Upload Lampiran</label>
                                            <input type="file" class="form-control" name="lampiran"
                                                aria-describedby="emailHelp" />
                                            <span>Nama file lampiran : </span><span id="lampiranE"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="perihalE" class="form-label black fw-semibold">Perihal</label>
                                            <textarea class="form-control perihal" id="perihalE" name="perihal" rows="2"
                                                placeholder="Contoh : Permohonan perijinan penelitian"></textarea>
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

            <!-- Modal registrasi start -->
            <div class="modal modal__section fade" id="registrasiSuratKeluar" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content p-3">
                        <div class="modal-header">
                            <h4 class="modal-title fw__bold black" id="exampleModalLabel">
                                Form Registrasi Surat Keluar
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formRegistrasi" class="needs-validation" novalidate
                                action="{{ route('inputSK') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="nomorSurat" class="form-label black fw-semibold">Nomor
                                                Surat</label>
                                            <div class="input d-flex align-items-center">
                                                <input type="text" readonly class="form-control"
                                                    placeholder="Nomor surat" id="nomorSurat"
                                                    aria-describedby="emailHelp" name="nomorSurat" required />
                                                <button type="button" class="ms-2 ambilNomor">
                                                    Ambil Nomor <i class="fas fa-search ms-1"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback">
                                                Mohon masukkan nomor surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kodeUnit" class="form-label black fw-semibold">Kode Unit
                                                Surat</label>
                                            <select id="kodeUnit" name="kodeUnit" class="form-select"
                                                aria-label="Default select example" required>
                                                <option value=""selected disabled value="">...</option>
                                                @foreach ($unit as $k => $v)
                                                    <option value="{{ $v->kode }}">{{ $v->nama }}
                                                        ({{ $v->kode }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Mohon masukkan kode unit surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kodeHal" class="form-label black fw-semibold">Kode Hal</label>
                                            <select class="form-select" id="kodeHal"
                                                aria-label="Default select example" name="kodeHal" required>
                                                <option value="" selected disabled value="">...</option>
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
                                        <div class="mb-3">
                                            <label for="tujuanSurat" class="form-label black fw-semibold">Tujuan
                                                Surat</label>
                                            <input type="text" class="form-control"
                                                placeholder="Contoh : Fakultas Kedokteran" id="tujuanSurat"
                                                name="tujuanSurat" aria-describedby="emailHelp" required />
                                            <div class="invalid-feedback">
                                                Mohon masukkan tujuan surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jumlahLampiran" class="form-label black fw-semibold">Jumlah
                                                Lampiran</label>
                                            <input type="number" class="form-control" placeholder="Contoh : 1"
                                                id="jumlahLampiran" name="jumlahLampiran" aria-describedby="emailHelp"
                                                required />
                                            <div class="invalid-feedback">
                                                Mohon masukkan jumlah lampiran surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sifatSurat" class="form-label black fw-semibold">Sifat</label>
                                            <select class="form-select" id="sifatSurat" name="sifatSurat"
                                                aria-label="Default select example" required>
                                                <option value=""selected disabled value="">...</option>
                                                @foreach ($sifat as $k => $v)
                                                    <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Mohon masukkan sifat surat dengan benar.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="disahkanOleh" class="form-label black fw-semibold">Disahkan
                                                Oleh</label>
                                            <select class="form-select" aria-label="Default select example"
                                                id="disahkanOleh" name="disahkanOleh" required>
                                                <option selected disabled value="">...</option>
                                                @foreach ($unit as $k => $v)
                                                    <option value="{{ $v->nama }}">{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Mohon masukkan unit dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggalPengesahan" class="form-label black fw-semibold">Tanggal
                                                Disahkan</label>
                                            <div class="position-relative input__tanggal__form">
                                                <input type="text" id="datepicker" identifier="date"
                                                    placeholder="..." name="tanggalPengesahan" aria-placeholder="coba"
                                                    class="form-control" required>
                                                <i class="fa-solid fa-calendar-days position-absolute"></i>
                                            </div>
                                            <div class="invalid-feedback">
                                                Mohon masukkan tanggal pengesahan surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lampiran" class="form-label black fw-semibold">Upload
                                                Lampiran</label>
                                            <input type="file" class="form-control" id="lampiran" name="lampiran"
                                                aria-describedby="emailHelp" required />
                                            <div class="invalid-feedback">
                                                Mohon masukkan lampiran dengan benar.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1"
                                                class="form-label black fw-semibold">Perihal</label>
                                            <textarea name="perihal" class="form-control perihal" id="exampleFormControlTextarea1" rows="8"
                                                placeholder="Contoh : Permohonan perijinan penelitian" required></textarea>
                                            <div class="invalid-feedback">
                                                Mohon masukkan perihal surat dengan benar.
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
                            <button type="submit" class="mybtn blue" form="formRegistrasi">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal registrasi end -->

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
                    <button id="tes" type="button" data-bs-toggle="modal" data-bs-target="#registrasiSuratKeluar"
                        class="mybtn blue">
                        <i class="fa-solid fa-plus me-2"></i>Registrasi Surat
                    </button>
                </div>
            </div>
            {{-- Tabel header end --}}

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
                        @foreach ($suratKeluar as $k => $v)
                            <tr>
                                <td class="no">{{ $k + 1 }}</td>
                                <td>
                                    {{ $v->disahkanOleh }} <br>Nomor :
                                    {{ $v->nomorSurat }}/{{ $v->kodeUnit }}/{{ date('Y', strtotime($v->tanggalPengesahan)) }}/{{ convertToRomawi(date('m', strtotime($v->tanggalPengesahan))) }}
                                    <br />
                                    <span
                                        class="date d-inline-block mt-1">{{ date('d M Y h:i', strtotime($v->created_at)) }}
                                        WIB</span>
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
                                        <button type="button" class="mybtn light" data-bs-toggle="modal"
                                            data-bs-target="#lampiran" onclick="showLampiran('{{ $v->lampiran }}')">
                                            Lihat lampiran
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratKeluar"
                                            class="myicon blue d-flex align-items-center justify-content-center passId"
                                            data-id="{{ $v->id }}">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button type="button"
                                            class="myicon red d-flex align-items-center justify-content-center"
                                            onclick="confirmHapus('{{ $v->id }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Sweet alert -->
    <script src="sweetalert2.all.min.js"></script>

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
                        console.log(data);
                        $('#mytable').DataTable().destroy();
                        data.forEach((value, index) => {
                            console.log(value.disahkanOleh);
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
                            console.log(value)
                        });
                        console.log(htmlNew);
                        // $('#mytable').DataTable().draw();
                    }
                });
            });
            // setInterval('location.reload()', 4000);
        }
    </script>
    <!-- Sweet alert : confirm delete -->
    <script>
        function confirmHapus(id) {
            // console.log(id)
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
            nomorSurat = $('input[name="nomorSurat"]').attr('value')
            var url = '{{ route('cekTersedia', ':id') }}';
            url = url.replace(':id', $('input[name="nomorSurat"]').attr('value'));
            url += "?sumber=keluar&jenis=biasa"
            $.ajax({
                type: 'GET',
                url: url,
                statusCode: {
                    400: function() {
                        Swal.fire("Gagal!", "Error.", "error");
                    },
                    201: function() {
                        Swal.fire("Gagal!", "Nomor sudah terpakai silahkan ambil nomor lagi.", "error");
                    },
                    200: function() {
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
                                document.getElementById('formRegistrasi').submit();
                                $("#registrasiSuratKeluar").modal("hide");
                            } else {
                                new Audio("audio/cancel-edited.mp3").play();
                                $("#registrasiSuratKeluar").modal("hide");
                            }
                        });
                    }
                },
            });

        }
    </script>

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
    <script>
        $(document).ready(function() {
            var start = $('#inputTanggalStart').attr('value')
            var end = $('#inputTanggalEnd').attr('value')
            oke = false
            $('#inputTanggalStart').change(function() {
                console.log(start)
                console.log(end)
                start = this.value
                if (start && end) {
                    window.location.href = '{{ route('suratKeluar') }}' + '?start=' + start + '&end=' +
                        end;
                }
            })
            $('#inputTanggalEnd').change(function() {
                console.log(start)
                console.log(end)
                end = this.value
                if (start && end) {
                    window.location.href = '{{ route('suratKeluar') }}' + '?start=' + start + '&end=' +
                        end;
                }
            })
            $(".passId").click(function() {
                let url = "{{ route('getSK', ':id') }}";
                url = url.replace(':id', $(this).data('id'));
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        console.log(data)
                        $('input[name="jenisSurat"]').val('biasa')
                        $("#tujuanSuratE").val(data.tujuanSurat)
                        tanggal = new Date(data.tanggalPengesahan)
                        y = tanggal.getFullYear()
                        m = parseInt(tanggal.getMonth()) + 1
                        d = tanggal.getDate()
                        console.log(`0${m}/${d}/${y}`)
                        // Ganti tahun
                        $("#datepickerEdit").datepicker(
                            'setDate',
                            `0${m}/${d}/${y}`);
                        // console.log(data.tanggalPengesahan)
                        $('#tanggalPengesahanE').val(`0${m}/${d}/${y}`)
                        $('#tujuanSuratE').attr('value', data.tujuanSurat)
                        $('#perihalE').val(data.perihal)
                        $("#kodeHalE").val(data.kodeHal)
                        $("#kodeUnitE").val(data.kodeUnit)
                        $("#disahkanOlehE").val(data.disahkanOleh)
                        $('#sifatSuratE').val(data.sifatSurat)
                        $('#jumlahLampiranE').val(data.jumlahLampiran)
                        $('#lampiranE').html(data.lampiran)
                    }
                });
                $('input[name="idSurat"]').attr('value', $(this).data('id'));
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
        var btn = document.getElementById("tes");
        $(document).ready(function() {
            $("#mytable").DataTable({
                // paging: false,
                // ordering: false,
                // searching: false,
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: "none",
                        target: "",
                    },
                },
                // responsive: false,
                // dom: '<t<"d-flex align-items-center justify-content-between mt-3"<"d-flex align-items-center"li><"right"p>>>',
                // dom: '<"table-responsive"tpf>',
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

                    },
                ],
                destroy: true,
                order: [
                    [0, "asc"]
                ],
                language: {
                    lengthMenu: "Tampilkan _MENU_",
                    zeroRecords: "Anda belum mengupload laporan kegiatan. <br>Silahkan upload laporan kegiatan terlebih dahulu.",
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
                    [10, 20, "All"],
                ],
            });
        });
    </script>

    {{-- Add keterangan button export start --}}
    <script>
        $(document).ready(function() {
            var btn = document.querySelector(".dt-buttons")
            var descText = document.createElement('h5')
            descText.textContent = "Export :";
            descText.className = "desc__export"
            btn.insertBefore(descText, btn[0]);

        })
    </script>
    {{-- Add keterangan button export end --}}

    <script>
        function berhasil(txt) {
            new Audio("audio/success-edited.mp3").play();
            // Swal.fire("Berhasil!", `${txt}`, "success");
            Swal.fire({
                confirmButtonColor: "#2F5596",
                icon: 'success',
                title: `berhasil`,
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
    @if ($errors->any())
        <script>
            gagal('data tidak boleh kosong')
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
@section('sk', 'active')
@section('title', 'Surat Keluar')
