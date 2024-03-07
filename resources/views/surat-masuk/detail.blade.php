@extends('template')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Datepicker Jquery -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/surat-masuk-style.css') }}" />
@endsection
@section('content')
    <section class="surat__masuk content">
        {{-- Alert validasi gagal start --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong class="mb-2 d-inline-block">Aksi gagal!</strong>
                @foreach ($errors->all() as $error)
                    <p class="mb-1" id="errorMessage">
                        {{ $error }}
                    </p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Alert validasi gagal end --}}

        {{-- Alert aksi berhasil start --}}
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        {{-- Alert aksi berhasil end --}}

        {{-- Alert aksi berhasil start --}}
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        {{-- Alert aksi berhasil end --}}

        {{-- Navigation start --}}
        <div class="navigation__content mb-4">
            <div class="d-flex mt-3 justify-content-between align-items-center">
                <h5 class="fw__semi black">SURAT MASUK</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb p-0 bg-transparent mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('surat-masuk.index') }}">Semua Surat Masuk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Surat Masuk</li>
                    </ol>
                </nav>
            </div>
        </div>
        {{-- Navigation end --}}

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

        {{-- Detail content start --}}
        <div class="card p-4 mt-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fs-6 black fw-normal">Tanggal Surat Diterima Admin: <span
                        class="fw-semibold">{{ $surat->created_at }}</span></h3>
                <h3 class="fs-6 black fw-normal">Status Disposisi Surat: <span
                        class="fw-semibold">{{ $surat->status_disposisi }}</span></h3>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="nomorSuratE" class="form-label black fw-normal">Nomor
                            Surat</label>
                        <input readonly type="text" class="form-control" placeholder="Masukkan nomor surat"
                            value="{{ $surat->nomorSurat }}" name="nomorSurat" aria-describedby="emailHelp" />
                    </div>
                    <div class="mb-3">
                        <label for="asalSurat" class="form-label black fw-normal">Asal Surat</label>
                        <input type="text" readonly class="form-control" value="{{ $surat->asalSurat }}" name="asalSurat"
                            aria-describedby="emailHelp" />
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label black fw-normal">Tanggal
                            Surat</label>
                        <div class="position-relative input__tanggal__form">
                            <input identifier="date" readonly class="form-control" name="tanggalPengajuan"
                                value="{{ $surat->tanggalPengajuan }}">
                            <i class="fa-solid fa-calendar-days position-absolute"></i>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="penerima" class="form-label black fw-normal">Ditujukan Kepada</label>
                        <input readonly type="text" class="form-control" name="penerima"
                            value="{{ $surat->nama_jabatan }} ({{ $surat->name }})" aria-describedby="emailHelp" />
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="penerima" class="form-label black fw-normal">Sifat</label>
                        <input readonly type="text" class="form-control" name="penerima"
                            value="{{ $surat->sifat_surat }}" aria-describedby="emailHelp" />
                    </div>
                    <div class="mb-3">
                        <label for="kodeHal" class="form-label black fw-normal">Kode Hal</label>
                        <input readonly type="text" class="form-control" name="penerima"
                            value="{{ $surat->kodeHal }}" aria-describedby="emailHelp" />
                    </div>
                    <div class="mb-3">
                        <label for="jumlahLampiran" class="form-label black fw-normal">Jumlah
                            Lampiran</label>
                        <div class="d-flex gap-3">
                            @if ($surat->jumlahLampiran)
                                <input readonly type="number" class="form-control" name="jumlahLampiran" min="0"
                                    value="{{ $surat->jumlahLampiran }}" />
                            @else
                                <input readonly type="text" class="form-control" name="jumlahLampiran"
                                    value="-" />
                            @endif
                            <button type="button" data-bs-toggle="modal" data-bs-target="#lampiran"
                                onclick="lihatLampiran('{{ $surat->lampiran }}')" class="mybtn white">
                                Lihat
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label black fw-normal">Perihal</label>
                        <textarea id="perihalE" readonly class="form-control perihal" rows="1" name="perihal"
                            style="min-height: unset">{{ $surat->perihal }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        {{-- Detail content end --}}

        <!-- Modal tambah disposisi start -->
        <div class="modal modal__section fade" data-bs-backdrop="static" id="tambahDisposisi" data-bs-backdrop="static"
            tabindex="-1" aria-labelledby="ex ampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="formTambahDisposisi" class="needs-validation" novalidate method="POST"
                    action="{{ route('surat-masuk.disposisiStore', $surat->id) }}">
                    @csrf
                    <div class="modal-content p-3">
                        <div class="modal-header">
                            <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                                Tambah Disposisi Surat
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nip_penerima" class="form-label black fw-normal">Disposisikan Kepada</label>
                                <select class="form-select" aria-label="Default select example" name="nip_penerima">
                                    <option selected disabled value="">...</option>
                                    @foreach ($usersWithJabatan as $item)
                                        @if (old('nip_penerima') == $item->nip)
                                            <option value="{{ $item->nip }}" selected>
                                                {{ $item->nama_jabatan }} ({{ $item->name }})
                                            </option>
                                        @else
                                            <option value="{{ $item->nip }}">
                                                {{ $item->nama_jabatan }} ({{ $item->name }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Penerima disposisi wajib diisi.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_disposisi" class="form-label black fw-normal">Tanggal
                                    Surat Didisposisikan</label>
                                <div class="position-relative input__tanggal__form">
                                    <input type="text" id="datepicker" identifier="date" placeholder="..."
                                        name="tanggal_disposisi" aria-placeholder="coba" class="form-control"
                                        value="" required>
                                    <i class="fa-solid fa-calendar-days position-absolute"></i>
                                    <div class="invalid-feedback">
                                        Tanggal surat didisposisikan wajib diisi.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="id_tindak_lanjut" class="form-label black fw-normal">Tindak Lanjut</label>
                                <select class="form-select" required aria-label="Default select example"
                                    name="id_tindak_lanjut">
                                    <option selected disabled value="">...</option>
                                    @foreach ($tindakLanjuts as $item)
                                        @if (old('id_tindak_lanjut') == $item->id)
                                            <option value="{{ $item->id }}" selected>
                                                {{ $item->deskripsi }}
                                            </option>
                                        @else
                                            <option value="{{ $item->id }}">
                                                {{ $item->deskripsi }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Tindak lanjut wajib diisi.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="form-label black fw-normal">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="4" placeholder="" required>{{ old('keterangan') }}</textarea>
                                <div class="invalid-feedback">
                                    Keterangan wajib diisi.
                                </div>
                            </div>
                            <input type="hidden" name="nip_pengirim" value="{{ $user->nip }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="mybtn light" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="button" id="btnTambah" onclick="confirmTambahDisposisi()" class="mybtn blue">
                                Tambah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal tambah disposisi end -->

        {{-- Disposisi tabel start --}}
        <div class="p-4 mt-4 card d-block">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <h4 class="fw-semibold black">Disposisi Surat</h4>
                @if ($user->id_jabatan !== 7 && $surat->status_disposisi !== 'Selesai')
                    @if (in_array($user->id_jabatan, [1, 2, 3]))
                        <button type="button" data-bs-toggle="modal" data-bs-target="#tambahDisposisi"
                            class="mybtn blue">
                            <i class="fa-solid fa-plus me-2"></i>Tambah Disposisi
                        </button>
                    @elseif(!$userTelahDispo)
                        <button type="button" data-bs-toggle="modal" data-bs-target="#tambahDisposisi"
                            class="mybtn blue">
                            <i class="fa-solid fa-plus me-2"></i>Tambah Disposisi
                        </button>
                    @endif
                @endif
            </div>
            <div class="table-responsive">
                <table id="mytable" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="no">#</th>
                            <th>Tanggal Surat Didisposisikan</th>
                            <th>Pengirim Disposisi</th>
                            <th>Penerima Disposisi</th>
                            <th>Tindak Lanjut</th>
                            <th>Keterangan</th>
                        </tr>
                    <tbody>
                        @if (count($disposisis) !== 0)
                            @foreach ($disposisis as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->tanggal_disposisi }}</td>
                                    <td>{{ $item->jabatan_pengirim }}</td>
                                    <td>{{ $item->jabatan_penerima }}</td>
                                    <td>{{ $item->deskripsi }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">Disposisi tidak tersedia.</td>
                            </tr>
                        @endif
                    </tbody>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            @if ($user->id_jabatan !== 7 && $surat->status_disposisi !== 'Selesai')
                <form id="formSelesaikanDisposisi" class="needs-validation mt-4" novalidate method="POST"
                    action="{{ route('surat-masuk.disposisiEnd', $surat->id) }}">
                    @csrf
                    <button type="button" class="mybtn green ms-auto" onclick="confirmSelesaikanDisposisi()">
                        <i class="fa-solid fa-check-to-slot me-2"></i>Selesaikan Disposisi
                    </button>
                </form>
            @endif
        </div>
        {{-- Disposisi tabel end --}}

    </section>
@endsection
@section('sm', 'active')
@section('title', 'Surat Masuk')
@section('js')
    {{-- Sweet alert cdn start --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initializing datapicker start
        $("#datepicker").datepicker();
        // Setter
        $("#datepicker").datepicker("option", "changeYear", true);
        $("#datepicker").datepicker("option", "dateFormat", "dd-mm-yy");
        // Cek apakah ada old value tanggal kadaluarsa
        let oldDate = "{{ old('tanggal_disposisi') }}";
        if (oldDate) {
            // Tetapkan nilai Datepicker dari old value
            $("#datepicker").datepicker("setDate", oldDate);
        }
        // Initializing datapicker end

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

        // {{-- Fungsi lihat lampiran start --}}
        function lihatLampiran(id) {
            var options = {
                fallbackLink: "<p>Silahkan lihat arsip dokumen surat melalui link berikut. <a href='[url]'>Lihat arsip.</a></p>"
            };
            PDFObject.embed(`{{ asset('public/uploads/${id}') }}`, "#example1", options);
        }
        // {{-- Fungsi lihat lampiran end --}}

        // Capitalize first error message start
        @if ($errors->any())
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
            }
            var errorMessage = document.getElementById('errorMessage').innerHTML;
            errorMessage = errorMessage.trim()
            errorMessage = errorMessage.replace(/_/g, ' ')
            document.getElementById('errorMessage').innerHTML = capitalizeFirstLetter(errorMessage);
        @endif
        // Capitalize first error message start

        // Sweetalert confirm delete start
        function confirmTambahDisposisi() {
            new Audio("{{ asset('audio/warning-edited.mp3') }}").play();
            Swal.fire({
                title: "Yakin ingin menambah data?",
                text: "Pastikan data yang anda masukkan benar!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2F5596",
                cancelButtonColor: "#d33",
                confirmButtonText: "Tambah",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formTambahDisposisi').submit();
                } else {
                    new Audio("{{ asset('audio/cancel-edited.mp3') }}").play();
                }
            });
        }
        // Sweetalert confirm delete end

        // Sweetalert confirm selesaikan disposisi start
        function confirmSelesaikanDisposisi() {
            new Audio("{{ asset('audio/warning-edited.mp3') }}").play();
            Swal.fire({
                title: "Yakin ingin menyelesaikan disposisi?",
                text: "Pastikan data yang anda masukkan benar!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#2F5596",
                cancelButtonColor: "#d33",
                confirmButtonText: "Tambah",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formSelesaikanDisposisi').submit();
                } else {
                    new Audio("{{ asset('audio/cancel-edited.mp3') }}").play();
                }
            });
        }
        // Sweetalert confirm selesaikan disposisi end
    </script>
@endsection
