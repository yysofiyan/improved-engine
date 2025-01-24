@extends('layouts.master-admin')

@section('title', 'Manajemen Akun Fakultas PMB')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Manajemen Akun Fakultas Panitia PMB </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Manajemen Akun Fakultas PMB </h4>
                <a href="{{ url('admin/tambah-fakultas') }}" class="btn btn-primary" style="margin-bottom: 20px" id="btnImport"><i class="fa fa-fw fa-plus"></i>Tambah</a>
                @if ($message = Session::get('info'))
                <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>	
                <strong>{{ $message }}</strong>
                </div>
                @endif
                <div class="table-responsive">
                    <table id="refMember" class="display expandable-table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIDN/NITK</th>
                                <th>EMAIL</th>
                                <th>NAMA FAKULTAS</th>
                                <th>ROLE</th>
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
  
        let table = $('#refMember').DataTable({
            ajax: {
                url: 'fakultas'
            },
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: 'nidn' },
                { data: 'email' },
                { data: 'fakultas' },
                { data: 'role' },
                { data: 'id' },
                
            ],
            'columnDefs': [
            
                {
                    "targets": 5,
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        $btn = `<a href="fakultas/edit/${row['id']}" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-edit"></i> Edit</a>`;

                        $btn = $btn + ' <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-delete" data-original-title="Delete" data-id="' + row['id'] + '" class="btn btn-danger btn-sm deleteUser"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
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
      title: 'Apakah akan menghapus pengguna ?',
      showCancelButton: true,
      cancelButtonText:'Tidak',
      confirmButtonText: 'Ya',
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
      $.ajax({
          type: "DELETE",
          url: "fakultas/hapus"+'/'+Customer_id,
          success: function (data) {
                table.ajax.url('fakultas').load();
                Swal.fire(data.success, '', 'success');
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
