@extends('layouts.master-fakultas')

@section('title', 'Pendaftaran Calon Mahasiswa Baru')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Pendaftaran Calon Mahasiswa Baru {{ \App\Helpers\AkademikHelpers::getFakultasNama(session('kodeprodi')) }}</h3>
                <div class="form-group mt-3">
                    <select class="form-control" id="tahunFilter" style="width: 200px;">
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == date('Y') ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Pendaftaran Data Calon Mahasiswa Baru {{ \App\Helpers\AkademikHelpers::getFakultasNama(session('kodeprodi')) }} </h4>
                
                <div class="table-responsive">
                    <table id="refMember" class="display expandable-table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NOMOR DAFTAR/PIN</th>
                                <th>NAMA LENGKAP</th>
                                <th>NOMOR TELEPON</th>
                                <th>PROGRAM STUDI</th>
                                <th>STATUS PIN</th>
                                <th>AKSI</th>
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
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            ajax: {
                url: '{{ route("pendaftaran.data") }}',
                data: function(d) {
                    d.tahun = $('#tahunFilter').val();
                }
            },
            columns: [
                { data: null, orderable: false },
                { data: 'nomor_pendaftaran' },
                { data: 'nama_mahasiswa' },
                { data: 'handphone' },
                { data: 'nama_prodi' },
                { data: 'is_aktif' },
                { data: 'actions' }
            ],
            'columnDefs': [
                {
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, row) {
                        return row.nomor_pendaftaran;
                    }
                },
                {
                    "targets": 3,
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        $btn = `<a target="_blank" href="https://wa.me/62${row['handphone']}"> ` + data + `</a>`;
                        return $btn;
                    }
                },
                {
                    "targets": 5,
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        if (data == '1') {
                            return '<span class="badge badge-success">Aktif</span>';
                        } else {
                            return '<span class="badge badge-danger">Belum Aktif</span>';
                        }

                    }
                },
                {
                    "targets": 6,
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        $btn = `<a href="/fakultas/lihatmhs/${row['id']}" class="btn btn-info btn-sm"><i class="fa fa-fw fa-eye"></i>Lihat </a>`;

                        $btn = $btn + "&nbsp&nbsp<a href='javascript:void(0)' data-toggle='modal' data-target='#modal-delete' data-id='" + data + "'' data-original-title='Delete' class='btn btn-sm btn-warning reminderUser' ><i class='fa fa-fw fa-whatsapp'></i>Reminder</a>";
                        return $btn;
                            
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
      title: 'Apakah akan menghapus member ?',
      showCancelButton: true,
      cancelButtonText:'Tidak',
      confirmButtonText: 'Ya',
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
      $.ajax({
          type: "DELETE",
          url: "member/hapus"+'/'+Customer_id,
          success: function (data) {
                table.ajax.url('member').load();
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
              table.ajax.url('pendaftaran').load();
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

$('#tahunFilter').change(function() {
    table.ajax.reload();
});

    });

   

</script>
@endpush
