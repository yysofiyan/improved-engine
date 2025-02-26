@extends('layouts.master-keuangan')

@section('title', 'Konfirmasi Pembayaran Calon Mahasiswa Baru')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Konfirmasi Calon Mahasiswa Baru </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Data Konfirmasi Calon Mahasiswa Baru </h4>

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

    {{-- <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="CustomerForm" name="CustomerForm" class="form-horizontal">
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name" class="col-sm-6 control-label">Nama Pengguna</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Masukan Nama Pengguna" value="" required autofocus>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name" class="col-sm-6 control-label">Email Pengguna</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="email" name="email"
                                            placeholder="Masukan Email" value="" required="">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name" class="col-sm-6 control-label">Password</label>
                                    <div class="col-sm-12">
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Masukan Password" value="" required autofocus>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name" class="col-sm-12 control-label">Konfirmasi Password</label>
                                    <div class="col-sm-12">
                                        <input type="password" class="form-control" id="konfirmasi_password"
                                            name="konfirmasi_password" placeholder="Masukan Konfirmasi Password"
                                            value="" required="">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-9">

                            </div>
                            <div class="col-sm-3">
                                <div class="col-sm-offset-2 col-sm-12 ml-4">
                                    <button type="submit" class="btn btn-primary" id="saveBtn" value="create"><i
                                            class="fas fa-save"></i> Simpan
                                    </button>
                                </div>

                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div> --}}

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

            $('#formImport').submit(function(e) {
                $('#btnImport').attr('disabled', true);
                $('#btnImport').html('<i class="fa-solid fa-spinner fa-spin fa-fw mr-2"></i>Proses Import');
            });



            let table = $('#refMember').DataTable({
                ajax: {
                    url: 'konfirmasi-bayar'
                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pin'
                    },
                    {
                        data: 'nomor_pendaftaran'
                    },
                    {
                        data: 'nama_mahasiswa'
                    },
                    {
                        data: 'tanggal_bayar'
                    },
                    {
                        data: 'nama_rekening_pengirim'
                    },
                    {
                        data: 'total_bayar'
                    },
                    {
                        data: 'lunas'
                    },
                    {
                        data: 'id'
                    },
                    {
                        data: 'id'
                    },
                    {
                        data: 'bukti_bayar',
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `<a class="btn btn-outline-primary" target="_blank" href="/images/pmb/${row['bukti_bayar']}">Lihat</a>`;
                        },
                        className: 'text-center'
                    },


                ],
                'columnDefs': [{
                        "targets": 7,
                        "className": "text-center",
                        "render": function(data, type, row, meta) {
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
                        "render": function(data, type, row, meta) {
                            return "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal-delete' data-id='" +
                                data +
                                "'' data-original-title='Delete' class='btn btn-sm btn-danger deleteUser' >Validasi</a>";
                        }
                    },
                    {
                        "targets": 9,
                        "className": "text-center",
                        "render": function(data, type, row, meta) {
                            return "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal-delete' data-id='" +
                                data +
                                "'' data-original-title='Delete' class='btn btn-sm btn-info reminderUser' >Reminder</a>";
                        }
                    },
                ]

            });
            table.on('order.dt search.dt', function() {
                table.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            });
            $('#refMember').on('click', '.deleteUser', function() {

                var Customer_id = $(this).data("id");
                Swal.fire({
                    icon: 'question',
                    title: 'Apakah anda yakin akan mengkonfirmasi pembayaran ?',
                    showCancelButton: true,
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Ya',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "verifikasi" + '/' + Customer_id,
                            success: function(data) {
                                table.ajax.url('konfirmasi-bayar').load();
                                Swal.fire(data.success, '', 'success')
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });

                    } else if (result.isDenied) {
                        Swal.fire('Tidak Terjadi Perubahan Data', '', 'info')
                    }
                })


            });


            $('#refMember').on('click', '.reminderUser', function() {

                var Customer_id = $(this).data("id");
                Swal.fire({
                    icon: 'question',
                    title: 'Ingatkan Calon Mahasiswa Baru tentang Pembayaran ?',
                    showCancelButton: true,
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Ya',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "reminder" + '/' + Customer_id,
                            success: function(data) {
                                table.ajax.url('konfirmasi-bayar').load();
                                Swal.fire(data.success, '', 'success')
                            },
                            error: function(data) {
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
