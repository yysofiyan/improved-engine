<table>
    <tr>
        <td colspan="5"><h2>Rekap Pendaftaran PMB Tahun {{ $tahun }}</h2></td>
    </tr>
    <tr>
        <td colspan="5">Per Tanggal : {{ $tanggal }}</td>
    </tr>
    <thead>
        <tr>
            <th>#</th>
            <th>Fakultas/Prodi</th>
            <th>Jumlah Pendaftar</th>
            <th>Jumlah PIN Aktif</th>
            <th>Jumlah Lulus</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td><b>{{ $row['fakultas'] }}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @foreach($row['prodi'] as $prodi)
            <tr>
                <td></td>
                <td>{{ $prodi['nama'] }}</td>
                <td>{{ $prodi['pendaftar'] }}</td>
                <td>{{ $prodi['pin_aktif'] }}</td>
                <td>{{ $prodi['lulus'] }}</td>
            </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="2"><b>Total Keseluruhan</b></td>
            <td>{{ $total['pendaftar'] }}</td>
            <td>{{ $total['pin'] }}</td>
            <td>{{ $total['lulus'] }}</td>
        </tr>
    </tbody>
</table> 