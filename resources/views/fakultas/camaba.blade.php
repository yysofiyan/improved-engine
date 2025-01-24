@extends('layouts.master-fakultas')

@section('title', 'Calon Mahasiswa Baru')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 mb-4 mb-xl-0">
                <h3 class="font-weight-bold"> Calon Mahasiswa Baru {{ \App\Helpers\AkademikHelpers::getFakultasNama(session('kodeprodi')) }} </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Data Calon Mahasiswa Baru {{ \App\Helpers\AkademikHelpers::getFakultasNama(session('kodeprodi')) }} </h4>
                
                <div class="table-responsive">
                    <table id="refMember" class="display expandable-table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NOMOR DAFTAR/PIN</th>
                                <th>NIM</th> 
                                <th>NAMA LENGKAP</th>
                                <th>NOMOR TELEPON</th>
                                <th>ASAL SEKOLAH</th>
                                <th>PROGRAM STUDI</th>
                                <th>STATUS</th>
                                <th>OPERATOR</th>
                                <th>CETAK</th>
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
                url: 'camaba'
            },
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: 'nomor_daftar' },
                { data: 'nim' },
                { data: 'nama_mahasiswa' },
                { data: 'handphone' },
                { data: 'asal_sekolah' },
                { data: 'nama_prodi' },
                { data: 'is_lulus' },
                { data: 'operator' },
                { data: 'nomor_pendaftaran' },
            ],
            'columnDefs': [
                {
                    "targets": 7,
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        if (data == '1') {
                            return '<span class="badge badge-success">Lulus</span>';
                        } else {
                            return '<span class="badge badge-danger">Belum Lulus</span>';
                        }

                    }
                },
                {
                    "targets": 8,
                    "className": "text-center",
                    "render": function (data, type, row, meta) { 
                            return '<span class="badge badge-success">'+ data + '</span>';
                       

                    }
                },
            {
                "targets": 9,
                "className": "text-center",
                "render": function ( data, type, row, meta ) {
                    // Update Button
                 $updateButton = "<a target='_blank' href='lihatpdf/"+ data +"' class='btn btn-sm btn-info'  ><i class='fa-solid fa-print'></i></a>";
                 // Delete Button
                
                    return $updateButton;

                }
            }
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
    });

   

</script>
@endpush
