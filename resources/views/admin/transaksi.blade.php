@extends('layouts.master-admin')

@section('title', 'Keuangan Transaksi Calon Mahasiswa Baru')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Keuangan Transaksi Calon Mahasiswa Baru </h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Data Transaksi </h4>
                {{-- <a href="{{ url('keuangan/tambah-pin') }}" class="btn btn-primary" style="margin-bottom: 20px" id="btnImport">Tambah PIN</a> --}}
                <div class="table-responsive">
                    <table id="refMember" class="display expandable-table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PIN</th>
                                <th>NO TRANSAKSI</th>
                                <th>NAMA</th>
                                <th>TOTAL</th>
                                <th>STATUS</th>
                               
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
                url: 'transaksi'
            },
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: 'pin' },
                { data: 'no_transaksi' },
                { data: 'nama_mahasiswa' },
                { data: 'total' },
                { data: 'status' },
            ],
            'columnDefs': [
                {
                    "targets": 5,
                    "className": "text-center",
                    "render": function (data, type, row, meta) {
                        if (data == '11') {
                            return '<span class="badge badge-info">Lunas</span>';
                        } else {
                            return '<span class="badge badge-warning">Belum Lunas</span>';
                        }

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
    });

   

</script>
@endpush
