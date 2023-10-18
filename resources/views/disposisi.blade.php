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
            <h5 class="fw__semi black">DISPOSISI SURAT MASUK</h5>
        </div>
        {{-- Navigation end --}}

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
                <h4 class="fw-semibold black">Disposisi Surat Masuk</h4>
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

            {{-- @dd($suratMasuk) --}}

            {{-- Tabel content start --}}
            <div class="table-responsive">
                <table id="mytable" class="table table-borderless">
                    <thead>
                        <tr>
                            <th class="no">#</th>
                            <th>Asal Surat / No. Surat</th>
                            <th>Tanggal Surat</th>
                            <th>Tujuan Penerima</th>
                            <th>Perihal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disposisi as $k => $v)
                            <!-- Modal teruskan surat start -->
                            <div class="modal modal__section fade" data-bs-backdrop="static"
                                id="teruskanSurat{{ $v->id }}" data-bs-backdrop="static" tabindex="-1"
                                aria-labelledby="ex ampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content p-3">
                                        <div class="modal-header">
                                            <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                                                Teruskan Surat Ke
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form id="form{{ $v->id }}" class="needs-validation" novalidate
                                            method="POST" action="{{ route('teruskanSurat') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="surat_masuk_id" value="{{ $v->surat_masuk_id }}">
                                            <input type="hidden" name="pengirim_disposisi" value="{{ $user->nip }}">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="mb-3">
                                                        <label for="penerima_disposisi"
                                                            class="form-label black fw-normal">Pilih Penerima</label>
                                                        <select class="form-select" aria-label="Default select example"
                                                            required name="penerima_disposisi">
                                                            <option selected disabled value="">...</option>
                                                            @foreach ($userDisposisi as $item)
                                                                <option value="{{ $item->nip }}">{{ $item->name }}
                                                                </option>
                                                                {{-- @endif --}}
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Masukkan tujuan penerima surat dengan benar.
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tindakan" class="form-label black fw-normal">Pilih
                                                            Tindakan</label>
                                                        <select class="form-select" id="tindakan"
                                                            aria-label="Default select example" required name="tindakan">
                                                            <option selected disabled value="">...</option>
                                                            @foreach ($tindakLanjut as $item)
                                                                <option value="{{ $item->nama_tindak_lanjut }}">
                                                                    {{ $item->nama_tindak_lanjut }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Masukkan tujuan penerima surat dengan benar.
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlTextarea1"
                                                            class="form-label black fw-normal">Catatan Disposisi</label>
                                                        <textarea class="form-control perihal" id="exampleFormControlTextarea1" rows="4" placeholder=""
                                                            name="catatan_disposisi" required></textarea>
                                                        <div class="invalid-feedback">
                                                            Catatan Disposisi
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="mybtn light" data-bs-dismiss="modal">
                                                    Batal
                                                </button>
                                                <button type="submit" form="form{{ $v->id }}" class="mybtn blue"
                                                    type="submit">
                                                    Teruskan
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <!-- Modal teruskan surat end -->
                            <!-- Modal detail disposisi start -->
                            <div class="modal modal__section fade" data-bs-backdrop="static"
                                id="detailDisposisi{{ $v->id }}" data-bs-backdrop="static" tabindex="-1"
                                aria-labelledby="ex ampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content p-3">
                                        <div class="modal-header">
                                            <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                                                Detail Disposisi
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @php
                                                $tracking = explode('|', $v->trace);
                                                foreach ($tracking as $i => $tr) {
                                                    $ct = count($tracking);
                                                    $item = explode(',', $tr);
                                                    echo '<div class="text-center bg-green mb-1 mx-auto w-75 rounded">
                                                        ' .
                                                        $item[0] .
                                                        '
                                                        <br>
                                                        ' .
                                                        date('r', strtotime($item[1])) .
                                                        '
                                                        <br>
                                                        ' .
                                                        $item[2] .
                                                        '</div>';
                                                    if ($ct >= 2) {
                                                        if ($i + 1 != $ct) {
                                                            echo '<i class="w-100 text-center fa-solid fa-down-long"></i>';
                                                        }
                                                    }
                                                }
                                            @endphp

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal detail disposisi end -->
                            <script>
                                console.log(@json($v))
                            </script>
                            <tr>
                                <td>{{ $k + 1 }}</td>
                                <td>
                                    {{ $v->asalSurat }} <br> <span class="pt-2 d-inline-block">Nomor :
                                        {{ $v->nomorSurat }}
                                    </span>
                                </td>
                                <td>{{ date('Y-m-d', strtotime($v->created_at)) }}</td>
                                <td>{{ $v->name }}</td>
                                <td>{{ $v->perihal }}</td>
                                <td>{{ $v->status }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-start gap-2 flex-wrap">
                                        <a data-bs-toggle="modal" data-bs-target="#teruskanSurat{{ $v->id }}"
                                            class="test myicon position-relative green d-flex align-items-center justify-content-center">
                                            <i class="fa-solid fa-file-export"></i>
                                            <div class="position-absolute mytooltip">
                                                <div class="text-white px-3 py-2 position-relative">
                                                    Teruskan
                                                </div>
                                                <div id="arrow"></div>
                                            </div>
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#detailDisposisi{{ $v->id }}"
                                            class="test myicon position-relative blue d-flex align-items-center justify-content-center">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                            <div class="position-absolute mytooltip">
                                                <div class="text-white px-3 py-2 position-relative">
                                                    Detail
                                                </div>
                                                <div id="arrow"></div>
                                            </div>
                                        </a>
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
@section('disposisi', 'active')
@section('title', 'Disposisi Surat')
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
            PDFObject.embed(`{{ asset('public/uploads/${id}') }}`, "#example1", options);
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
    @if ($message = Session::get('success'))
        <script>
            berhasil("{{ Session::get('success') }}")
        </script>
    @elseif($message = Session::get('failed'))
        <script>
            gagal("{{ Session::get('failed') }}")
        </script>
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
