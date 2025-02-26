@extends('layouts.master-dashboard')

@section('title', 'Riwayat Calon Mahasiswa Baru')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Riwayat Calon Mahasiswa Baru </h3>
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
                                <th>#</th>
                                <th>PIN</th>
                                <th>NAMA LENGKAP</th>
                                <th>NOMOR TELEPON</th>
                                <th>PROGRAM STUDI</th>
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
        // Setup CSRF token untuk semua request AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Kode ini mengarah ke OperatorController::riwayat()
        // Route: /operator/riwayat-daftar (GET)
        // Controller: OperatorController.php
        // Method: riwayat()
        // Fungsi: Mengambil data riwayat pendaftaran mahasiswa oleh operator
        let table = $('#refMember').DataTable({
            ajax: {
                url: 'riwayat-daftar' // Mengarah ke route 'riwayat-daftar'
            },
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: 'pin' },
                { data: 'nama_mahasiswa' },
                { data: 'handphone' },
                { data: 'nama_prodi' },
                { data: 'pin' },
            ],
            'columnDefs': [
                {
                    "targets": 5,
                    "className": "text-center",
                    "render": function ( data, type, row, meta ) {
                        // Tombol update mengarah ke OperatorController::lihatPin()
                        // Route: /operator/lihat/{pin} (GET)
                        // Controller: OperatorController.php
                        // Method: lihatPin()
                        // Fungsi: Menampilkan form untuk melihat/mengedit data mahasiswa berdasarkan PIN
                        $updateButton = "<a href='lihat/"+ data +"' class='btn btn-sm btn-info'><i class='fa-solid fa-pen-to-square'></i></a>";
                        return $updateButton;
                    }
                }
            ]
        });

        // Penomoran otomatis untuk kolom pertama
        table.on('order.dt search.dt', function () {
            table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i+1;
            });
        });

        // Kode untuk menghapus member (tidak digunakan dalam konteks ini)
        $('#refMember').on('click', '.deleteUser', function () {
            var Customer_id = $(this).data("id");
            Swal.fire({
                icon: 'question',
                title: 'Apakah akan menghapus member ?',
                showCancelButton: true,
                cancelButtonText:'Tidak',
                confirmButtonText: 'Ya',
            }).then((result) => {
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
            });
        });
    });
</script>
@endpush
