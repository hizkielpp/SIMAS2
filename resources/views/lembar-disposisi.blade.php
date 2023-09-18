<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lembar Disposisi</title>

    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />

    <!-- Custom css -->
    <link rel="stylesheet" href="{{ asset('css/lembar-disposisi-style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>

<body>
    <div class="disposisi py-4 mx-auto container">
        {{-- Header start --}}
        <div class="disposisi__header w-75 mx-auto mb-1">
            <h1 class="text-center fw__semi">
                KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI UNIVERSITAS
                DIPONEGORO FAKULTAS KEDOKTERAN SEMARANG <br />
                LEMBAR DISPOSISI
            </h1>
        </div>
        {{-- Header end --}}

        {{-- Informasi start --}}
        <div class="disposisi__info row">
            <div class="col-6">
                <form action="">
                    <div class="d-flex align-items-start mb-1">
                        <div class="disposisi__info-title d-flex justify-content-between">
                            <h4>Tanggal Agenda Surat</h4>
                            <h4>:</h4>
                        </div>
                        <div class="disposisi__info-body ms-2">
                            <h4>{{ date('d ', strtotime($surat->created_at)) }}{{ convertToBulan(date('F', strtotime($surat->created_at))) }}{{ date(' Y ', strtotime($surat->created_at)) }}
                            </h4>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-1">
                        <div class="disposisi__info-title d-flex justify-content-between">
                            <h4>Tanggal Surat</h4>
                            <h4>:</h4>
                        </div>
                        <div class="disposisi__info-body ms-2">
                            <h4>{{ date('d ', strtotime($surat->tanggalPengajuan)) }}{{ convertToBulan(date('F', strtotime($surat->tanggalPengajuan))) }}{{ date(' Y ', strtotime($surat->tanggalPengajuan)) }}
                            </h4>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-1">
                        <div class="disposisi__info-title d-flex justify-content-between">
                            <h4>Nomor Surat</h4>
                            <h4>:</h4>
                        </div>
                        <div class="disposisi__info-body ms-2">
                            <h4>{{ $surat->nomorSurat }}</h4>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-1">
                        <div class="disposisi__info-title d-flex justify-content-between">
                            <h4>Asal Surat</h4>
                            <h4>:</h4>
                        </div>
                        <div class="disposisi__info-body ms-2">
                            <h4>{{ $surat->asalSurat }}</h4>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-6">
                <div class="d-flex align-items-start mb-1">
                    <div class="disposisi__info-title d-flex justify-content-between">
                        <h4>Nomor Agenda</h4>
                        <h4>:</h4>
                    </div>
                    <div class="disposisi__info-body ms-2">
                        <h4>{{ $surat->nomorAgenda }}</h4>
                    </div>
                </div>
                <div class="d-flex align-items-start mb-1">
                    <div class="disposisi__info-title d-flex justify-content-between">
                        <h4>Kode Hal</h4>
                        <h4>:</h4>
                    </div>
                    <div class="disposisi__info-body ms-2">
                        <h4>{{ $surat->nama }}</h4>
                    </div>
                </div>
                <div class="d-flex align-items-start mb-1">
                    <div class="disposisi__info-title d-flex justify-content-between">
                        <h4>Perihal</h4>
                        <h4>:</h4>
                    </div>
                    <div class="disposisi__info-body ms-2">
                        <h4>
                            {{ $surat->perihal }}
                        </h4>
                    </div>
                </div>
                <div class="d-flex align-items-start mb-1">
                    <div class="disposisi__info-title d-flex justify-content-between">
                        <h4>Lampiran</h4>
                        <h4>:</h4>
                    </div>
                    @if ($surat->jumlahLampiran)
                        <div class="disposisi__info-body ms-2">
                            <h4>{{ $surat->jumlahLampiran }} Lembar</h4>
                        </div>
                    @else
                        <div class="disposisi__info-body ms-2">
                            <h4>-</h4>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <div class="d-flex align-items-center">
                        <div class="checkbox"></div>
                        <h4>Penting *</h4>
                    </div>
                </div>
                <div class="col-3">
                    <div class="d-flex align-items-center">
                        <div class="checkbox"></div>
                        <h4>Segera *</h4>
                    </div>
                </div>
                <div class="col-3">
                    <div class="d-flex align-items-center">
                        <div class="checkbox"></div>
                        <h4>Rahasia *</h4>
                    </div>
                </div>
                <div class="col-3">
                    <div class="d-flex align-items-center">
                        <div class="checkbox"></div>
                        <h4>Biasa *</h4>
                    </div>
                </div>
            </div>
        </div>
        {{-- Informasi end --}}

        {{-- Tabel start --}}
        <table class="table table-bordered mt-1">
            <thead>
                <tr>
                    <th scope="col">Tgl</th>
                    <th scope="col">Kepada</th>
                    <th scope="col" colspan="2">Disposisi</th>
                    <th scope="col">Isi</th>
                    <th scope="col">Paraf</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>1</td>
                    <td>Mohon Pertimbangan</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>2</td>
                    <td>Mohon Pendapat</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>3</td>
                    <td>Mohon Keputusan</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>4</td>
                    <td>Mohon Petunjuk</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>5</td>
                    <td>Mohon Saran</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>6</td>
                    <td>Bicarakan</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>7</td>
                    <td>Teliti/Ikuti Perkembangan</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>8</td>
                    <td>Untuk Perhatian</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>9</td>
                    <td>Siapkan Laporan</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>10</td>
                    <td>Siapkan Konsep</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>11</td>
                    <td>Untuk Diproses</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>12</td>
                    <td>Selesaikan Sesuai Pembicaraan</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>13</td>
                    <td>Edaran</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>14</td>
                    <td>Gandakan</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td>15</td>
                    <td>Arsip</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        {{-- Tabel end --}}

        {{-- Tanda tangan start --}}
        <h5 class="text-center" style="font-size: 14px">Keterangan: Mohon diisi oleh pimpinan sesuai dengan sifat surat
        </h5>
        {{-- Tanda tangan end --}}

        {{-- Button cetak start --}}
        <div class="d-flex justify-content-end mt-5">
            <button id="cetak" class="d-flex justify-content-center align-items-center mybtn blue"
                onclick="window.print()">Cetak</button>
        </div>
        {{-- Button cetak end --}}

    </div>

    {{-- Modal iframe start start --}}
    <div class="modal modal__section fade" id="registrasiSuratMasuk" tabindex="-1" aria-labelledby="ex ampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h4 class="modal-title fw__bold black" id="exampleModalLabel">
                        Form Registrasi Surat Masuk
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="mybtn light" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="mybtn blue" onclick="confirmAdd()">
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal iframe start end --}}
</body>

</html>
