@extends('layouts.master-keuangan')

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

    <div class="row mb-4">
        <div class="col-md-3">
            <select class="form-control" id="filterTahun">
                <option value="">Semua Tahun</option>
                @foreach ($tahunMasuk as $tahun)
                    <option value="{{ $tahun }}" {{ $tahun == $tahunAktif ? 'selected' : '' }}>
                        {{ $tahun }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelTransaksi" class="data-table table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>PIN</th>
                                    <th>No. Transaksi</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data diisi via DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-stylesheet')
    <style>
        #tabelTransaksi thead th {
            background: linear-gradient(45deg, #4B49AC, #4B49AC);
            color: white;
            border-bottom: 2px solid #4B49AC;
        }
    </style>
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

            var table = $('#tabelTransaksi').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/keuangan/transaksi/data',
                    data: function(d) {
                        d.tahun = $('#filterTahun').val();
                    }
                },
                columns: [{
                        data: 'no',
                        name: 'no'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'pin',
                        name: 'pin'
                    },
                    {
                        data: 'no_transaksi',
                        name: 'no_transaksi'
                    },
                    {
                        data: 'nama_mahasiswa',
                        name: 'nama_mahasiswa'
                    },
                    {
                        data: 'total',
                        name: 'total',
                        render: function(data, type, row) {
                            return 'Rp ' + data;
                        }
                    },
                    {
                        data: 'status_badge',
                        name: 'status_badge',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#refMember').on('click', '.deleteUser', function() {

                var Customer_id = $(this).data("id");
                Swal.fire({
                    icon: 'question',
                    title: 'Apakah akan menghapus member ?',
                    showCancelButton: true,
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Ya',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "member/hapus" + '/' + Customer_id,
                            success: function(data) {
                                table.ajax.url('member').load();
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

            $('#filterTahun').change(function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
