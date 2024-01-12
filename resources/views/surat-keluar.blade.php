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
    <link rel="stylesheet" href="{{ asset('css/surat-masuk-style.css') }}" />

@endsection
@section('content')
    <section class="surat__masuk content">
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

        {{-- Alert nomor surat sudah digunakan start --}}
        @if (session()->has('registrasiFailed'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong id="headerGagal">Aksi gagal!</strong>
                <p class="mt-2">
                    {{ session('registrasiFailed') }}
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Alert nomor surat sudah digunakan end --}}

        <div class="card p-4 mt-3 position-relative surat__keluar">
            {{-- Loader start --}}
            <div class="loader__tables w-100 h-100 position-absolute justify-content-center align-items-center"
                style="z-index: 9; backdrop-filter: blur(4px); background-color: rgba(256, 256, 256, .8); display: flex; border-radius: .3rem; top: 0; left: 0;">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <div class="lds-dual-ring"></div>
                    <p class="mt-3">Mohon tunggu sebentar</p>
                </div>
            </div>
            {{-- Loader end --}}

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

            <!-- Modal detail & edit start -->
            <div class="modal modal__section fade" id="editSuratKeluar" data-bs-backdrop="static" tabindex="-1"
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
                                Detail Surat Keluar
                            </h4>
                            <button type="button" onclick="batalHandling()" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEdit" method="POST" enctype="multipart/form-data"
                                action="{{ route('editSK') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <input type="text" name="jenisSurat" value="biasa" hidden>
                                        <input type="text" name="idSurat" hidden>
                                        <div class="mb-3" id="unitPengesahanKanan">
                                            <label for="disahkanOlehE" class="form-label black fw-normal">Disahkan
                                                Oleh</label>
                                            <select id="disahkanOlehE2" class="form-select" aria-label="edited" disabled>
                                                <option value="" selected>
                                                    -- Pilih salah satu --
                                                </option>
                                                @foreach ($unit as $k => $v)
                                                    <option value="{{ $v->nama }}">{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kodeUnitE" class="form-label black fw-normal">Kode Unit
                                                Surat</label>
                                            <select class="form-select" id="kodeUnitE" name="kodeUnit" aria-label="edited"
                                                disabled>
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
                                            <select class="form-select" id="kodeHalE" name="kodeHal"
                                                aria-label="edited" disabled>
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
                                            <label for="tanggalPengesahanE" class="form-label black fw-normal">Tanggal
                                                Disahkan</label>
                                            <div class="position-relative input__tanggal__form">
                                                <input type="text" id="datepickerEdit" identifier="date"
                                                    placeholder="..." name="tanggalPengesahanE" aria-placeholder="coba"
                                                    class="form-control" required disabled>
                                                <i class="fa-solid fa-calendar-days position-absolute"></i>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tujuanSuratE" class="form-label black fw-normal">Tujuan
                                                Surat</label>
                                            <input type="text" class="form-control" placeholder="Masukkan nomor surat"
                                                id="tujuanSuratE" name="tujuanSurat" aria-describedby="emailHelp"
                                                aria-label="edited" disabled />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="sifatSuratE" class="form-label black fw-normal">Sifat</label>
                                            <select id="sifatSuratE" name="sifatSurat" class="form-select"
                                                aria-label="edited" disabled>
                                                <option value="" selected>
                                                    -- Pilih salah satu --
                                                </option>
                                                @foreach ($sifat as $k => $v)
                                                    <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3" id="unitPengesahanKiri">
                                            <label for="disahkanOlehE" class="form-label black fw-normal">Disahkan
                                                Oleh</label>
                                            <select id="disahkanOlehE1" class="form-select" aria-label="edited" disabled>
                                                <option value="" selected>
                                                    -- Pilih salah satu --
                                                </option>
                                                @foreach ($unit as $k => $v)
                                                    <option value="{{ $v->nama }}">{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
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
                                                aria-describedby="emailHelp" aria-label="edited" accept=".pdf"
                                                value="" disabled />
                                        </div>
                                        <div class="mb-3">
                                            <label for="jumlahLampiranE" class="form-label black fw-normal"
                                                id="labelJumlah">Jumlah Halaman Dokumen
                                                Arsip</label>
                                            <input type="number" class="form-control" id="jumlahLampiranE"
                                                name="jumlahLampiran" min="0" aria-describedby="emailHelp"
                                                aria-label="edited" disabled />
                                        </div>

                                        <div class="mb-3">
                                            <label for="perihalE" class="form-label black fw-normal">Perihal</label>
                                            <textarea class="form-control perihal" id="perihalE" name="perihal" rows="1"
                                                placeholder="Contoh : Permohonan perijinan penelitian" style="min-height: unset" aria-label="edited" disabled></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert border rounded-2 py-3 gap-2 d-flex align-items-start mt-3"
                                    role="alert">
                                    <i class="fa-solid fa-circle-info" class="icon__info"></i>
                                    <div>
                                        <span class="fw-semibold" style="font-size: 14px">Catatan</span>
                                        <h5 class="mt-1 fw-normal" style="line-height: 1.5; font-size: 14px">
                                            Surat ini dibuat oleh <span id="created_by"></span> (<span
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

            <!-- Modal registrasi start -->
            <div class="modal modal__section fade" id="registrasiSuratKeluar" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content p-3">
                        <div class="modal-header">
                            <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
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
                                                <input type="text" readonly class="form-control readonly"
                                                    id="nomorSurat" aria-describedby="emailHelp" aria-label="edited"
                                                    name="nomorSurat" required />
                                                <button type="button" class="ms-2" onclick="ambilNomor()">
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
                                                aria-label="edited">
                                                <option selected disabled value="">...</option>
                                                @foreach ($unit as $k => $v)
                                                    @if (old('kodeUnit') == $v->kode)
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
                                            <div class="invalid-feedback" id="feedback-u">
                                                Masukkan kode unit surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kodeHal" class="form-label black fw-normal">Kode Hal</label>
                                            <select class="form-select" required id="kodeHal" aria-label="edited"
                                                name="kodeHal">
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
                                        <div class="mb-3">
                                            <label for="tujuanSurat" class="form-label black fw-normal">Tujuan
                                                Surat</label>
                                            <input type="text" required class="form-control"
                                                placeholder="Contoh : Fakultas Kedokteran" id="tujuanSurat"
                                                name="tujuanSurat" aria-describedby="emailHelp" aria-label="edited"
                                                value="{{ old('tujuanSurat') }}" />
                                            <div class="invalid-feedback">
                                                Masukkan tujuan surat dengan benar.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sifatSurat" class="form-label black fw-normal">Sifat</label>
                                            <select class="form-select" id="sifatSurat" name="sifatSurat"
                                                aria-label="edited" required>
                                                <option value="" selected disabled value="">...</option>
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
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="disahkanOleh" class="form-label black fw-normal">Disahkan
                                                Oleh</label>
                                            <select class="form-select" aria-label="edited" id="disahkanOleh"
                                                name="disahkanOleh" required>
                                                <option selected disabled value="">...</option>
                                                @foreach ($unit as $k => $v)
                                                    @if (old('disahkanOleh') == $v->nama)
                                                        <option value="{{ $v->nama }}" selected>{{ $v->nama }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $v->nama }}">{{ $v->nama }}</option>
                                                    @endif
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
                                                <input type="text" readonly value="{{ date('Y-m-d', time()) }}"
                                                    identifier="date" placeholder="..." name="tanggalPengesahan"
                                                    class="form-control" required>
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
                                            <textarea name="perihal" class="form-control" style="min-height: 13.9rem" id="exampleFormControlTextarea1"
                                                rows="8" aria-label="edited" placeholder="Contoh : Permohonan perijinan penelitian" required>{{ old('perihal') }}</textarea>
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
                            <button type="submit" id="confirmRegistrasi" form="formRegistrasi" class="mybtn blue">
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formUploadDokumen" class="needs-validation" novalidate
                                action="{{ route('uploadDokumen') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="suratKeluar" name="dokumen">
                                <div class="alert alert-warning gap-2 d-flex align-items-start" role="alert">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    <div>
                                        <span class="fw-semibold">Perhatian!</span>
                                        <h5 class="mt-1 fw-normal d-flex" style="line-height: 1.5">
                                            <span class="d-inline-block me-1 text-center"
                                                style="min-width: 20px">1.</span>
                                            Pastikan arsip surat sudah
                                            terdapat nomor surat, tanda tangan, dan tidak di stempel.
                                        </h5>
                                        <h5 class="mt-1 fw-normal d-flex" style="line-height: 1.5">
                                            <span class="d-inline-block me-1 text-center"
                                                style="min-width: 20px">2.</span>
                                            Arsip
                                            surat diupload dalam
                                            bentuk
                                            pdf.
                                        </h5>
                                        <h5 class="mt-1 fw-normal d-flex" style="line-height: 1.5">
                                            <span class="d-inline-block me-1 text-center"
                                                style="min-width: 20px">3.</span>
                                            Ukuran file tidak lebih dari 2 MB.
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
                                    <label for="jumlahLampiran" class="form-label black fw-normal">Jumlah Lampiran</label>
                                    <input type="number" class="form-control"
                                        placeholder="*Kosongkan jika tidak terdapat lampiran" id="jumlahLampiran"
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
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3" id="xx">
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
                    <div id="btnRegistrasi"></div>
                </div>
            </div>
            {{-- Tabel header end --}}

            {{-- Tabel content start --}}
            <div class="table-responsive">
                <table id="mytable" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="no">#</th>
                            <th>Disahkan Oleh / No. Surat</th>
                            <th>Tanggal Disahkan</th>
                            <th>Perihal</th>
                            <th>Aksi</th>
                            <th>Name</th>

                            {{-- Hidden kolom untuk Excel start --}}
                            {{-- <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomor</th>
                            <th>Perihal</th>
                            <th>Dibuat</th> --}}
                            {{-- Hidden kolom untuk Excel end --}}

                            {{-- <th class="text-center">Sifat</th> --}}
                        </tr>
                    </thead>
                    <tbody>
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
            let input = document.querySelectorAll('[aria-label=edited]')

            title.innerText = "Form Edit Surat Keluar"
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

    {{-- Fungsi lihat lampiran start --}}
    <script>
        function lihatLampiran(id) {
            var options = {
                fallbackLink: "<p>Silahkan lihat arsip dokumen surat melalui link berikut. <a href='[url]'>Lihat arsip.</a></p>"
            };
            let env = "{{ config('app.env') }}"
            if (env === "development") {
                PDFObject.embed(`{{ asset('uploads/${id}') }}`, "#example1", options);
            } else {
                PDFObject.embed(`{{ asset('public/uploads/${id}') }}`, "#example1", options);
            }
        }
    </script>
    {{-- Fungsi lihat lampiran end --}}

    {{-- Fungsi upload dokumen start --}}
    <script>
        function uploadDokumen(id) {
            let idSurat = document.getElementById('suratKeluar')
            idSurat.value = id
        }
    </script>
    {{-- Fungsi upload dokumen end --}}

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
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
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

    {{-- Fungsi ambil nomor surat --}}
    <script>
        function ambilNomor() {
            $.ajax({
                type: 'GET',
                url: "{{ route('ambilNomor') }}" + "?jenis=biasa" + "&tanggalPengesahan=" + $(
                    'input[name="tanggalPengesahan"]').attr('value'),
                dataType: 'json',
                success: function(data) {
                    $('input[name="nomorSurat"]').attr('value', data)
                }
            });
        }
    </script>
    {{-- End fungsi ambil nomor surat --}}

    {{-- Fungsi detail surat start --}}
    <script>
        function detailSurat(id, lampiran) {
            let loader = document.getElementById('myloader')
            loader.classList.remove('d-none')

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
                    loader.classList.add('d-none')

                    $('input[name="jenisSurat"]').val('biasa')
                    $("#tujuanSuratE").val(data.tujuanSurat)
                    tanggal = new Date(data.tanggalPengesahan)
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

                    $('#datepickerEdit').val(`${d}-${m}-${y}`)
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
                    $('#created_at').text(`${dname}, ${dd} ${md} ${yd}.`)
                    $('#bagian').text(data.bagian)
                }
            });
            $('input[name="idSurat"]').attr('value', id);
        }
    </script>
    {{-- Fungsi detail surat end --}}

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

    <!-- Initializing data tables start -->
    {{-- Pimpinan --}}
    @if ($user->role_id === 3)
        <script>
            $(document).ready(function() {
                var table = $("#mytable").DataTable({
                    serverSide: true,
                    ajax: {
                        url: '/getSuratKeluar',
                        type: 'GET'
                    },
                    pageLength: 10,
                    columns: [
                        // Indeks nomor start
                        {
                            render: function(data, type, row, meta) {
                                return meta.row + 1
                            }
                        },
                        // Indeks nomor end

                        // Disahkan oleh / nomor surat start
                        {
                            render: function(data, type, row, meta) {
                                let disahkanOleh = row.disahkanOleh
                                let nomorSurat = row.nomorSurat
                                let kodeUnit = row.kodeUnit
                                let kodeHal = row.kodeHal
                                let tanggalPengesahanOriginal = row.tanggalPengesahan
                                let tanggalPengesahan = new Date(`${tanggalPengesahanOriginal}`)
                                let tahun = tanggalPengesahan.getFullYear()
                                let bulan = tanggalPengesahan.getMonth() + 1

                                function angkaBulanKeRomawi(bulan) {
                                    if (bulan < 1 || bulan > 12) {
                                        return "Invalid";
                                    }

                                    const romawi = ["", "I", "II", "III", "IV", "V", "VI", "VII",
                                        "VIII", "IX", "X", "XI", "XII"
                                    ];
                                    return romawi[bulan];
                                }

                                const romawiBulan = angkaBulanKeRomawi(
                                    bulan); // Konversi angka bulan ke angka Romawi

                                return `
                        ${disahkanOleh} <br />
                        <div class="pt-2">
                            Nomor : ${nomorSurat}/${kodeUnit}/${kodeHal}/${romawiBulan}/${tahun}
                        </div>
                        `
                            }
                        },
                        // Disahkan oleh / nomor surat end

                        // Tanggal disahkan start
                        {
                            render: function(data, type, row, meta) {
                                let tes = row
                                let tanggalPengesahanOriginal = row.tanggalPengesahan
                                let tanggal = new Date(tanggalPengesahanOriginal);

                                const namaBulanIndonesia = [
                                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                    "Juli", "Agustus", "September", "Oktober", "November",
                                    "Desember"
                                ];

                                const tanggalFormatted = tanggal.getDate().toString().padStart(2,
                                    '0'); // Dapatkan tanggal dan format dengan 2 digit
                                const namaBulan = namaBulanIndonesia[tanggal
                                    .getMonth()]; // Dapatkan nama bulan sesuai indeks
                                const tahun = tanggal.getFullYear(); // Dapatkan tahun

                                return `${tanggalFormatted} ${namaBulan} ${tahun}`;
                            }
                        },
                        // Tanggal disahkan end

                        // Perihal start
                        {
                            data: "perihal"
                        },
                        // Perihal end

                        // Aksi start
                        {
                            render: function(data, type, full, meta) {
                                let id = full.id
                                let lampiran = full.lampiran
                                if (lampiran == null) {
                                    return `
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratKeluar"
                                    class="myicon position-relative blue d-flex align-items-center justify-content-center"
                                        id="btnEdit" onclick="detailSurat('${id}')"
                                        data-id="${id}" style="width: fit-content">
                                        <i class="fa-solid fa-file-lines me-2"></i>Detail
                                </button>
                            </div>
                                        `;
                                } else {
                                    return `
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratKeluar"
                                    class="myicon position-relative blue d-flex align-items-center justify-content-center"
                                        id="btnEdit" onclick="detailSurat('${id}')"
                                        data-id="${id}" style="width: fit-content">
                                        <i class="fa-solid fa-file-lines me-2"></i>Detail
                                </button>
                                <button type="button"
                                        class="myicon light bg-white position-relative blue d-flex align-items-center justify-content-center"
                                        data-bs-toggle="modal" data-bs-target="#lampiran"
                                        onclick="lihatLampiran('${lampiran}')">
                                        <i class="fa-solid fa-paperclip"></i>
                                        <div class="position-absolute mytooltip">
                                            <div class="text-white px-3 py-2 position-relative">
                                                Lihat Arsip
                                            </div>
                                            <div id="arrow"></div>
                                        </div>
                                </button>
                            </div>
                                        `;
                                }
                            },
                            orderable: false, // Agar kolom ini tidak dapat diurutkan
                            searchable: false, // Agar kolom ini tidak dapat dicari
                        },
                        // Aksi end

                        // Name start
                        {
                            render: function(data, type, row, meta) {
                                let name = row.name
                                return name
                            }
                        }
                        // Name end
                    ],

                    columnDefs: [{
                            orderable: false,
                            targets: [0, 1, 2, 3, 4, 5]
                        },
                        {
                            targets: [5],
                            visible: false
                        }, {
                            "targets": 0, // Ganti dengan indeks kolom yang sesuai
                            "className": "text-center"
                        }, {
                            "width": "25%",
                            "targets": 1,
                        }, {
                            "width": "15%",
                            "targets": 2,
                        }, {
                            "width": "40%",
                            "targets": 3,
                        }
                    ],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.childRowImmediate,
                            type: "none",
                            target: "",
                        },
                    },
                    dom: '<"d-flex justify-content-between flex-wrap gap-3"Bf>rt<"d-flex justify-content-between mt-3 overflow-hidden"<"d-flex align-items-center"li>p>',
                    buttons: [{
                        text: '<i class="fa-solid fa-file-arrow-down"></i> Export Excel',
                        className: 'mybtn green',
                        action: function exportExcel() {
                            let table = $('#mytable').DataTable();
                            let dataTableHeaders = ["Tanggal Disahkan", "Disahkan Oleh",
                                "Nomor Surat", "Perihal", "Tujuan Surat",
                                "Dibuat Oleh"
                            ];
                            let allData = table.rows().data().toArray();
                            let data = [];
                            const user = `{{ $user->role_id }}`

                            // Ambil data yang dibutuhkan saja menyesuaikan excel pak mul start
                            for (let i = 0; i < allData.length; i++) {
                                let subarray = allData[i];
                                let nomorSurat = subarray.nomorSurat;
                                let kodeHal = subarray.kodeHal;
                                let kodeUnit = subarray.kodeUnit;
                                let tanggalPengesahanOriginal = subarray.tanggalPengesahan
                                let tanggalPengesahan = new Date(tanggalPengesahanOriginal)
                                let bulan = tanggalPengesahan.getMonth() + 1
                                let tahun = tanggalPengesahan.getFullYear()

                                // Nomor surat : convert bulan ke romawi start
                                function angkaBulanKeRomawi(bulan) {
                                    if (bulan < 1 || bulan > 12) {
                                        return "Invalid";
                                    }

                                    const romawi = ["", "I", "II", "III", "IV", "V", "VI", "VII",
                                        "VIII", "IX", "X", "XI", "XII"
                                    ];
                                    return romawi[bulan];
                                }
                                const romawiBulan = angkaBulanKeRomawi(
                                    bulan);
                                // Nomor surat : convert bulan ke romawi end

                                // Tanggal disahkan : convert bulan ke bulan indonesia start
                                const namaBulanIndonesia = [
                                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                    "Juli", "Agustus", "September", "Oktober", "November",
                                    "Desember"
                                ];

                                const tanggalFormatted = tanggalPengesahan.getDate().toString()
                                    .padStart(2,
                                        '0'); // Dapatkan tanggal dan format dengan 2 digit
                                const namaBulan = namaBulanIndonesia[tanggalPengesahan
                                    .getMonth()]; // Dapatkan nama bulan sesuai indeks
                                const tahunOri = tanggalPengesahan.getFullYear(); // Dapatkan tahun

                                let tanggalFixed = `${tanggalFormatted} ${namaBulan} ${tahunOri}`;
                                // Tanggal disahkan : convert bulan ke bulan indonesia end

                                let nomorSuratLengkap =
                                    `${nomorSurat}/${kodeUnit}/${kodeHal}/${romawiBulan}/${tahun}`
                                let subarrayTerpilih = [tanggalFixed, subarray
                                    .disahkanOleh,
                                    nomorSuratLengkap, subarray.perihal, subarray.tujuanSurat,
                                    subarray.name
                                ];
                                data.push(subarrayTerpilih);
                            }

                            // Menggunakan .sort() dengan fungsi perbandingan
                            data.sort((a, b) => {
                                // Mengambil nomor dari indeks ke-2 dalam masing-masing array
                                const numA = parseInt(a[2].split('/')[0]);
                                const numB = parseInt(b[2].split('/')[0]);

                                // Membandingkan nomor untuk pengurutan
                                return numA - numB;
                            });

                            function parseDate(dateString) {
                                const months = {
                                    'Januari': '01',
                                    'Februari': '02',
                                    'Maret': '03',
                                    'April': '04',
                                    'Mei': '05',
                                    'Juni': '06',
                                    'Juli': '07',
                                    'Agustus': '08',
                                    'September': '09',
                                    'Oktober': '10',
                                    'November': '11',
                                    'Desember': '12'
                                };

                                const [day, month, year] = dateString.split(' ');
                                const monthNumber = months[month];
                                if (monthNumber) {
                                    return new Date(`${year}-${monthNumber}-${day}`);
                                }
                                return new Date(); // Atau tindakan lain jika format tanggal tidak valid
                            }

                            data.sort((a, b) => parseDate(a[0]) - parseDate(b[0]));

                            // Tambahkan header ke data
                            let excelData = [dataTableHeaders].concat(data)

                            // Buat file Excel kosong menggunakan SheetJS
                            let workbook = XLSX.utils.book_new();

                            // Buat worksheet dalam file Excel
                            let worksheet = XLSX.utils.aoa_to_sheet(excelData);

                            // Sisipkan worksheet ke dalam file Excel
                            XLSX.utils.book_append_sheet(workbook, worksheet, 'Data');

                            // Dapatkan tanggal hari ini
                            let today = new Date();
                            let dd = String(today.getDate()).padStart(2, '0');
                            let mm = String(today.getMonth() + 1).padStart(2,
                                '0'); // Januari dimulai dari 0
                            let yyyy = today.getFullYear();

                            // Dapatkan hari ini
                            let days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat',
                                'Sabtu'
                            ];
                            let day = days[today
                                .getDay()]; // Mendapatkan nama hari berdasarkan indeks

                            // Dapatkan nama bulan
                            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                'Juli', 'Agustus', 'September', 'Oktober',
                                'November', 'Desember'
                            ];
                            var month = months[today
                                .getMonth()]; // Mendapatkan nama bulan berdasarkan indeks

                            // Format tanggal sesuai keinginan Anda (contoh: DD-MM-YYYY)
                            let formattedDate = dd + ' ' + month + ' ' + yyyy;

                            // Nama file Excel dengan tanggal hari ini
                            let fileName = ''
                            if (user == 1) {
                                fileName =
                                    'Data Surat Keluar Fakultas Kedokteran Universitas Diponegoro ' +
                                    '- ' + day + ', ' +
                                    formattedDate + '.xlsx';
                            } else {
                                fileName = 'Rekap Data Surat Keluar - ' + day + ', ' +
                                    formattedDate + '.xlsx'
                            }

                            // Ekspor file Excel
                            XLSX.writeFile(workbook, fileName);
                        }
                    }],
                    destroy: true,
                    order: false,
                    language: {
                        lengthMenu: "Tampilkan _MENU_",
                        zeroRecords: "Surat keluar tidak tersedia. <br>Silahkan registrasi surat terlebih dahulu.",
                        info: "Menampilkan _PAGE_ dari _PAGES_",
                        infoEmpty: "Baris tidak tersedia",
                        infoFiltered: "",
                        search: "Cari :",
                    },
                    oLanguage: {
                        oPaginate: {
                            sNext: '<span class="pagination-fa"><i class="fa-solid fa-angle-right"></i></span>',
                            sPrevious: '<span class="pagination-fa"><i class="fa-solid fa-angle-left"></i></span>',
                        },
                    },
                    lengthMenu: [
                        [10, 50, 200, 1000, 10000],
                        [10, 50, 200, 1000, 10000],
                    ],
                });

                // Initializing
                $("#inputTanggalStart").datepicker()
                // Ganti tahun
                $("#inputTanggalStart").datepicker("option", "changeYear", true);
                // Ganti format tanggal
                $("#inputTanggalStart").datepicker("option", "dateFormat", "dd-mm-yy")

                // Initializing
                $("#inputTanggalEnd").datepicker()
                // Ganti tahun
                $("#inputTanggalEnd").datepicker("option", "changeYear", true);
                // Ganti format tanggal
                $("#inputTanggalEnd").datepicker("option", "dateFormat", "dd-mm-yy");

                // Filter rentang tanggal start
                $('#inputTanggalStart, #inputTanggalEnd').on('change', function() {
                    var start = $('#inputTanggalStart').val();
                    var end = $('#inputTanggalEnd').val();

                    if (start && end) {
                        table.ajax.url('getSuratKeluar?mulai=' + start + '&selesai=' + end).load();
                    }
                });
                // Filter rentang tanggal end
            });
        </script>
        {{-- End pimpinan --}}

        {{-- Operator --}}
    @elseif($user->role_id === 2)
        <script>
            $(document).ready(function() {
                var table = $("#mytable").DataTable({
                    serverSide: true,
                    ajax: {
                        url: '/getSuratKeluar',
                        type: 'GET'
                    },
                    pageLength: 10,
                    columns: [
                        // Indeks nomor start
                        {
                            render: function(data, type, row, meta) {
                                return meta.row + 1
                            }
                        },
                        // Indeks nomor end

                        // Disahkan oleh / nomor surat start
                        {
                            render: function(data, type, row, meta) {
                                let disahkanOleh = row.disahkanOleh
                                let nomorSurat = row.nomorSurat
                                let kodeUnit = row.kodeUnit
                                let kodeHal = row.kodeHal
                                let tanggalPengesahanOriginal = row.tanggalPengesahan
                                let tanggalPengesahan = new Date(`${tanggalPengesahanOriginal}`)
                                let tahun = tanggalPengesahan.getFullYear()
                                let bulan = tanggalPengesahan.getMonth() + 1

                                function angkaBulanKeRomawi(bulan) {
                                    if (bulan < 1 || bulan > 12) {
                                        return "Invalid";
                                    }

                                    const romawi = ["", "I", "II", "III", "IV", "V", "VI", "VII",
                                        "VIII", "IX", "X", "XI", "XII"
                                    ];
                                    return romawi[bulan];
                                }

                                const romawiBulan = angkaBulanKeRomawi(
                                    bulan); // Konversi angka bulan ke angka Romawi

                                return `
                            ${disahkanOleh} <br />
                            <div class="pt-2">
                                Nomor : ${nomorSurat}/${kodeUnit}/${kodeHal}/${romawiBulan}/${tahun}
                            </div>
                            `
                            }
                        },
                        // Disahkan oleh / nomor surat end

                        // Tanggal disahkan start
                        {
                            render: function(data, type, row, meta) {
                                let tes = row
                                let tanggalPengesahanOriginal = row.tanggalPengesahan
                                let tanggal = new Date(tanggalPengesahanOriginal);

                                const namaBulanIndonesia = [
                                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                    "Juli", "Agustus", "September", "Oktober", "November",
                                    "Desember"
                                ];

                                const tanggalFormatted = tanggal.getDate().toString().padStart(2,
                                    '0'); // Dapatkan tanggal dan format dengan 2 digit
                                const namaBulan = namaBulanIndonesia[tanggal
                                    .getMonth()]; // Dapatkan nama bulan sesuai indeks
                                const tahun = tanggal.getFullYear(); // Dapatkan tahun

                                return `${tanggalFormatted} ${namaBulan} ${tahun}`;
                            }
                        },
                        // Tanggal disahkan end

                        // Perihal start
                        {
                            data: "perihal"
                        },
                        // Perihal end

                        // Aksi start
                        {
                            render: function(data, type, full, meta) {
                                let id = full.id
                                let lampiran = full.lampiran
                                let sifat = full.sifatSurat
                                if (lampiran == null) {
                                    if (sifat == 4) {
                                        return `
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratKeluar"
                                                    class="myicon position-relative blue d-flex align-items-center justify-content-center"
                                                        id="btnEdit" onclick="detailSurat('${id}')"
                                                        data-id="${id}" style="width: fit-content">
                                                        <i class="fa-solid fa-file-lines me-2"></i>Detail
                                                </button>
                                                <button type="button"
                                                        class="myicon position-relative yellow d-flex align-items-center justify-content-center"
                                                        data-bs-toggle="modal" data-bs-target="#uploadDokumen"
                                                        onclick="uploadDokumen('${id}')" id="btnUpload">
                                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                                        <div class="position-absolute mytooltip">
                                                            <div class="text-white px-3 py-2 position-relative">
                                                                Upload Arsip
                                                            </div>
                                                            <div id="arrow"></div>
                                                        </div>
                                                </button>
                                            </div>
                                            `;
                                    }
                                    return `
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratKeluar"
                                        class="myicon position-relative blue d-flex align-items-center justify-content-center"
                                            id="btnEdit" onclick="detailSurat('${id}')"
                                            data-id="${id}" style="width: fit-content">
                                            <i class="fa-solid fa-file-lines me-2"></i>Detail
                                    </button>
                                    <button type="button"
                                            class="myicon position-relative yellow d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal" data-bs-target="#uploadDokumen"
                                            onclick="uploadDokumen('${id}')" id="btnUpload">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <div class="position-absolute mytooltip">
                                                <div class="text-white px-3 py-2 position-relative">
                                                    Upload Arsip
                                                </div>
                                                <div id="arrow"></div>
                                            </div>
                                    </button>
                                    <button type="button"
                                            class="myicon red position-relative d-flex align-items-center justify-content-center"
                                            onclick="confirmHapus('${id}')">
                                            <i class="fa-solid fa-trash"></i>
                                            <div class="position-absolute mytooltip">
                                                <div class="text-white px-3 py-2 position-relative">
                                                    Hapus
                                                </div>
                                                <div id="arrow"></div>
                                            </div>
                                    </button>
                                </div>
                                `;
                                } else {
                                    return `
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratKeluar"
                                        class="myicon position-relative blue d-flex align-items-center justify-content-center"
                                            id="btnEdit" onclick="detailSurat('${id}')"
                                            data-id="${id}" style="width: fit-content">
                                            <i class="fa-solid fa-file-lines me-2"></i>Detail
                                    </button>
                                    <button type="button"
                                            class="myicon light bg-white position-relative blue d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal" data-bs-target="#lampiran"
                                            onclick="lihatLampiran('${lampiran}')">
                                            <i class="fa-solid fa-paperclip"></i>
                                            <div class="position-absolute mytooltip">
                                                <div class="text-white px-3 py-2 position-relative">
                                                    Lihat Arsip
                                                </div>
                                                <div id="arrow"></div>
                                            </div>
                                    </button>
                                </div>
                                            `;
                                }
                            },
                            orderable: false, // Agar kolom ini tidak dapat diurutkan
                            searchable: false, // Agar kolom ini tidak dapat dicari
                        },
                        // Aksi end

                        // Name start
                        {
                            render: function(data, type, row, meta) {
                                let name = row.name
                                return name
                            }
                        }
                        // Name end
                    ],

                    columnDefs: [{
                            orderable: false,
                            targets: [0, 1, 2, 3, 4, 5]
                        },
                        {
                            targets: [5],
                            visible: false
                        }, {
                            "targets": 0, // Ganti dengan indeks kolom yang sesuai
                            "className": "text-center"
                        }, {
                            "width": "25%",
                            "targets": 1,
                        }, {
                            "width": "15%",
                            "targets": 2,
                        }, {
                            "width": "40%",
                            "targets": 3,
                        }
                    ],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.childRowImmediate,
                            type: "none",
                            target: "",
                        },
                    },
                    dom: '<"d-flex justify-content-between flex-wrap gap-3"Bf>rt<"d-flex justify-content-between mt-3 overflow-hidden"<"d-flex align-items-center"li>p>',
                    buttons: [{
                        text: '<i class="fa-solid fa-file-arrow-down"></i> Export Excel',
                        className: 'mybtn green',
                        action: function exportExcel() {
                            let table = $('#mytable').DataTable();
                            let dataTableHeaders = ["Tanggal Disahkan", "Disahkan Oleh",
                                "Nomor Surat", "Perihal", "Tujuan Surat",
                                "Dibuat Oleh"
                            ];
                            let allData = table.rows().data().toArray();
                            let data = [];
                            const user = `{{ $user->role_id }}`

                            // Ambil data yang dibutuhkan saja menyesuaikan excel pak mul start
                            for (let i = 0; i < allData.length; i++) {
                                let subarray = allData[i];
                                let nomorSurat = subarray.nomorSurat;
                                let kodeHal = subarray.kodeHal;
                                let kodeUnit = subarray.kodeUnit;
                                let tanggalPengesahanOriginal = subarray.tanggalPengesahan
                                let tanggalPengesahan = new Date(tanggalPengesahanOriginal)
                                let bulan = tanggalPengesahan.getMonth() + 1
                                let tahun = tanggalPengesahan.getFullYear()

                                // Nomor surat : convert bulan ke romawi start
                                function angkaBulanKeRomawi(bulan) {
                                    if (bulan < 1 || bulan > 12) {
                                        return "Invalid";
                                    }

                                    const romawi = ["", "I", "II", "III", "IV", "V", "VI", "VII",
                                        "VIII", "IX", "X", "XI", "XII"
                                    ];
                                    return romawi[bulan];
                                }
                                const romawiBulan = angkaBulanKeRomawi(
                                    bulan);
                                // Nomor surat : convert bulan ke romawi end

                                // Tanggal disahkan : convert bulan ke bulan indonesia start
                                const namaBulanIndonesia = [
                                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                    "Juli", "Agustus", "September", "Oktober", "November",
                                    "Desember"
                                ];

                                const tanggalFormatted = tanggalPengesahan.getDate().toString()
                                    .padStart(2,
                                        '0'); // Dapatkan tanggal dan format dengan 2 digit
                                const namaBulan = namaBulanIndonesia[tanggalPengesahan
                                    .getMonth()]; // Dapatkan nama bulan sesuai indeks
                                const tahunOri = tanggalPengesahan.getFullYear(); // Dapatkan tahun

                                let tanggalFixed = `${tanggalFormatted} ${namaBulan} ${tahunOri}`;
                                // Tanggal disahkan : convert bulan ke bulan indonesia end

                                let nomorSuratLengkap =
                                    `${nomorSurat}/${kodeUnit}/${kodeHal}/${romawiBulan}/${tahun}`
                                let subarrayTerpilih = [tanggalFixed, subarray
                                    .disahkanOleh,
                                    nomorSuratLengkap, subarray.perihal, subarray.tujuanSurat,
                                    subarray.name
                                ];
                                data.push(subarrayTerpilih);
                            }

                            // Menggunakan .sort() dengan fungsi perbandingan
                            data.sort((a, b) => {
                                // Mengambil nomor dari indeks ke-2 dalam masing-masing array
                                const numA = parseInt(a[2].split('/')[0]);
                                const numB = parseInt(b[2].split('/')[0]);

                                // Membandingkan nomor untuk pengurutan
                                return numA - numB;
                            });

                            function parseDate(dateString) {
                                const months = {
                                    'Januari': '01',
                                    'Februari': '02',
                                    'Maret': '03',
                                    'April': '04',
                                    'Mei': '05',
                                    'Juni': '06',
                                    'Juli': '07',
                                    'Agustus': '08',
                                    'September': '09',
                                    'Oktober': '10',
                                    'November': '11',
                                    'Desember': '12'
                                };

                                const [day, month, year] = dateString.split(' ');
                                const monthNumber = months[month];
                                if (monthNumber) {
                                    return new Date(`${year}-${monthNumber}-${day}`);
                                }
                                return new Date(); // Atau tindakan lain jika format tanggal tidak valid
                            }

                            data.sort((a, b) => parseDate(a[0]) - parseDate(b[0]));

                            // Tambahkan header ke data
                            let excelData = [dataTableHeaders].concat(data)

                            // Buat file Excel kosong menggunakan SheetJS
                            let workbook = XLSX.utils.book_new();

                            // Buat worksheet dalam file Excel
                            let worksheet = XLSX.utils.aoa_to_sheet(excelData);

                            // Sisipkan worksheet ke dalam file Excel
                            XLSX.utils.book_append_sheet(workbook, worksheet, 'Data');

                            // Dapatkan tanggal hari ini
                            let today = new Date();
                            let dd = String(today.getDate()).padStart(2, '0');
                            let mm = String(today.getMonth() + 1).padStart(2,
                                '0'); // Januari dimulai dari 0
                            let yyyy = today.getFullYear();

                            // Dapatkan hari ini
                            let days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat',
                                'Sabtu'
                            ];
                            let day = days[today
                                .getDay()]; // Mendapatkan nama hari berdasarkan indeks

                            // Dapatkan nama bulan
                            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                'Juli', 'Agustus', 'September', 'Oktober',
                                'November', 'Desember'
                            ];
                            var month = months[today
                                .getMonth()]; // Mendapatkan nama bulan berdasarkan indeks

                            // Format tanggal sesuai keinginan Anda (contoh: DD-MM-YYYY)
                            let formattedDate = dd + ' ' + month + ' ' + yyyy;

                            // Nama file Excel dengan tanggal hari ini
                            let fileName = ''
                            if (user == 1) {
                                fileName =
                                    'Data Surat Keluar Fakultas Kedokteran Universitas Diponegoro ' +
                                    '- ' + day + ', ' +
                                    formattedDate + '.xlsx';
                            } else {
                                fileName = 'Rekap Data Surat Keluar - ' + day + ', ' +
                                    formattedDate + '.xlsx'
                            }

                            // Ekspor file Excel
                            XLSX.writeFile(workbook, fileName);
                        }
                    }],
                    destroy: true,
                    order: false,
                    language: {
                        lengthMenu: "Tampilkan _MENU_",
                        zeroRecords: "Surat keluar tidak tersedia. <br>Silahkan registrasi surat terlebih dahulu.",
                        info: "Menampilkan _PAGE_ dari _PAGES_",
                        infoEmpty: "Baris tidak tersedia",
                        infoFiltered: "",
                        search: "Cari :",
                    },
                    oLanguage: {
                        oPaginate: {
                            sNext: '<span class="pagination-fa"><i class="fa-solid fa-angle-right"></i></span>',
                            sPrevious: '<span class="pagination-fa"><i class="fa-solid fa-angle-left"></i></span>',
                        },
                    },
                    lengthMenu: [
                        [10, 50, 200, 1000, 10000],
                        [10, 50, 200, 1000, 10000],
                    ],
                });

                // Initializing
                $("#inputTanggalStart").datepicker()
                // Ganti tahun
                $("#inputTanggalStart").datepicker("option", "changeYear", true);
                // Ganti format tanggal
                $("#inputTanggalStart").datepicker("option", "dateFormat", "dd-mm-yy")

                // Initializing
                $("#inputTanggalEnd").datepicker()
                // Ganti tahun
                $("#inputTanggalEnd").datepicker("option", "changeYear", true);
                // Ganti format tanggal
                $("#inputTanggalEnd").datepicker("option", "dateFormat", "dd-mm-yy");

                // Filter rentang tanggal start
                $('#inputTanggalStart, #inputTanggalEnd').on('change', function() {
                    var start = $('#inputTanggalStart').val();
                    var end = $('#inputTanggalEnd').val();

                    if (start && end) {
                        table.ajax.url('getSuratKeluar?mulai=' + start + '&selesai=' + end).load();
                    }
                });
                // Filter rentang tanggal end
            });
        </script>
        {{-- End operator --}}

        {{-- Admin --}}
    @else
        <script>
            $(document).ready(function() {
                var table = $("#mytable").DataTable({
                    serverSide: true,
                    ajax: {
                        url: '/getSuratKeluar',
                        type: 'GET'
                    },
                    pageLength: 10,
                    columns: [
                        // Indeks nomor start
                        {
                            render: function(data, type, row, meta) {
                                return meta.row + 1
                            }
                        },
                        // Indeks nomor end

                        // Disahkan oleh / nomor surat start
                        {
                            render: function(data, type, row, meta) {
                                let disahkanOleh = row.disahkanOleh
                                let nomorSurat = row.nomorSurat
                                let kodeUnit = row.kodeUnit
                                let kodeHal = row.kodeHal
                                let tanggalPengesahanOriginal = row.tanggalPengesahan
                                let tanggalPengesahan = new Date(`${tanggalPengesahanOriginal}`)
                                let tahun = tanggalPengesahan.getFullYear()
                                let bulan = tanggalPengesahan.getMonth() + 1

                                function angkaBulanKeRomawi(bulan) {
                                    if (bulan < 1 || bulan > 12) {
                                        return "Invalid";
                                    }

                                    const romawi = ["", "I", "II", "III", "IV", "V", "VI", "VII",
                                        "VIII", "IX", "X", "XI", "XII"
                                    ];
                                    return romawi[bulan];
                                }

                                const romawiBulan = angkaBulanKeRomawi(
                                    bulan); // Konversi angka bulan ke angka Romawi

                                return `
                        ${disahkanOleh} <br />
                        <div class="pt-2">
                            Nomor : ${nomorSurat}/${kodeUnit}/${kodeHal}/${romawiBulan}/${tahun}
                        </div>
                        `
                            }
                        },
                        // Disahkan oleh / nomor surat end

                        // Tanggal disahkan start
                        {
                            render: function(data, type, row, meta) {
                                let tes = row
                                let tanggalPengesahanOriginal = row.tanggalPengesahan
                                let tanggal = new Date(tanggalPengesahanOriginal);

                                const namaBulanIndonesia = [
                                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                    "Juli", "Agustus", "September", "Oktober", "November",
                                    "Desember"
                                ];

                                const tanggalFormatted = tanggal.getDate().toString().padStart(2,
                                    '0'); // Dapatkan tanggal dan format dengan 2 digit
                                const namaBulan = namaBulanIndonesia[tanggal
                                    .getMonth()]; // Dapatkan nama bulan sesuai indeks
                                const tahun = tanggal.getFullYear(); // Dapatkan tahun

                                return `${tanggalFormatted} ${namaBulan} ${tahun}`;
                            }
                        },
                        // Tanggal disahkan end

                        // Perihal start
                        {
                            data: "perihal"
                        },
                        // Perihal end

                        // Aksi start
                        {
                            render: function(data, type, full, meta) {
                                let id = full.id
                                let lampiran = full.lampiran
                                let sifat = full.sifatSurat
                                if (lampiran == null) {
                                    return `
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratKeluar"
                                    class="myicon position-relative blue d-flex align-items-center justify-content-center"
                                        id="btnEdit" onclick="detailSurat('${id}')"
                                        data-id="${id}" style="width: fit-content">
                                        <i class="fa-solid fa-file-lines me-2"></i>Detail
                                </button>
                                <button type="button"
                                        class="myicon position-relative yellow d-flex align-items-center justify-content-center"
                                        data-bs-toggle="modal" data-bs-target="#uploadDokumen"
                                        onclick="uploadDokumen('${id}')" id="btnUpload">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <div class="position-absolute mytooltip">
                                            <div class="text-white px-3 py-2 position-relative">
                                                Upload Arsip
                                            </div>
                                            <div id="arrow"></div>
                                        </div>
                                </button>
                                <button type="button"
                                        class="myicon red position-relative d-flex align-items-center justify-content-center"
                                        onclick="confirmHapus('${id}')">
                                        <i class="fa-solid fa-trash"></i>
                                        <div class="position-absolute mytooltip">
                                            <div class="text-white px-3 py-2 position-relative">
                                                Hapus
                                            </div>
                                            <div id="arrow"></div>
                                        </div>
                                </button>
                            </div>
                                        `;
                                } else {
                                    return `
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratKeluar"
                                    class="myicon position-relative blue d-flex align-items-center justify-content-center"
                                        id="btnEdit" onclick="detailSurat('${id}')"
                                        data-id="${id}" style="width: fit-content">
                                        <i class="fa-solid fa-file-lines me-2"></i>Detail
                                </button>
                                <button type="button"
                                        class="myicon light bg-white position-relative blue d-flex align-items-center justify-content-center"
                                        data-bs-toggle="modal" data-bs-target="#lampiran"
                                        onclick="lihatLampiran('${lampiran}')">
                                        <i class="fa-solid fa-paperclip"></i>
                                        <div class="position-absolute mytooltip">
                                            <div class="text-white px-3 py-2 position-relative">
                                                Lihat Arsip
                                            </div>
                                            <div id="arrow"></div>
                                        </div>
                                </button>
                                <button type="button"
                                        class="myicon red position-relative d-flex align-items-center justify-content-center"
                                        onclick="confirmHapus('${id}')">
                                        <i class="fa-solid fa-trash"></i>
                                        <div class="position-absolute mytooltip">
                                            <div class="text-white px-3 py-2 position-relative">
                                                Hapus
                                            </div>
                                            <div id="arrow"></div>
                                        </div>
                                </button>
                            </div>
                                        `;
                                }
                            },
                            orderable: false, // Agar kolom ini tidak dapat diurutkan
                            searchable: false, // Agar kolom ini tidak dapat dicari
                        },
                        // Aksi end

                        // Name start
                        {
                            render: function(data, type, row, meta) {
                                let name = row.name
                                return name
                            }
                        }
                        // Name end
                    ],

                    columnDefs: [{
                            orderable: false,
                            targets: [0, 1, 2, 3, 4, 5]
                        },
                        {
                            targets: [5],
                            visible: false
                        }, {
                            "targets": 0, // Ganti dengan indeks kolom yang sesuai
                            "className": "text-center"
                        }, {
                            "width": "25%",
                            "targets": 1,
                        }, {
                            "width": "15%",
                            "targets": 2,
                        }, {
                            "width": "40%",
                            "targets": 3,
                        }
                    ],
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.childRowImmediate,
                            type: "none",
                            target: "",
                        },
                    },
                    dom: '<"d-flex justify-content-between flex-wrap gap-3"Bf>rt<"d-flex justify-content-between mt-3 overflow-hidden"<"d-flex align-items-center"li>p>',
                    buttons: [{
                        text: '<i class="fa-solid fa-file-arrow-down"></i> Export Excel',
                        className: 'mybtn green',
                        action: function exportExcel() {
                            let table = $('#mytable').DataTable();
                            let dataTableHeaders = ["Tanggal Disahkan", "Disahkan Oleh",
                                "Nomor Surat", "Perihal", "Tujuan Surat",
                                "Dibuat Oleh"
                            ];
                            let allData = table.rows().data().toArray();
                            let data = [];
                            const user = `{{ $user->role_id }}`

                            // Ambil data yang dibutuhkan saja menyesuaikan excel pak mul start
                            for (let i = 0; i < allData.length; i++) {
                                let subarray = allData[i];
                                let nomorSurat = subarray.nomorSurat;
                                let kodeHal = subarray.kodeHal;
                                let kodeUnit = subarray.kodeUnit;
                                let tanggalPengesahanOriginal = subarray.tanggalPengesahan
                                let tanggalPengesahan = new Date(tanggalPengesahanOriginal)
                                let bulan = tanggalPengesahan.getMonth() + 1
                                let tahun = tanggalPengesahan.getFullYear()

                                // Nomor surat : convert bulan ke romawi start
                                function angkaBulanKeRomawi(bulan) {
                                    if (bulan < 1 || bulan > 12) {
                                        return "Invalid";
                                    }

                                    const romawi = ["", "I", "II", "III", "IV", "V", "VI", "VII",
                                        "VIII", "IX", "X", "XI", "XII"
                                    ];
                                    return romawi[bulan];
                                }
                                const romawiBulan = angkaBulanKeRomawi(
                                    bulan);
                                // Nomor surat : convert bulan ke romawi end

                                // Tanggal disahkan : convert bulan ke bulan indonesia start
                                const namaBulanIndonesia = [
                                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                    "Juli", "Agustus", "September", "Oktober", "November",
                                    "Desember"
                                ];

                                const tanggalFormatted = tanggalPengesahan.getDate().toString()
                                    .padStart(2,
                                        '0'); // Dapatkan tanggal dan format dengan 2 digit
                                const namaBulan = namaBulanIndonesia[tanggalPengesahan
                                    .getMonth()]; // Dapatkan nama bulan sesuai indeks
                                const tahunOri = tanggalPengesahan.getFullYear(); // Dapatkan tahun

                                let tanggalFixed = `${tanggalFormatted} ${namaBulan} ${tahunOri}`;
                                // Tanggal disahkan : convert bulan ke bulan indonesia end

                                let nomorSuratLengkap =
                                    `${nomorSurat}/${kodeUnit}/${kodeHal}/${romawiBulan}/${tahun}`
                                let subarrayTerpilih = [tanggalFixed, subarray
                                    .disahkanOleh,
                                    nomorSuratLengkap, subarray.perihal, subarray.tujuanSurat,
                                    subarray.name
                                ];
                                data.push(subarrayTerpilih);
                            }

                            // Menggunakan .sort() dengan fungsi perbandingan
                            data.sort((a, b) => {
                                // Mengambil nomor dari indeks ke-2 dalam masing-masing array
                                const numA = parseInt(a[2].split('/')[0]);
                                const numB = parseInt(b[2].split('/')[0]);

                                // Membandingkan nomor untuk pengurutan
                                return numA - numB;
                            });

                            function parseDate(dateString) {
                                const months = {
                                    'Januari': '01',
                                    'Februari': '02',
                                    'Maret': '03',
                                    'April': '04',
                                    'Mei': '05',
                                    'Juni': '06',
                                    'Juli': '07',
                                    'Agustus': '08',
                                    'September': '09',
                                    'Oktober': '10',
                                    'November': '11',
                                    'Desember': '12'
                                };

                                const [day, month, year] = dateString.split(' ');
                                const monthNumber = months[month];
                                if (monthNumber) {
                                    return new Date(`${year}-${monthNumber}-${day}`);
                                }
                                return new Date(); // Atau tindakan lain jika format tanggal tidak valid
                            }

                            data.sort((a, b) => parseDate(a[0]) - parseDate(b[0]));

                            // Tambahkan header ke data
                            let excelData = [dataTableHeaders].concat(data)

                            // Buat file Excel kosong menggunakan SheetJS
                            let workbook = XLSX.utils.book_new();

                            // Buat worksheet dalam file Excel
                            let worksheet = XLSX.utils.aoa_to_sheet(excelData);

                            // Sisipkan worksheet ke dalam file Excel
                            XLSX.utils.book_append_sheet(workbook, worksheet, 'Data');

                            // Dapatkan tanggal hari ini
                            let today = new Date();
                            let dd = String(today.getDate()).padStart(2, '0');
                            let mm = String(today.getMonth() + 1).padStart(2,
                                '0'); // Januari dimulai dari 0
                            let yyyy = today.getFullYear();

                            // Dapatkan hari ini
                            let days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat',
                                'Sabtu'
                            ];
                            let day = days[today
                                .getDay()]; // Mendapatkan nama hari berdasarkan indeks

                            // Dapatkan nama bulan
                            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                'Juli', 'Agustus', 'September', 'Oktober',
                                'November', 'Desember'
                            ];
                            var month = months[today
                                .getMonth()]; // Mendapatkan nama bulan berdasarkan indeks

                            // Format tanggal sesuai keinginan Anda (contoh: DD-MM-YYYY)
                            let formattedDate = dd + ' ' + month + ' ' + yyyy;

                            // Nama file Excel dengan tanggal hari ini
                            let fileName = ''
                            if (user == 1) {
                                fileName =
                                    'Data Surat Keluar Fakultas Kedokteran Universitas Diponegoro ' +
                                    '- ' + day + ', ' +
                                    formattedDate + '.xlsx';
                            } else {
                                fileName = 'Rekap Data Surat Keluar - ' + day + ', ' +
                                    formattedDate + '.xlsx'
                            }

                            // Ekspor file Excel
                            XLSX.writeFile(workbook, fileName);
                        }
                    }],
                    destroy: true,
                    order: false,
                    language: {
                        lengthMenu: "Tampilkan _MENU_",
                        zeroRecords: "Surat keluar tidak tersedia. <br>Silahkan registrasi surat terlebih dahulu.",
                        info: "Menampilkan _PAGE_ dari _PAGES_",
                        infoEmpty: "Baris tidak tersedia",
                        infoFiltered: "",
                        search: "Cari :",
                    },
                    oLanguage: {
                        oPaginate: {
                            sNext: '<span class="pagination-fa"><i class="fa-solid fa-angle-right"></i></span>',
                            sPrevious: '<span class="pagination-fa"><i class="fa-solid fa-angle-left"></i></span>',
                        },
                    },
                    lengthMenu: [
                        [10, 50, 200, 1000, 10000],
                        [10, 50, 200, 1000, 10000],
                    ],
                });

                // Initializing
                $("#inputTanggalStart").datepicker()
                // Ganti tahun
                $("#inputTanggalStart").datepicker("option", "changeYear", true);
                // Ganti format tanggal
                $("#inputTanggalStart").datepicker("option", "dateFormat", "dd-mm-yy")

                // Initializing
                $("#inputTanggalEnd").datepicker()
                // Ganti tahun
                $("#inputTanggalEnd").datepicker("option", "changeYear", true);
                // Ganti format tanggal
                $("#inputTanggalEnd").datepicker("option", "dateFormat", "dd-mm-yy");

                // Filter rentang tanggal start
                $('#inputTanggalStart, #inputTanggalEnd').on('change', function() {
                    var start = $('#inputTanggalStart').val();
                    var end = $('#inputTanggalEnd').val();

                    if (start && end) {
                        table.ajax.url('getSuratKeluar?mulai=' + start + '&selesai=' + end).load();
                    }
                });
                // Filter rentang tanggal end
            });
        </script>
    @endif
    {{-- End admin --}}
    <!-- Initializing data tables end -->

    {{-- Cek semua sudah arsip start --}}
    <script>
        $(document).ready(function() {
            let btnWrapper = document.getElementById('btnRegistrasi')
            let user = '{{ $user->nip }}'

            $.ajax({
                url: "/getSuratKeluar", // Ganti dengan rute yang sesuai
                dataType: "json",
                data: {
                    'start': 0,
                    'length': 999999999999,
                },
                success: function(data) {
                    var dataTableData = data.data;
                    let semuaSudahArsip = true; // Inisialisasi dengan true
                    let jumlah = 0;

                    dataTableData.forEach(item => {
                        if (item.sifatSurat != 4 && !item.lampiran && item.status ==
                            'digunakan' && item.created_by == user) {
                            semuaSudahArsip = false;
                            jumlah++;
                        }
                    });

                    if (semuaSudahArsip) {
                        btnWrapper.innerHTML = `
                        <button type="button" data-bs-toggle="modal" data-bs-target="#registrasiSuratKeluar"
                            class="mybtn blue">
                            <i class="fa-solid fa-plus me-2"></i>Registrasi Surat
                        `
                    } else {
                        btnWrapper.innerHTML = `
                        </button>
                            <button type="button" onclick="reminderArsip()" class="mybtn blue">
                                <i class="fa-solid fa-plus me-2"></i>Registrasi Surat
                        </button>
                        `
                    }
                }
            });

        });
    </script>
    {{-- Cek semua sudah arsip end --}}

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

    {{-- Sweetalert untuk melengkapi arsip surat start --}}
    <script>
        function reminderArsip() {
            Swal.fire({
                confirmButtonColor: "#2F5596",
                icon: 'warning',
                title: `Perhatian`,
                text: `Silahkan upload arsip pada surat yang telah Anda buat terlebih dahulu untuk dapat kembali mengambil nomor surat. Terima kasih!`,
            })
            new Audio("{{ asset('audio/warning-edited.mp3') }}").play();
        }
    </script>
    {{-- Sweetalert untuk melengkapi arsip surat end --}}

    {{-- Loader length menu tables start --}}
    <script>
        $(document).ready(function() {
            var table = $('#mytable').DataTable();

            table.on('length.dt', function(e, settings, len) {
                showLoading();
            });

            table.on('draw.dt', function() {
                hideLoading();
            });
        });

        function showLoading() {
            $('.loader__tables').show();
        }

        function hideLoading() {
            $('.loader__tables').hide();
        }
    </script>
    {{-- Loader length menu tables end --}}

@endsection
@section('sk', 'active')
@section('title', 'Surat Keluar')
