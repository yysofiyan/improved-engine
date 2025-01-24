@extends('layouts.master-admin')

@section('title', 'Calon Mahasiswa Baru')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 mb-4 mb-xl-0">
                <h3 class="font-weight-bold"> Calon Mahasiswa Baru </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Data Calon Mahasiswa Baru </h4>
                
                <div class="table-responsive">
                    <table id="refMember" class="display expandable-table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>TGL DAFTAR</th>
                                <th>NOMOR DAFTAR/PIN</th>
                                <th>NAMA LENGKAP</th>
                                <th>NOMOR TELEPON</th>
                                <th>PROGRAM STUDI</th>
                                <th>SMA</th>
                                <th>OPERATOR</th>
                                <th>AKSI</th>
                                <th>CETAK</th>
                                <th>KIRIM</th>
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
                { data: 'tanggal_daftar' },
                { data: 'nomor_daftar' },
                { data: 'nama_mahasiswa' },
                { data: 'handphone' },
                { data: 'nama_prodi' },
                { data: 'sma' },
                { data: 'operator' },
                { data: 'pin' },
                { data: 'nomor_pendaftaran' },
                { data: 'pin' }
            ],
            'columnDefs': [
                {
                    "targets": 6,
                    "className": "text-center",
                    "render": function (data, type, row, meta) { 
                            return '<span class="badge badge-success">'+ data + '</span>';
                       

                    }
                },
            {
                "targets": 7,
                "className": "text-center",
                "render": function ( data, type, row, meta ) {
                    // Update Button
                 $updateButton = "<a href='lihat/"+ data +"' class='btn btn-sm btn-info'  ><i class='fa-solid fa-pen-to-square'></i></a>";
                 // Delete Button
                
                    return $updateButton;

                }
            },
            {
                "targets": 8,
                "className": "text-center",
                "render": function ( data, type, row, meta ) {
                    // Update Button
                 $updateButton = "<a target='_blank' href='lihatpdf/"+ data +"' class='btn btn-sm btn-info'  ><i class='fa-solid fa-print'></i></a>";
                 // Delete Button
                
                    return $updateButton;

                }
            },
            {
                "targets": 9,
                "className": "text-center",
                "render": function ( data, type, row, meta ) {
                    return "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal-delete' data-id='" + data + "'' data-original-title='Delete' class='btn btn-sm btn-info reminderUser' ><i class='fa-brands fa-whatsapp'></i></a>";

                }
            },
        ]

        });
        table.on('order.dt search.dt', function () {
            
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
      title: 'Kirim PIN Calon Mahasiswa Baru ?',
      showCancelButton: true,
      cancelButtonText:'Tidak',
      confirmButtonText: 'Ya',
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
      $.ajax({
          type: "POST",
          url: "sendpin"+'/'+Customer_id,
          success: function (data) {
              table.ajax.url('camaba').load();
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
