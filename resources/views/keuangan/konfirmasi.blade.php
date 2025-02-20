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
        
        <!-- Tambahkan Filter Tahun di Sini -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Tahun Akademik:</label>
                    <select class="form-control" id="filterTahunKonfirmasi">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunMasuk as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == $tahunAktif ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelKonfirmasi" class="data-table table table-striped" style="width:100%">
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
                        <tbody>
                            <!-- Data diisi via DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
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
                                <input type="text" class="form-control" id="name" name="name" placeholder="Masukan Nama Pengguna" value=""  required autofocus>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-6 control-label">Email Pengguna</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Masukan Email" value="" required="" >
                            </div>
                        </div>

                    </div>
                   </div>

                   <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-6 control-label">Password</label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukan Password" value=""  required autofocus>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label">Konfirmasi Password</label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" placeholder="Masukan Konfirmasi Password" value="" required="" >
                            </div>
                        </div>

                    </div>
                   </div>

                    <div class="row">
                        <div class="col-sm-9">

                        </div>
                        <div class="col-sm-3">
                            <div class="col-sm-offset-2 col-sm-12 ml-4">
                                <button type="submit" class="btn btn-primary" id="saveBtn" value="create"><i class="fas fa-save"></i> Simpan
                                </button>
                               </div>

                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>

<!-- Temporary debug -->
<div class="alert alert-info">
    Data Tahun dari DB: {{ implode(', ', $tahunMasuk->toArray()) }}
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

      

        var table = $('#tabelKonfirmasi').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("keuangan.konfirmasi.data") }}',
                data: function(d) {
                    d.tahun = $('#filterTahunKonfirmasi').val();
                },
                error: function(xhr, error, thrown) {
                    console.error('AJAX Error:', error, thrown);
                }
            },
            columns: [
                { 
                    data: 'no',
                    name: 'no',
                    orderable: false,
                    searchable: false
                },
                { data: 'pin', name: 'pin' },
                { data: 'nomor_pendaftaran', name: 'nomor_pendaftaran' },
                { data: 'nama_mahasiswa', name: 'nama_mahasiswa' },
                { data: 'tanggal_bayar', name: 'tanggal_bayar' },
                { data: 'nama_rekening_pengirim', name: 'nama_rekening_pengirim' },
                { 
                    data: 'total_bayar',
                    name: 'total_bayar',
                    render: function(data, type, row) {
                        return 'Rp ' + data;
                    }
                },
                { 
                    data: 'lunas',
                    name: 'lunas',
                    render: function(data) {
                        return data === 'Verifikasi' ? 
                            '<span class="badge badge-success">Verifikasi</span>' : 
                            '<span class="badge badge-danger">Belum Verifikasi</span>';
                    }
                },
                {
                    data: 'id',
                    name: 'aksi',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `
                            <div class="btn-group">
                                <button class="btn btn-sm btn-danger verifikasi" data-id="${data}">
                                    <i class="fas fa-check-circle"></i> Validasi
                                </button>
                            </div>
                        `;
                    }
                },
                {
                    data: 'id',
                    name: 'reminder',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `
                            <button class="btn btn-sm btn-info reminder" data-id="${data}">
                                <i class="fas fa-bell"></i> Reminder
                            </button>
                        `;
                    }
                },
                {
                    data: 'bukti_bayar',
                    name: 'bukti_bayar',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `
                            <a href="${data}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                        `;
                    }
                }
            ],
            language: {
                url: "/vendor/datatables/Indonesian.json"
            },
            order: [[4, 'desc']]
        });

        // Filter tahun
        $('#filterTahunKonfirmasi').change(function() {
            table.ajax.reload();
        });

        // Fungsi verifikasi
        $('#tabelKonfirmasi').on('click', '.verifikasi', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Verifikasi Pembayaran?',
                text: "Anda yakin ingin memverifikasi pembayaran ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Verifikasi!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/keuangan/konfirmasi/' + id + '/verifikasi',
                        method: 'PUT',
                        success: function(response) {
                            Swal.fire('Sukses!', 'Pembayaran berhasil diverifikasi', 'success');
                            $('#tabelKonfirmasi').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });

        // Fungsi reminder
        $('#tabelKonfirmasi').on('click', '.reminder', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Kirim Reminder WhatsApp?',
                input: 'text',
                inputLabel: 'Pesan Custom',
                inputValue: 'Yth. Calon Mahasiswa, kami mengingatkan untuk segera melakukan pembayaran...',
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Batal',
                preConfirm: (pesan) => {
                    return $.ajax({
                        url: '/keuangan/konfirmasi/' + id + '/reminder',
                        method: 'POST',
                        data: { pesan: pesan },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Sukses!', 'Reminder berhasil dikirim', 'success');
                }
            });
        });
    });

   

</script>
@endpush
