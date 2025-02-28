<html>

<head>
    <style>
        body {
            font-family: Tahoma, Verdana, Segoe, sans-serif;
        }

        .header {
            font-weight: bold;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .table th {
            border: 1px solid #000;
        }

        .table td {
            border: 1px solid #000;
            padding: 5px 5px;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <img style="margin-top: -50px" src="{{ $headers['HEADER_LOGO'] }}" width="100%" height="15%">
    <h2 style="margin-bottom: -50px">PENGUMUMAN KELULUSAN MAHASISWA BARU</h2>
    <p style="font-size:14px;text-align:center;font-weight: bold;margin-top:60px;">
        ======================================================================</p>
    <p style="font-size:16px;text-align:center">Panitia Penerimaan Mahasiswa Baru (PMB) Universitas Sebelas April dan
        STAI Sebelas April Sumedang Tahun 2024 menyatakan bahwa :</p>
    <table style="font-size:14px">
        <tbody>

            <tr>
                <td><strong>NIK</strong></td>
                <td>:</td>
                <td>{{ $formulir->nik }}</td>
            </tr>

            <tr>
                <td><strong>Nama</strong></td>
                <td>:</td>
                <td>{{ strtoupper($formulir->nama_mahasiswa) }}</td>
            </tr>
            <tr>
                <td><strong>Tempat/Tanggal Lahir </strong></td>
                <td>:</td>
                <td>{{ $formulir->tempat_lahir }} /
                    {{ \App\Helpers\AkademikHelpers::tanggal('d F Y', $formulir->tanggal_lahir) }}</td>
            </tr>
            <tr>
                <td><strong>Nama Ibu Kandung </strong></td>
                <td>:</td>
                <td>{{ strtoupper($formulir->nama_ibu_kandung) }}</td>
            </tr>
            <tr>
                <td><strong>Nomor Pendaftaran</strong></td>
                <td>:</td>
                <td>{{ $formulir->nomor_pendaftaran }}</td>
            </tr>
            <tr>
                <td><strong>Asal Sekolah/PT</strong></td>
                <td>:</td>
                <td>{{ $formulir->asal_sekolah }}</td>
            </tr>
            <tr>
                <td><strong>Kabupaten/Kota</strong></td>
                <td>:</td>
                <td>{{ \App\Helpers\AkademikHelpers::getSekolahKab($formulir->asal_sekolah) }}</td>
            </tr>
        </tbody>
    </table>

    <p style="font-size:14px;text-align:center">Dinyatakan LULUS sebagai Calon Mahasiswa Baru pada :</p>

    <table style="font-size:14px">
        <tbody>
            <tr>
                <td><strong>Program Studi</strong></td>
                <td>:</td>
                <td>{{ $prodi->nama_prodi . ' - ' . $prodi->nama_jenjang }}</td>
            </tr>
            <tr>
                <td><strong>Fakultas</strong></td>
                <td>:</td>
                <td>{{ \App\Helpers\AkademikHelpers::getFakultasNama($prodi->kode_fakultas) }}</td>
            </tr>
        </tbody>
    </table>
    <br />
    <table style="font-size:14px">
        <tbody>
            <tr>
                <td><strong>Informasi PMB 2024/2025</strong></td>
                <td>:</td>

            </tr>

        </tbody>
    </table>
    <table style="font-size:14px;width:100%">
        <tbody>
            <tr>
                <td style="text-align:center;vertical-align:top;width:5%">1. </td>
                <td style="text-align:justify;" colspan="2">Paling lambat <strong>satu minggu setelah hari
                        ini</strong>, Saudara wajib melakukan registrasi dengan membayar lunas keuangan sesuai dengan
                    ketentuan yang telah ditetapkan YPSA Sumedang.</td>
            </tr>
            <tr>
                <td style="text-align:center;vertical-align:top;width:5%">2. </td>
                <td style="text-align:justify;" colspan="2">Seluruh pembayaran keuangan dilakukan pada <strong>Bank
                        BNI Cabang Sumedang</strong> dengan nomor rekening <strong>22022043</strong> atas nama Yayasan
                    Pendidikan 11 April Sumedang</td>
            </tr>
            <tr>
                <td style="text-align:center;vertical-align:top;width:5%">3. </td>
                <td style="text-align:justify;" colspan="2">Bukti pembayaran dari bank, diserahkan langsung ke
                    <strong>Sekretariat YPSA</strong> (setiap hari kerja) <strong>paling lambat 1 hari</strong> setelah
                    pembayaran dari bank atau Saudara bisa upload pada laman https://online.ypsa-sumedang.or.id.</td>
            </tr>
            <tr>
                <td style="text-align:center;vertical-align:top;width:5%">4. </td>
                <td style="text-align:justify;" colspan="2"><strong>Khusus </strong> Calon Mahasiswa Baru
                    <strong>Program Studi Pendidikan Jasmani Kesehatan dan Rekreasi (PJKR) FKIP </strong>, wajib
                    mengikuti seleksi kesehatan dan fisik yang akan dilaksanakan <strong>Kamis, 31 Agustus 2024 Pukul
                        08.00 WIB s.d. selesai</strong> di Kampus I Universitas Sebelas April dengan menggunakan pakaian
                    olahraga.</td>
            </tr>
            <tr style="margin-bottom: 20px;">
                <td style="text-align:center;vertical-align:top;width:5%">5. </td>
                <td style="text-align:justify;" colspan="2">Informasi ketentuan dan pelaksanaan kegiatan
                    <strong>Pengenalan Kehidupan Kampus bagi Mahasiswa Baru (PKKMB)</strong> akan di informasikan
                    kemudian melalui laman https://unsap.ac.id. dan group WA oleh panitia PKKMB.</td>
            </tr>

            <tr>
                <td colspan="2" style="text-align:right"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:right"></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right">Sumedang, {{ $tanggal }}</td>
            </tr>
        </tbody>
    </table>
    <table width="100%" cellspacing="0px" cellpadding="0px">
        <tr>
            <td style="text-align:center;vertical-align:top"><img src="{{ $headers['TANDATANGAN'] }}" width="500px"
                    height="170px"> </td>
            <td style="font-size:12px;text-align:center;vertical-align:top">Scan Informasi Keuangan dan Lainnya Disini
                <br /><br /> <img src="{{ $headers['qrunsap1'] }}" width="100px" height="100px"></td>
        </tr>
        <tr>
            <td style="text-align:center;vertical-align:top"></td>
            <td style="font-size:12px;text-align:center;vertical-align:top"></td>
        </tr>
    </table>
    <br />
</body>

</html>
