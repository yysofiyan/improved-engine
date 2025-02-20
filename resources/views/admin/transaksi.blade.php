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
                                <th>TANGGAL</th>
                                <th>PIN</th>
                                <th>NO TRANSAKSI</th>
                                <th>NAMA</th>
                                <th>TOTAL</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td data-order="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                </td>
                                <td>{{ $item->pin }}</td>
                                <td>{{ $item->no_transaksi }}</td>
                                <td>{{ $item->mahasiswa->nama_mahasiswa ?? 'N/A' }}</td>
                                <td>Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->status == '11')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($item->status == '10')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Unknown</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-danger">
                                    <i class="fas fa-database"></i> Tidak ada data transaksi
                                </td>
                            </tr>
                            @endforelse
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
.badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
}
.badge-success { background: #28a745; color: white; }
.badge-warning { background: #ffc107; color: black; }
.badge-danger { background: #dc3545; color: white; }
</style>
@endpush

@push('page-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>
    $(document).ready(function() {
        $('#refMember').DataTable({
            "order": [[1, "desc"]],  // Urutkan berdasarkan kolom tanggal (index 1)
            "columnDefs": [
                {
                    "type": "date-eu",
                    "targets": 1  // Terapkan ke kolom tanggal
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });

    // Tambahkan custom sorting untuk format tanggal dd/mm/YYYY
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-eu-pre": function(date) {
            if (!date) return 0;
            var dateParts = date.split('/');
            return Date.parse(dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0]);
        },
        "date-eu-asc": function(a, b) {
            return a - b;
        },
        "date-eu-desc": function(a, b) {
            return b - a;
        }
    });
</script>
@endpush
