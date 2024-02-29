@extends('template')
@section('disposisi', 'active')
@section('title', 'Disposisi Surat Masuk')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap data tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />

    <!-- Datepicker Jquery -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
@endsection
@section('content')
    <section class="surat__masuk content">
        {{-- Navigation start --}}
        <div class="navigation__content mb-4">
            <h5 class="fw__semi black">DIPOSISI SURAT MASUK</h5>
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

        {{-- Tabel wrapper start --}}
        <div class="card p-4 mt-3">
            {{-- Tabel header start --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <h4 class="fw-semibold black">Daftar Disposisi Surat Masuk</h4>
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
                            <th>Tanggal Disposisi</th>
                            <th>Penerima</th>
                            <th>Status Disposisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disposisis as $item)
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->asalSurat }} <br> <span class="pt-2 d-inline-block">Nomor :
                                    {{ $item->nomorSurat }}
                                </span></td>
                            <td>{{ $item->tanggal_disposisi }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->status_disposisi }}</td>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Tabel content end --}}
        </div>
        {{-- Tabel wrapper end --}}
    </section>
@endsection
@section('js')
    {{-- Sweet alert start --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Sweet alert end --}}

    <!-- Data tables start -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js">
    </script>
    <!-- Data tables end -->

    <!-- Data tables : responsive start -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <!-- Data tables : responsive end -->

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
