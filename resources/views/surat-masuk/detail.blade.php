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
            <h3 class="ms-auto fs-6 mb-3 black fw-normal">Status Surat: <span
                    class="fw-semibold">{{ $surat->status_disposisi }}</span></h3>
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
                        <input readonly type="text" class="form-control" name="penerima" value="{{ $surat->kodeHal }}"
                            aria-describedby="emailHelp" />
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
                <form class="needs-validation" novalidate method="POST"
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
                                <select class="form-select" required aria-label="Default select example"
                                    name="nip_penerima">
                                    <option selected disabled value="">...</option>
                                    @foreach ($usersWithJabatan as $item)
                                        <option value="{{ $item->nip }}">
                                            {{ $item->nama_jabatan }} ({{ $item->name }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Masukkan penerima surat dengan benar.
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
                                </div>
                                <div class="invalid-feedback">
                                    Masukkan tanggal pengajuan dengan benar.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="id_tindak_lanjut" class="form-label black fw-normal">Tindak Lanjut</label>
                                <select class="form-select" required aria-label="Default select example"
                                    name="id_tindak_lanjut">
                                    <option selected disabled value="">...</option>
                                    @foreach ($tindakLanjuts as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->deskripsi }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Masukkan tindak lanjut surat dengan benar.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="isi_disposisi" class="form-label black fw-normal">Keterangan</label>
                                <input type="text" class="form-control" placeholder="" name="isi_disposisi"
                                    aria-describedby="emailHelp" required />
                                <div class="invalid-feedback">
                                    Masukkan keterangan disposisi dengan benar.
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
        </div>
        <!-- Modal tambah disposisi end -->

        {{-- Disposisi tabel start --}}
        <div class="card p-4 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <h4 class="fw-semibold black">Disposisi Surat</h4>
                @if ($user->id_jabatan !== 7)
                    <button type="button" data-bs-toggle="modal" data-bs-target="#tambahDisposisi" class="mybtn blue">
                        <i class="fa-solid fa-plus me-2"></i>Tambah Disposisi
                    </button>
                @endif
            </div>
            <div class="table-responsive">
                <table id="mytable" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="no">#</th>
                            <th>Tanggal Surat Didisposisikan</th>
                            <th>Penerima Disposisi</th>
                            <th>Tindak Lanjut</th>
                            <th>Isi</th>
                        </tr>
                    <tbody>
                        @foreach ($disposisis as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->tanggal_disposisi }}</td>
                                <td>{{ $item->nama_jabatan }} ({{ $item->tujuan }})</td>
                                <td>{{ $item->deskripsi }}</td>
                                <td>{{ $item->isi_disposisi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Disposisi tabel end --}}

    </section>
@endsection
@section('sm', 'active')
@section('title', 'Surat Masuk')
@section('js')
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
    </script>
@endsection
