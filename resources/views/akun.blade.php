@extends('template')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Bootstrap data tables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />

<!-- Custom CSS -->
<link rel="stylesheet" href="css/surat-masuk-style.css" />
@endsection
@section('content')
<section class="surat__masuk content">
    {{-- @dd($users) --}}
    {{-- Navigation start --}}
    <div class="navigation__content mb-4">
        <h5 class="fw__semi black">KELOLA AKUN</h5>
    </div>
    {{-- Navigation end --}}

    @if (session()->has('failed'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Edit akun gagal!</strong>
        <p class="mt-2">{{session('failed')}}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card p-4 mt-3">
        <!-- Modal registrasi start -->
        <div class="modal fade" id="registrasiAkun" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                            Form Registrasi Akun
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formRegistrasi" enctype="multipart/form-data" class="needs-validation" novalidate
                            method="POST" action="{{ route('inputAkun') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label black fw-normal">Nama</label>
                                        <input type="text" class="form-control" placeholder="Masukkan nama pengguna"
                                            id="name" name="name" />
                                        <div class="invalid-feedback">
                                            Isian nama wajib diisi.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nip" class="form-label black fw-normal">NIP</label>
                                        <input type="text" class="form-control" placeholder="Masukkan NIP pengguna"
                                            id="nip" name="nip" required />
                                        <div class="invalid-feedback">
                                            Isian NIP wajib diisi.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label black fw-normal">Email</label>
                                        <input type="text" class="form-control" placeholder="Masukkan email pengguna"
                                            id="email" name="email" required />
                                        <div class="invalid-feedback">
                                            Isian email wajib diisi.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="role" class="form-label black fw-normal">Peran</label>
                                        <select id="role" name="role_id" class="form-select"
                                            aria-label="Default select example" required>
                                            <option selected disabled value="">...</option>
                                            @foreach ($role as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Isian peran wajib diisi.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label black fw-normal">Password</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control"
                                                placeholder="Masukkan password pengguna" id="password" name="password"
                                                aria-describedby="emailHelp" required />
                                            <i class="far fa-eye-slash position-absolute" id="passIcon"
                                                onclick="showPass()"
                                                style="top: 50%; right:3%; transform: translateY(-50%)"></i>
                                        </div>
                                        <div class="invalid-feedback">
                                            Isian password wajib diisi.
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
            <h4 class="fw-semibold black">Daftar Akun</h4>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <button id="tes" type="button" data-bs-toggle="modal" data-bs-target="#registrasiAkun"
                    class="mybtn blue">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Akun
                </button>
            </div>
        </div>
        {{-- Tabel header end --}}

        {{-- Tabel content start --}}
        <div class="table-responsive">
            <table id="mytable" class="table table-borderless">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $pengguna)
                    <tr>
                        <td>{{ $pengguna->name }}</td>
                        <td>{{ $pengguna->email }}</td>
                        <td>{{ $pengguna->roleTabel->nama }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#editSuratKeluar"
                                    class="myicon blue d-flex align-items-center justify-content-center me-2 passId"
                                    data-id="{{ $pengguna->id }}">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button type="button"
                                    class="myicon red d-flex align-items-center justify-content-center"
                                    onclick="confirmHapus('{{ $pengguna->id }}')">
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

        <!-- Modal edit start -->
        <div class="modal fade" id="editSuratKeluar" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h4 class="modal-title fw-semibold black" id="exampleModalLabel">
                            Form Edit Akun
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEdit" enctype="multipart/form-data" method="POST"
                            action="{{ route('editAkun') }}">
                            @csrf
                            <input type="text" id="idAkun" name="idAkun" hidden>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label black fw-normal">Nama</label>
                                        <input type="text" class="form-control" placeholder="Masukkan nama akun"
                                            id="nameE" name="name" value="{{ $pengguna->nama }}" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="role" class="form-label black fw-normal">Role</label>
                                        <select id="role_id" name="role_id" class="form-select"
                                            aria-label="Default select example">
                                            <option selected>-- Pilih salah satu --</option>
                                            @foreach ($role as $k => $v)
                                            <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label black fw-normal">Password Baru</label>
                                        <div class="d-flex">
                                            <input type="password" class="form-control"
                                                placeholder="Masukkan password baru" id="passwordE" name="password"
                                                aria-describedby="emailHelp" />
                                            <i class="far fa-eye-slash" id="passIconE" onclick="showPassE()"
                                                style="margin-left: -30px;margin-top:8px; cursor: pointer;"></i>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label black fw-normal">Konfirmasi Password
                                            Baru</label>
                                        <div class="d-flex">
                                            <input type="password" class="form-control"
                                                placeholder="Masukkan password kembali" id="passwordConfirmation"
                                                name="passwordConfirmation" aria-describedby="emailHelp" />
                                            <i class="far fa-eye-slash" id="passIconEC" onclick="showPassEC()"
                                                style="margin-left: -30px;margin-top:8px; cursor: pointer;"></i>
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
                        <button type="button" class="mybtn blue" onclick="confirmEdit()">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal edit end -->

    </div>

</section>
@endsection
@section('js')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Edit akun start --}}
<script>
    $(".passId").click(function() {
                let url = "{{ route('getAkun', ':id') }}";
                url = url.replace(':id', $(this).data('id'));
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                        $('#nameE').attr('value', data.name)
                        $("#role_id").val(data.role_id)
                    }
                });
                $('#idAkun').attr('value', $(this).data('id'));
            });
</script>
{{-- Edit akun end --}}

{{-- Refresh page start --}}
<script>
    function refreshDatatable() {
            setInterval('location.reload()', 2000);
            // window.location.reload();
        }
</script>
{{-- Refresh page end --}}

<!-- Sweet alert : confirm delete start -->
<script>
    function confirmHapus(id) {
            new Audio("audio/warning-edited.mp3").play();
            Swal.fire({
                title: "Konfirmasi",
                text: "Yakin ingin menghapus akun?",
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
                        url: '{{ route('deleteAkun') }}',
                        data: {
                            "idAkun": id,
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
                    $('#formRegistrasi').submit();
                    $("#registrasiAkun").modal("hide");
                } else {
                    new Audio("audio/cancel-edited.mp3").play();
                    $("#registrasiAkun").modal("hide");
                }
            });
        }
</script>
<!-- Sweet alert : confirm add end -->


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
                    var password = $('#passwordE').val()
                    var confirmPassword = $('#passwordConfirmation').val()
                    if (password != confirmPassword) {
                        Swal.fire({
                            confirmButtonColor: "#2F5596",
                            text: "Password dan konfirmasi password tidak sesuai"
                        });
                        new Audio("audio/error-edited.mp3").play();
                    } else {
                        $('#formEdit').submit();
                        $("#editSuratKeluar").modal("hide");
                    }
                } else {
                    new Audio("audio/cancel-edited.mp3").play();
                    $("#editSuratKeluar").modal("hide");
                }
            });
        }
</script>
<!-- Sweet alert : confirm edit end -->


<!-- Show/hide password start -->
<script>
    function showPassE() {
            var input = document.getElementById("passwordE");
            var icon = document.getElementById("passIconE");
            if (input.type === "password") {
                input.type = "text";
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        }

        function showPassEC() {
            var input = document.getElementById("passwordConfirmation");
            var icon = document.getElementById("passIconEC");
            if (input.type === "password") {
                input.type = "text";
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        }

        function showPass() {
            var input = document.getElementById("password");
            var icon = document.getElementById("passIcon");
            if (input.type === "password") {
                input.type = "text";
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        }
</script>
<!-- Show/hide password end -->

<!-- Data tables -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js">
</script>

<!-- Data tables : responsive start -->
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<!-- Data tables : responsive end -->

<!-- Initializing data tables -->
<script>
    var btn = document.getElementById("tes");
        $(document).ready(function() {
            $("#mytable").DataTable({
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.childRowImmediate,
                        type: "none",
                        target: "",
                    },
                },
                dom: 'frt<"d-flex justify-content-between mt-3 overflow-hidden"<"d-flex align-items-center"li>p>',
                // dom: '<"table-responsive"tpf>',
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
{{-- set value of duet date picker --}}
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

@endsection
@section('ka', 'active')
@section('title', 'Kelola Akun')