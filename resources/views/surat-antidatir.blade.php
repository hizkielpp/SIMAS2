@extends('template')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">

      <!-- Bootstrap data tables -->
      <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css"
    />

    <!-- Date picker -->
    <script
      type="module"
      src="https://cdn.jsdelivr.net/npm/@duetds/date-picker@1.4.0/dist/duet/duet.esm.js"
    ></script>
    <script
      nomodule
      src="https://cdn.jsdelivr.net/npm/@duetds/date-picker@1.4.0/dist/duet/duet.js"
    ></script>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@duetds/date-picker@1.4.0/dist/duet/themes/default.css"
    />
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
    <a href="{{ route('downloadNaskah') }}" class="mybtn blue fw__light"
      ><i class="fa-solid fa-download me-1"></i> Download
    </a>
  </div>
  <div class="card p-4 mt-3">
          <!-- Modal -->
          <div
          class="modal fade"
          id="registrasiSuratKeluar"
          tabindex="-1"
          aria-labelledby="exampleModalLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-lg">
            <div class="modal-content p-3">
              <div class="modal-header">
                <h4
                  class="modal-title fw__bold black"
                  id="exampleModalLabel"
                >
                  Form Registrasi Surat Antidatir
                </h4>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <form id="formRegistrasi" enctype="multipart/form-data" method="POST" action="{{ route('inputSA') }}">
                @csrf  
                  <div class="row">
                    <div class="col-lg-6 col-12">
                      <div class="mb-3">
                        <label
                          for="nomorSurat"
                          class="form-label black fw-semibold"
                          >Nomor Surat</label
                        >
                        <div class="input d-flex align-items-center">
                          <input
                            type="text"
                            readonly
                            class="form-control"
                            placeholder="Masukkan nomor surat"
                            id="nomorSurat"
                            name="nomorSurat"
                            aria-describedby="emailHelp"
                          />
                          <button type="button" class="ms-2" onclick="ambilNomor()">
                            Ambil Nomor
                          </button>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label
                          for="kodeUnit"
                          class="form-label black fw-semibold"
                          >Kode Unit Surat</label
                        >
                        <select
                          id="kodeUnit"
                          name="kodeUnit"
                          class="form-select"
                          aria-label="Default select example"
                        >
                          <option selected>-- Pilih salah satu --</option>
                          @foreach ($unit as $k=>$v)
                            <option value="{{ $v->kode }}">{{ $v->nama }} ({{ $v->kode }})</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="mb-3">
                        <label
                          for="kodeHal"
                          class="form-label black fw-semibold"
                          >Kode Hal</label
                        >
                        <select
                          id="kodeHal"
                          name="kodeHal"
                          class="form-select"
                          aria-label="Default select example"
                        >
                          <option selected>-- Pilih salah satu --</option>
                          @foreach ($hal as $k=>$v)
                            <option value="{{ $v->kode }}">{{ $v->nama }} ({{ $v->kode }})</option>
                          @endforeach                      
                        </select>
                      </div>
                      <div class="mb-3">
                        <label
                          for="tujuanSurat"
                          class="form-label black fw-semibold"
                          >Tujuan Surat</label
                        >
                        <input
                        type="text"
                        class="form-control"
                        placeholder="Masukkan tujuan surat"
                        id="tujuanSurat"
                        name="tujuanSurat"
                        aria-describedby="emailHelp"
                      />
  
                      </div>
                      <div class="mb-3">
                        <label
                          for="jumlahLampiran"
                          class="form-label black fw-semibold"
                          >Jumlah Lampiran</label
                        >
                        <input
                        type="number"
                        class="form-control"
                        placeholder="Masukkan tujuan surat"
                        id="jumlahLampiran"
                        name="jumlahLampiran"
                        aria-describedby="emailHelp"
                      />
                      </div>
                      <div class="mb-3">
                        <label
                          for="sifatSurat"
                          class="form-label black fw-semibold"
                          >Sifat</label
                        >
                        <select
                          id="sifatSurat"
                          name="sifatSurat"
                          class="form-select"
                          aria-label="Default select example"
                        >
                          <option selected>-- Pilih salah satu --</option>
                          @foreach ($sifat as $k=>$v)
                            <option value="{{ $v->kode }}">{{ $v->nama }}</option>  
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-6 col-12">
                      <div class="mb-3">
                        <label
                          for="disahkanOleh"
                          class="form-label black fw-semibold"
                          >Disahkan Oleh</label
                        >
                        <select
                          id="disahkanOleh"
                          name="disahkanOleh"
                          class="form-select"
                          aria-label="Default select example"
                        >
                          <option selected>-- Pilih salah satu --</option>
                          @foreach ($unit as $k=>$v)
                            <option value="{{ $v->nama }}">{{ $v->nama }}</option>  
                          @endforeach
                        </select>
                      </div>
                      <div class="mb-3">
                        <label
                          for="date"
                          class="form-label black fw-semibold"
                          >Tanggal Disahkan</label
                        >
                        <duet-date-picker
                          id="date"
                          name="tanggalPengesahan"
                          identifier="date"
                        ></duet-date-picker>
                      </div>
                      <div class="mb-3">
                        <label
                          for="lampiran"
                          class="form-label black fw-semibold"
                          >Upload Lampiran</label
                        >
                        <input
                          type="file"
                          class="form-control"
                          id="lampiran"
                          name="lampiran"
                          aria-describedby="emailHelp"
                        />
                      </div>
                      <div class="mb-3">
                        <label
                          for="exampleFormControlTextarea1"
                          class="form-label black fw-semibold"
                          >Perihal</label
                        >
                        <textarea
                          name="perihal"
                          class="form-control perihal"
                          id="exampleFormControlTextarea1"
                          rows="8"
                          placeholder="Contoh : Permohonan perijinan penelitian"
                        ></textarea>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="mybtn light"
                  data-bs-dismiss="modal"
                >
                  Batal
                </button>
                <button
                  type="button"
                  class="mybtn blue"
                  onclick="confirmAdd()"
                >
                  Tambah
                </button>
              </div>
            </div>
          </div>
        </div>
    <div
    class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3"
  >
    <h4 class="fw__bold black">Daftar Surat Antidatir</h4>
    <div
      class="d-flex align-items-center gap-3 input__tanggal flex-wrap"
    >
      
      <p class="">Rentang Tanggal :</p>
      <input
        type="date"
        name="inputTanggal"
        id="inputTanggalStart"
        class="mybtn"
      />
      <input
        type="date"
        name="inputTanggalEnd"
        id="inputTanggalEnd"
        class="mybtn"
      />
      <button
        id="tes"
        type="button"
        data-bs-toggle="modal"
        data-bs-target="#registrasiSuratKeluar"
        class="mybtn blue"
      >
        <i class="fa-solid fa-plus me-2"></i>Registrasi Surat
      </button>
    </div>
  </div>
    <div class="table-responsive">
      <table id="mytable" class="table">
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
          @foreach ($suratAntidatir as $k=>$v )
          <tr>
            <td class="no">{{ $k+1 }}</td>
            <td>
              {{ $v->tujuanSurat }} <br>Nomor : 
              {{ $v->nomorSurat }}/{{ $v->kodeUnit }}/{{ date('Y', strtotime($v->tanggalPengesahan)) }}/{{ convertToRomawi(date('m', strtotime($v->tanggalPengesahan))) }}
              <br />
              <span class="date d-inline-block mt-1"
                >{{ date('d M Y h:i', strtotime($v->created_at))}} WIB</span
              >
            </td>
            <td>{{ $v->perihal }}</td>
            <td>{{ $v->tujuanSurat }}</td>
            <td>
              @if ($v->sifatSurat==1)
              <div
              class="sifat biasa d-flex justify-content-center align-items-center"
              >
                <h6 class="fw__semi">Biasa</h6>
              </div>
              @elseif ($v->sifatSurat==2)
              <div
              class="sifat penting d-flex justify-content-center align-items-center"
              >
                <h6 class="fw__semi">Penting</h6>
              </div>
              @elseif ($v->sifatSurat==3)
              <div
              class="sifat segera d-flex justify-content-center align-items-center"
              >
                <h6 class="fw__semi">Segera</h6>
              </div>
              @elseif ($v->sifatSurat==4)
              <div
              class="sifat rahasia d-flex justify-content-center align-items-center"
              >
                <h6 class="fw__semi">Rahasia</h6>
              </div>
              @endif
            </td>
            <td>
              <div class="d-flex align-items-center">
                <button
                  type="button"
                  data-bs-toggle="modal"
                  data-bs-target="#editSuratKeluar"
                  class="myicon blue d-flex align-items-center justify-content-center me-2 passId"
                  data-id="{{ $v->id }}"
                >
                  <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button
                  type="button"
                  class="myicon red d-flex align-items-center justify-content-center"
                  onclick="confirmHapus('{{ $v->id }}')"
                >
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>         
          @endforeach
        </tbody>
      </table>
      {{-- @if($errors->any())
      <script>gagal()</script>
      {{ implode('', $errors->all('<div>:message</div>')) }}
      @endif --}}
    </div>
                    <!-- Modal -->
                    <div
                    class="modal fade"
                    id="editSuratKeluar"
                    tabindex="-1"
                    aria-labelledby="exampleModalLabel"
                    aria-hidden="true"
                  >
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content p-3">
                        <div class="modal-header">
                          <h4
                            class="modal-title fw__bold black"
                            id="exampleModalLabel"
                          >
                            Form Edit Surat Antidatir
                          </h4>
                          <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                          ></button>
                        </div>
                        <div class="modal-body">
                          <form id="formEdit" method="POST" enctype="multipart/form-data" action="{{ route('editSK') }}">
                            @csrf
                              <div class="row">
                                <div class="col-lg-6 col-12">
                                  {{-- nomor surat tidak usah --}}
                                  {{-- <div class="mb-3">
                                    <label
                                      for="noSurat"
                                      class="form-label black fw-semibold"
                                      >Nomor Surat</label
                                    >
                                    <input
                                      disabled
                                      type="text"
                                      class="form-control"
                                      placeholder="Masukkan nomor surat"
                                      id="noSurat"
                                      aria-describedby="emailHelp"
                                    />
                                  </div> --}}
                                  <input type="text"name="jenisSurat" hidden>
                                  <input type="text" name="idSurat" hidden>
                                  <div class="mb-3">
                                    <label
                                      for="kodeUnitE"
                                      class="form-label black fw-semibold"
                                      >Kode Unit Surat</label
                                    >
                                    <select
                                      class="form-select"
                                      aria-label="Default select example"
                                      id="kodeUnitE"
                                      name="kodeUnit"
                                    >
                                      <option selected value="">
                                        -- Pilih salah satu --
                                      </option>
                                      @foreach ($unit as $k=>$v)
                                        <option value="{{ $v->kode }}">{{ $v->nama }} ({{ $v->kode }})</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label
                                      for="kodeHalE"
                                      class="form-label black fw-semibold"
                                      >Kode Hal</label
                                    >
                                    <select
                                      class="form-select"
                                      aria-label="Default select example"
                                      id="kodeHalE"
                                      name="kodeHal"
                                    >
                                      <option value="" selected>
                                        -- Pilih salah satu --
                                      </option>
                                      @foreach ($hal as $k=>$v)
                                        <option value="{{ $v->kode }}">{{ $v->nama }} ({{ $v->kode }})</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label
                                      for="tujuanSuratE"
                                      class="form-label black fw-semibold"
                                      >Tujuan Surat</label
                                    >
                                    <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Masukkan nomor surat"
                                    id="tujuanSuratE"
                                    name="tujuanSurat"
                                    aria-describedby="emailHelp"
                                  />
                                  </div>
                                  <div class="mb-3">
                                    <label
                                      for="sifatSuratE"
                                      class="form-label black fw-semibold"
                                      >Sifat</label
                                    >
                                    <select
                                      id="sifatSuratE"
                                      name="sifatSurat"
                                      class="form-select"
                                      aria-label="Default select example"
                                    >
                                      <option value="" selected>
                                        -- Pilih salah satu --
                                      </option>
                                      @foreach ($sifat as $k=>$v)
                                      <option value="{{ $v->kode }}">{{ $v->nama }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label
                                      class="form-label black fw-semibold"
                                      >Upload Lampiran</label
                                    >
                                    <input
                                      type="file"
                                      class="form-control"
                                      name="lampiran"
                                      aria-describedby="emailHelp"
                                    />
                                    <span>Nama file lampiran : </span><span id="lampiranE"></span>
                                  </div>
                                  <div class="mb-3">
                                    <label
                                      for="jumlahLampiranE"
                                      class="form-label black fw-semibold"
                                      >Jumlah Lampiran</label
                                    >
                                    <input
                                    type="number"
                                    class="form-control"
                                    placeholder="Masukkan jumlah lampiran"
                                    id="jumlahLampiranE"
                                    name="jumlahLampiran"
                                    aria-describedby="emailHelp"
                                  />
                                  </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                  <div class="mb-3">
                                    <label
                                      for="disahkanOlehE"
                                      class="form-label black fw-semibold"
                                      >Disahkan Oleh</label
                                    >
                                    <select
                                      id="disahkanOlehE"
                                      name="disahkanOleh"
                                      class="form-select"
                                      aria-label="Default select example"
                                    >
                                      <option value="" selected>
                                        -- Pilih salah satu --
                                      </option>
                                      @foreach ($unit as $k=>$v )
                                        <option value="{{ $v->nama }}">{{ $v->nama }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label
                                      for="tanggalPengesahanE"
                                      class="form-label black fw-semibold"
                                      >Tanggal Disahkan</label
                                    >
                                    <duet-date-picker
                                      id="tanggalPengesahanE"
                                      identifier="date"
                                      name="tanggalPengesahan"
                                    ></duet-date-picker>
                                  </div>
                                  <div class="mb-3">
                                    <label
                                      for="perihalE"
                                      class="form-label black fw-semibold"
                                      >Perihal</label
                                    >
                                    <textarea
                                      class="form-control perihal"
                                      id="perihalE"
                                      name="perihal"
                                      rows="12"
                                      placeholder="Contoh : Permohonan perijinan penelitian"
                                    ></textarea>
                                  </div>
                                </div>
                              </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                          <button
                            type="button"
                            class="mybtn light"
                            data-bs-dismiss="modal"
                          >
                            Batal
                          </button>
                          <button
                            type="button"
                            class="mybtn blue"
                            onclick="confirmEdit()"
                          >
                            Simpan
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
  </div>

</section>
@endsection
@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Sweet alert -->
<script src="sweetalert2.all.min.js"></script>
{{-- Refresh page --}}
<script>
  function refreshDatatable(){
    setInterval('location.reload()', 4000);
    // window.location.reload();
  }
</script>
<!-- Sweet alert : confirm delete -->
<script>
  function confirmHapus(id) {
    // refreshDatatable()
    console.log(id);
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
            url: '{{ route("deleteSK") }}',
            data: {
              "idSurat": id,
              "_token": token,
            },
            success: function (data) { 
              Swal.fire("Berhasil!", data, "success");
              new Audio("audio/success-edited.mp3").play(); 
              refreshDatatable();
              },
            error: function (error) {
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
    var url = '{{ route("cekTersedia", ":id") }}';
    url = url.replace(':id', $('input[name="nomorSurat"]').attr('value'));
    url += "?sumber=keluar&jenis=antidatir"
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
              document.getElementById('formRegistrasi').submit();
              $("#registrasiSuratKeluar").modal("hide");
            }else {
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
<script>
  function ambilNomor() {

    if(dateNow==""){
      Swal.fire({      
        confirmButtonColor: "#2F5596",
        text: "Silahkan isi tanggal disahkan terlebih dahulu!"});
      new Audio("audio/error-edited.mp3").play(); 
      
    }else{
    month = 1+dateNow.getMonth()
    date = dateNow.getFullYear()+'-'+month+'-'+dateNow.getDate()
    console.log(date);
    $.ajax({ 
    type: 'GET', 
    url: "{{ route('ambilNomor') }}"+"?jenis=antidatir&tanggalPengesahan="+date, 
    statusCode: {
            404: function(xhr) {
              new Audio("audio/error-edited.mp3").play();
              Swal.fire("Gagal!", xhr.responseText, "error");
            },
            401: function(xhr) {
              new Audio("audio/error-edited.mp3").play();
              Swal.fire("Gagal!", xhr.responseText, "error");
            },
            200: function(xhr) {
              $('#nomorSurat').attr('value',xhr)
            }
          },
});
    }
  }
</script>
<script>
  $(document).ready(function () {
    var start = $('#inputTanggalStart').attr('value')
    var end = $('#inputTanggalEnd').attr('value')
    oke = false
    $('#inputTanggalStart').change(function(){
      console.log(start)
      console.log(end)
      start = this.value
      if(start&&end){
        window.location.href = '{{ route('suratAntidatir') }}'+'?start='+start+'&end='+end;
      }
    })
    $('#inputTanggalEnd').change(function(){
      console.log(start)
      console.log(end)
      end = this.value
      if(start&&end){
        window.location.href = '{{ route('suratAntidatir') }}'+'?start='+start+'&end='+end;
      }
    })
});
</script>
@if (isset($_GET['start']) and isset($_GET['end']))
<script>
start = "{{ $_GET['start'] }}"
end = "{{ $_GET['end'] }}" 
$('#inputTanggalStart').attr('value',start)
$('#inputTanggalEnd').attr('value',end)
</script>
@endif
<!-- Data tables -->
<script
  type="text/javascript"
  charset="utf8"
  src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"
></script>
<script
  type="text/javascript"
  charset="utf8"
  src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"
></script>
<!-- Data tables : responsive -->
<script
  type="text/javascript"
  charset="utf8"
  src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"
></script>
{{-- include tombol ekspor untuk datatable --}}
<script
type="text/javascript"
charset="utf8"
src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script
type="text/javascript"
charset="utf8"
src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script
type="text/javascript"
charset="utf8"
src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script
type="text/javascript"
charset="utf8"
src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script
type="text/javascript"
charset="utf8"
src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script
type="text/javascript"
charset="utf8"
src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script
type="text/javascript"
charset="utf8"
src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script> 
    <script>
      $(document).ready(function () {
        $(".passId").click(function () {
          let url = "{{ route('getSK', ':id') }}";
          url = url.replace(':id',$(this).data('id'));
            $.ajax({ 
                type: 'GET', 
                url: url, 
                success: function (data) { 
                    $('input[name="jenisSurat"]').val('antidatir')
                    $("#tujuanSuratE").val(data.tujuanSurat)
                    $('#tanggalPengesahanE').val(data.tanggalPengesahan)   
                    $('#tujuanSuratE').attr('value',data.tujuanSurat)
                    $("#kodeHalE").val(data.kodeHal)
                    $("#kodeUnitE").val(data.kodeUnit)
                    $("#disahkanOlehE").val(data.disahkanOleh)
                    $('#sifatSuratE').val(data.sifatSurat)
                    $('#perihalE').val(data.perihal)
                    $('#jumlahLampiranE').val(data.jumlahLampiran)
                    $('#lampiranE').html(data.lampiran)
                  }
            });
            $('input[name="idSurat"]').attr('value',$(this).data('id'));
        });
    });
    </script>
<!-- Initializing data tables -->
<script>
  var btn = document.getElementById("tes");
  $(document).ready(function () {
    $("#mytable").DataTable({
      responsive: {
            details: {
              display: $.fn.dataTable.Responsive.display.childRowImmediate,
              type: "none",
              target: "",
            },
          },
      // dom: '<t<"d-flex align-items-center justify-content-between mt-3"<"d-flex align-items-center"li><"right"p>>>',
      // dom: '<"table-responsive"tpf>',
      dom: 'Bfrtip',
      buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                },
                className:'mybtn blue'
  
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                },
                className:'mybtn blue'
  
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3,4 ]
                },
                className:'mybtn blue'
  
            },{
              extend: 'print',
              exportOptions: {
                columns: [0,1,2,3,4]
              },
                className:'mybtn blue'
            },
        ],
      destroy: true,
      order: [[0, "asc"]],
      language: {
        lengthMenu: "Tampilkan _MENU_",
        zeroRecords:
          "Anda belum mengupload laporan kegiatan. <br>Silahkan upload laporan kegiatan terlebih dahulu.",
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
          sNext:
            '<span class="pagination-fa"><i class="fa-solid fa-angle-right"></i></span>',
          sPrevious:
            '<span class="pagination-fa"><i class="fa-solid fa-angle-left"></i></span>',
        },
      },
      lengthMenu: [
        [5, 10, 20, -1],
        [5, 10, 20, "All"],
      ],
    });
  });
</script>
{{-- set value of duet date picker --}}
<script>
  var dateNow = "";
  const picker = document.querySelector("duet-date-picker")
  picker.addEventListener("duetChange", function(event) {
    $('input[name="nomorSurat"]').attr('value','');
    dateNow = event.detail.valueAsDate
  });
</script>
<script>
    function berhasil(txt){
    new Audio("audio/success-edited.mp3").play();
    // Swal.fire("Berhasil!", `${txt}`, "success");
    Swal.fire({
      confirmButtonColor: "#2F5596",
      icon: 'success',
      title: `berhasil`,
      text: `${txt}`,
    })
  }
function gagal(txt){
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
<script>berhasil("{{ Session::get('success') }}")</script>
</div>
@endif
@if ($message = Session::get('failed'))
<script>gagal("{{ Session::get('failed') }}")</script>
</div>
@endif


@endsection
@section('sa','active')
@section('title','Surat Antidatir')