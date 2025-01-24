@extends('layouts.master-admin')

@section('title', 'Laporan Pembayaran Online Calon Mahasiswa Baru')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Laporan Pembayaran Online Calon Mahasiswa Baru </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Data Pembayaran Online Calon Mahasiswa Baru </h4>
                <form id="MabaForm" name="MabaForm" action="{{url('admin/pos_online')}}" method="POST">
                    @csrf
                <div class="row">
                    <div class="form-group col-lg-4 @error('periode1') has-danger @enderror">
                        <label class="col-sm-12 "><strong>Periode Tanggal</strong></label>
                            <div class="col-sm-12">
                                <input type="text" id="date1" class="form-control form-control-danger" name="periode1" value="{{old('periode1',session('tanggal1'))}}" autocomplete="off">
                                @error('periode1')
                                <label class="error text-danger">{{ $message }}</label>
                                @enderror
                            </div>
    
    
                    </div>
                    <div class="form-group col-lg-4 @error('periode2') has-danger @enderror">
                        <label class="col-sm-12 ">&nbsp</label>
                            <div class="col-sm-12">
                                <input type="text" id="date2" class="form-control form-control-danger" name="periode2" value="{{old('periode2',session('tanggal2'))}}" autocomplete="off">
                                @error('periode2')
                                <label class="error text-danger">{{ $message }}</label>
                                @enderror
                            </div>
    
    
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-lg-12 ml-3">
                        <button type="submit" id="saveBtn" class="btn btn-primary"> <i class="fa-solid fa-filter fa-fw mr-2"></i>Filter </button>
                        <a target="_blank" href="{{ url('/admin/printpdf')}}" class="btn btn-info mr-2"> <i class="fa-solid fa-print fa-fw mr-2"></i> Cetak</a>
                        <a href="{{ url('/admin/reset-online')}}" class="btn btn-warning mr-2"> <i class="fa-solid fa-repeat fa-fw mr-2"></i> Reset</a>
                    </div>
                    

                </div>
            </form>
                <br/>
                    
                <div class="table-responsive">
                    <table id="refMember" class="display expandable-table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PIN</th>
                                <th>NO PENDAFTARAN</th>
                                <th>NAMA LENGKAP</th>
                                <th>TANGGAL BAYAR</th>
                                <th>NAMA PENGIRIM</th>
                                <th>BAYAR</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                                <th>WHATSAPP</th>
                                <th>File</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('page-stylesheet')
@endpush

@push('page-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    $(function() {
      $("#date1").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: new Date(),
        autoclose: true,
        changeMonth: true,
        changeYear: true,
      });
    });
    </script>

<script>
    $(function() {
      $("#date2").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: new Date(),
        autoclose: true,
        changeMonth: true,
        changeYear: true,
      });
    });
    </script>

<script>
   

    $(document).ready(function() {
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });


        $('.file-upload-browse').on('click', function() {
            let file = $(this).parent().parent().parent().find('.file-upload-default');
            file.trigger('click');
        });
        $('.file-upload-default').on('change', function() {
            $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        });

        $('#formImport').submit(function (e) {
            $('#btnImport').attr('disabled', true);
            $('#btnImport').html('<i class="fa-solid fa-spinner fa-spin fa-fw mr-2"></i>Proses Import');
        });

      

        let table = $('#refMember').DataTable({
            ajax: {
                url: 'online'
            },
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: 'pin' },
                { data: 'nomor_pendaftaran' },
                { data: 'nama_mahasiswa' },
                { data: 'tanggal_bayar' },
                { data: 'nama_rekening_pengirim' },
                { data: 'total_bayar' },
                { data: 'lunas' },
                { data: 'id' },
                { data: 'id' },
                {
                    data: 'bukti_bayar', searchable: false,
                    render: function (data, type, row, meta) {
                        return `<a class="btn btn-outline-primary" target="_blank" href="/images/pmb/${row['bukti_bayar']}">Lihat</a>`;
                    },
                    className: 'text-center'
                },


            ],
            'columnDefs': [
                {
                    "targets": 7,
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        if (data == '11') {
                            return '<span class="badge badge-success">Verifikasi</span>';
                        } else {
                            return '<span class="badge badge-danger">Belum Verifikasi</span>';
                        }

                    }
                },
                {
                    "targets": 8,
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                            return "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal-delete' data-id='" + data + "'' data-original-title='Delete' class='btn btn-sm btn-danger deleteUser' >Validasi</a>";
                    }
                },
                {
                    "targets": 9,
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                            return "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal-delete' data-id='" + data + "'' data-original-title='Delete' class='btn btn-sm btn-info reminderUser' >Reminder</a>";
                    }
                },
        ]

        });
        table.on('order.dt search.dt', function () {
            table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i+1;
            });
        });
        $('#refMember').on('click', '.deleteUser', function () {

var Customer_id = $(this).data("id");
Swal.fire({
      icon: 'question',
      title: 'Apakah anda yakin akan mengkonfirmasi pembayaran ?',
      showCancelButton: true,
      cancelButtonText:'Tidak',
      confirmButtonText: 'Ya',
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
      $.ajax({
          type: "POST",
          url: "verifikasi"+'/'+Customer_id,
          success: function (data) {
              table.ajax.url('konfirmasi-bayar').load();
              Swal.fire(data.success, '', 'success')
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

  } else if (result.isDenied) {
      Swal.fire('Tidak Terjadi Perubahan Data', '', 'info')
  }
})


});


$('#refMember').on('click', '.reminderUser', function () {

var Customer_id = $(this).data("id");
Swal.fire({
      icon: 'question',
      title: 'Ingatkan Calon Mahasiswa Baru tentang Pembayaran ?',
      showCancelButton: true,
      cancelButtonText:'Tidak',
      confirmButtonText: 'Ya',
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
      $.ajax({
          type: "POST",
          url: "reminder"+'/'+Customer_id,
          success: function (data) {
              table.ajax.url('konfirmasi-bayar').load();
              Swal.fire(data.success, '', 'success')
          },
          error: function (data) {
              console.log('Error:', data);
          }
      });

  } else if (result.isDenied) {
      Swal.fire('Tidak Terjadi Perubahan Data', '', 'info')
  }
})


});
    });

   

</script>
@endpush
